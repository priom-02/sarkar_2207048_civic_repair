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

        // 3. Calculate 7-Day Trends
        $trends = [];
        $infraIds = [1, 5, 6, 7, 9, 11]; // Road, street light, electricity, etc.
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->endOfDay();
            
            $dayLabel = $date->format('D'); // e.g. Mon, Tue, etc.
            
            $infraCount = Issue::whereBetween('created_at', [$start, $end])
                ->whereIn('category_id', $infraIds)
                ->count();

            $sanitationCount = Issue::whereBetween('created_at', [$start, $end])
                ->whereNotIn('category_id', $infraIds)
                ->count();
                
            $trends[] = [
                'label' => $dayLabel,
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
            'icon' => 'nullable|string|max:10',
        ]);

        $category = IssueCategory::create([
            'category_name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?: '📋',
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
        $issues = Issue::with([
            'category', 
            'area', 
            'status', 
            'reportedBy', 
            'assignments', 
            'assignments.worker', 
            'media', 
            'media.uploadedBy', 
            'statusHistory', 
            'statusHistory.changedBy', 
            'statusHistory.oldStatus', 
            'statusHistory.newStatus'
        ])
            ->where('status_id', '!=', 6) // Exclude Closed issues
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($issue) {
                $assignedWorker = $issue->assignments->first();
                return [
                    'id' => $issue->id,
                    'title' => $issue->title,
                    'description' => $issue->description,
                    'category_name' => $issue->category->category_name ?? 'Other',
                    'category_icon' => $issue->category->icon ?? '📋',
                    'area_name' => $issue->area->area_name ?? 'N/A',
                    'status_name' => $issue->status->status_name ?? 'Pending',
                    'status_id' => $issue->status_id,
                    'reported_by' => $issue->reportedBy->full_name ?? 'Anonymous',
                    'assigned_worker_id' => $assignedWorker ? $assignedWorker->worker_id : null,
                    'assigned_worker_name' => $assignedWorker && $assignedWorker->worker ? $assignedWorker->worker->full_name : 'Unassigned',
                    'upvote_count' => $issue->upvote_count,
                    'time_ago' => $issue->created_at->diffForHumans(),
                    'latitude' => $issue->latitude,
                    'longitude' => $issue->longitude,
                    'media' => $issue->media->map(function ($m) {
                        return [
                            'url' => $m->file_url,
                            'uploaded_by_role' => $m->uploadedBy->role_id ?? null,
                        ];
                    }),
                    'history' => $issue->statusHistory->sortBy('created_at')->map(function ($h) {
                        return [
                            'time' => $h->created_at->diffForHumans(),
                            'user_name' => $h->changedBy->full_name ?? 'System',
                            'old_status' => $h->oldStatus->status_name ?? 'Initial',
                            'new_status' => $h->newStatus->status_name ?? 'Pending',
                            'remark' => $h->remark,
                        ];
                    })->values(),
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

    /**
     * Fetch all users (citizens and workers) for user management.
     */
    public function getUsers(): JsonResponse
    {
        $users = User::with('role')
            ->where('id', '!=', \Illuminate\Support\Facades\Auth::id())
            ->orderBy('role_id', 'desc')
            ->orderBy('full_name', 'asc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? 'N/A',
                    'role_name' => $user->role->role_name ?? 'Citizen',
                    'is_active' => (bool) $user->is_active,
                    'nid_number' => $user->nid_number,
                    'nid_front_photo' => $user->nid_front_photo,
                    'nid_back_photo' => $user->nid_back_photo,
                    'nid_verified' => $user->nid_verified ?? 'pending',
                ];
            });

        return response()->json($users);
    }

    /**
     * Verify or reject citizen NID registration.
     */
    public function verifyNid(Request $request, $id): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:verify,reject',
        ]);

        $user = User::findOrFail($id);
        
        if ($user->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'NID verification is only applicable for Citizens.'
            ], 422);
        }

        $user->nid_verified = $request->action === 'verify' ? 'verified' : 'rejected';
        $user->save();

        $statusStr = $user->nid_verified === 'verified' ? 'verified' : 'rejected';
        return response()->json([
            'success' => true,
            'message' => "Citizen NID verification status updated to {$statusStr}."
        ]);
    }

    /**
     * Toggle user is_active status.
     */
    public function toggleUserActive($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "User account has been successfully {$statusStr}."
        ]);
    }


}
