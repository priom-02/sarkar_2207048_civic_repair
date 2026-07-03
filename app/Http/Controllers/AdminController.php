<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Fetch admin executive analytics data.
     */
    public function getAnalytics(): JsonResponse
    {
        // 1. Calculate Key Metrics
        $totalReports = Issue::count();
        
        $resolvedReports = Issue::whereHas('status', function($q) {
            $q->where('status_name', 'Resolved');
        })->count();
        
        $inProgressReports = Issue::whereHas('status', function($q) {
            $q->where('status_name', 'In Progress');
        })->count();
        
        $activeWorkers = User::where('role_id', 2)->count();

        // 2. Fetch Heatmap Pin Locations
        $locations = Issue::with('status')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($issue) {
                return [
                    'latitude' => $issue->latitude,
                    'longitude' => $issue->longitude,
                    'title' => $issue->title,
                    'upvotes' => $issue->upvote_count,
                    'status' => $issue->status->status_name ?? 'Pending'
                ];
            });

        // 3. Calculate 30-Day Weekly Trends
        $now = now();
        $weeks = [
            'Week 1' => [$now->copy()->subDays(28), $now->copy()->subDays(21)],
            'Week 2' => [$now->copy()->subDays(21), $now->copy()->subDays(14)],
            'Week 3' => [$now->copy()->subDays(14), $now->copy()->subDays(7)],
            'Week 4' => [$now->copy()->subDays(7), $now],
        ];

        // Group categories into Infrastructure vs Sanitation
        $infraIds = [1, 5, 6, 7, 9, 11]; // Road, street light, electricity, traffic, public property, footpath
        
        $trends = [];
        foreach ($weeks as $weekName => $range) {
            $infraCount = Issue::whereBetween('created_at', [$range[0], $range[1]])
                ->whereIn('category_id', $infraIds)
                ->count();

            $sanitationCount = Issue::whereBetween('created_at', [$range[0], $range[1]])
                ->whereNotIn('category_id', $infraIds)
                ->count();
                
            $trends[] = [
                'week' => $weekName,
                'infrastructure' => $infraCount,
                'sanitation' => $sanitationCount,
            ];
        }

        return response()->json([
            'stats' => [
                'total' => $totalReports,
                'resolved' => $resolvedReports,
                'inprogress' => $inProgressReports,
                'workers' => $activeWorkers,
            ],
            'locations' => $locations,
            'trends' => $trends
        ]);
    }

    /**
     * Fetch all issue categories.
     */
    public function getCategories(): JsonResponse
    {
        $categories = IssueCategory::all();
        return response()->json($categories);
    }

    /**
     * Store a newly created category.
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:80|unique:issue_categories,category_name',
            'description' => 'required|string',
        ]);

        $category = IssueCategory::create([
            'category_name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category
        ]);
    }

    /**
     * Fetch all reports with their assignment state.
     */
    public function getIssues(): JsonResponse
    {
        $issues = Issue::with(['category', 'area', 'status', 'reportedBy', 'assignments', 'assignments.worker'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($issue) {
                $assignedWorker = $issue->assignments->first();
                return [
                    'id' => $issue->id,
                    'title' => $issue->title,
                    'description' => $issue->description,
                    'category_name' => $issue->category->category_name ?? 'Other',
                    'area_name' => $issue->area->area_name ?? 'N/A',
                    'status_name' => $issue->status->status_name ?? 'Pending',
                    'status_id' => $issue->status_id,
                    'reported_by' => $issue->reportedBy->full_name ?? 'Anonymous',
                    'assigned_worker_id' => $assignedWorker ? $assignedWorker->worker_id : null,
                    'assigned_worker_name' => $assignedWorker && $assignedWorker->worker ? $assignedWorker->worker->full_name : 'Unassigned',
                    'upvote_count' => $issue->upvote_count,
                    'time_ago' => $issue->created_at->diffForHumans()
                ];
            });

        return response()->json($issues);
    }

    /**
     * Fetch active workers list.
     */
    public function getWorkers(): JsonResponse
    {
        $workers = User::where('role_id', 2)
            ->where('is_active', true)
            ->orderBy('full_name', 'asc')
            ->get(['id', 'full_name']);

        return response()->json($workers);
    }

    /**
     * Assign a complaint to a specific worker.
     */
    public function assignWorker(Request $request): JsonResponse
    {
        $request->validate([
            'issue_id' => 'required|exists:issues,id',
            'worker_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $issue = Issue::findOrFail($request->issue_id);
        $oldStatusId = $issue->status_id;

        // Auto transition status to Acknowledged (ID 2)
        $issue->status_id = 2;
        $issue->save();

        // Create or update assignment record
        \App\Models\IssueAssignment::updateOrCreate(
            ['issue_id' => $issue->id],
            [
                'worker_id' => $request->worker_id,
                'assigned_by' => \Illuminate\Support\Facades\Auth::id(),
                'notes' => $request->notes,
            ]
        );

        // Log status change history
        \App\Models\StatusHistory::create([
            'issue_id' => $issue->id,
            'old_status_id' => $oldStatusId,
            'new_status_id' => 2,
            'changed_by' => \Illuminate\Support\Facades\Auth::id(),
            'remark' => 'Worker assigned: ' . ($request->notes ?: 'No instructions provided.'),
        ]);

        // Send system notification
        \App\Models\Notification::create([
            'user_id' => $request->worker_id,
            'issue_id' => $issue->id,
            'message' => 'You have been assigned a new issue: "' . $issue->title . '".',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Worker assigned successfully!'
        ]);
    }
}
