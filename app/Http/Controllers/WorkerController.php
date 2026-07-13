<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueAssignment;
use App\Models\StatusHistory;
use App\Models\IssueStatus;
use App\Models\IssueMedia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkerController extends Controller
{
    /**
     * Display the worker dashboard.
     */
    public function dashboard(): View
    {
        return view('worker.dashboard');
    }

    /**
     * Fetch assigned tasks for the authenticated worker.
     */
    public function getAssignments(): JsonResponse
    {
        $workerId = Auth::id();

        $assignments = IssueAssignment::where('worker_id', $workerId)
            ->with([
                'issue', 
                'issue.category', 
                'issue.area', 
                'issue.status', 
                'issue.statusHistory' => function($q) {
                    $q->orderBy('created_at', 'desc');
                },
                'issue.statusHistory.changedBy', 
                'issue.statusHistory.newStatus'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($assignment) {
                $issue = $assignment->issue;
                if (!$issue) return null;

                // Priority calculation based on upvote count
                $priority = 'Low';
                $priorityBadge = 'Low Priority';
                if ($issue->upvote_count >= 40) {
                    $priority = 'High';
                    $priorityBadge = 'High Priority';
                } elseif ($issue->upvote_count >= 20) {
                    $priority = 'Medium';
                    $priorityBadge = 'Medium Priority';
                }

                // Map database status back to visual codes: assigned, working, completed
                $statusMapping = [
                    1 => 'assigned',   // Pending
                    2 => 'assigned',   // Acknowledged
                    3 => 'working',    // In Progress
                    4 => 'working',    // On Hold
                    5 => 'completed',  // Resolved
                    6 => 'completed',  // Closed
                    7 => 'completed',  // Rejected
                ];
                $statusClass = $statusMapping[$issue->status_id] ?? 'assigned';

                $progressPercent = 15;
                if ($statusClass === 'working') {
                    $progressPercent = 65;
                } elseif ($statusClass === 'completed') {
                    $progressPercent = 100;
                }

                // Map status logs
                $updates = $issue->statusHistory->map(function ($history) {
                    return [
                        'time' => $history->created_at->diffForHumans(),
                        'status' => 'Status changed to ' . ($history->newStatus->status_name ?? 'Unknown') . ($history->remark ? ': "' . $history->remark . '"' : ''),
                        'user' => $history->changedBy->full_name ?? 'System',
                    ];
                });

                return [
                    'id' => $assignment->id,
                    'issue_id' => $issue->id,
                    'title' => $issue->title,
                    'description' => $issue->description,
                    'location' => '📍 ' . ($issue->area->area_name ?? 'N/A') . ($issue->latitude ? ' (Lat: ' . round($issue->latitude, 3) . ', Long: ' . round($issue->longitude, 3) . ')' : ''),
                    'area_name' => $issue->area->area_name ?? 'N/A',
                    'category_name' => $issue->category->category_name ?? 'Other',
                    'status' => $statusClass,
                    'status_name' => $issue->status->status_name ?? 'Pending',
                    'priority' => $priority,
                    'priority_badge' => $priorityBadge,
                    'progress' => $progressPercent,
                    'notes' => $assignment->notes ?? 'No instructions provided.',
                    'latitude' => $issue->latitude,
                    'longitude' => $issue->longitude,
                    'updates' => $updates,
                    'time_ago' => $issue->created_at->diffForHumans(),
                ];
            })
            ->filter();

        // Calculate statistics
        $totalCount = $assignments->count();
        $highPriorityCount = $assignments->where('priority', 'High')->count();
        $completedCount = $assignments->where('status', 'completed')->count();

        // Calculate average completion time
        $completedAssignments = IssueAssignment::where('worker_id', $workerId)
            ->whereHas('issue', function ($q) {
                $q->whereIn('status_id', [5, 6]);
            })
            ->with(['issue', 'issue.statusHistory'])
            ->get();

        $durations = [];
        foreach ($completedAssignments as $cAssignment) {
            $resolvedHistory = $cAssignment->issue->statusHistory
                ->whereIn('new_status_id', [5, 6])
                ->sortBy('created_at')
                ->first();
            if ($resolvedHistory) {
                $durations[] = $resolvedHistory->created_at->diffInMinutes($cAssignment->created_at);
            }
        }

        if (count($durations) > 0) {
            $avgMinutes = array_sum($durations) / count($durations);
            if ($avgMinutes >= 60) {
                $avgTimeText = round($avgMinutes / 60, 1) . ' hrs';
            } else {
                $avgTimeText = round($avgMinutes) . ' mins';
            }
            $rating = max(3.5, min(5.0, round(5.0 - (($avgMinutes / 60) / 48) * 0.5, 1)));
        } else {
            $avgTimeText = 'N/A';
            $rating = 5.0;
        }

        // Get next highest priority task
        $nextPriorityObj = $assignments->where('status', '!==', 'completed')->sortByDesc(function($a) {
            return $a['priority'] === 'High' ? 2 : ($a['priority'] === 'Medium' ? 1 : 0);
        })->first();
        $nextPriorityTitle = $nextPriorityObj ? $nextPriorityObj['title'] : 'None (All Clear)';

        return response()->json([
            'assignments' => $assignments->values(),
            'stats' => [
                'total' => $totalCount,
                'high' => $highPriorityCount,
                'completed' => $completedCount,
                'completed_text' => "{$completedCount} of {$totalCount}",
                'avg_time' => $avgTimeText,
                'rating' => "{$rating} / 5.0",
                'next_priority' => $nextPriorityTitle,
            ]
        ]);
    }

    /**
     * Update assignment parent issue status, upload file evidence, and create logs.
     */
    public function updateAssignmentStatus(Request $request, $assignmentId): JsonResponse
    {
        $assignment = IssueAssignment::findOrFail($assignmentId);
        $issue = $assignment->issue;
        $oldStatusId = $issue->status_id;

        $request->validate([
            'status' => 'required|string|in:assigned,working,completed',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $statusMap = [
            'assigned' => 2,  // Acknowledged
            'working' => 3,   // In Progress
            'completed' => 5, // Resolved
        ];

        $newStatusId = $statusMap[$request->status] ?? 2;

        // Save new status
        $issue->status_id = $newStatusId;
        $issue->save();

        // Write status history audit log
        StatusHistory::create([
            'issue_id' => $issue->id,
            'old_status_id' => $oldStatusId,
            'new_status_id' => $newStatusId,
            'changed_by' => Auth::id(),
            'remark' => $request->notes,
        ]);

        // Upload photo proof if present
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('issues', 'public');
            $url = asset('storage/' . $path);

            IssueMedia::create([
                'issue_id' => $issue->id,
                'file_url' => $url,
                'media_type' => 'image',
                'uploaded_by' => Auth::id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Work order status updated successfully!'
        ]);
    }
}
