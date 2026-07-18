<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueCategory;
use App\Models\Area;
use App\Models\IssueVote;
use App\Models\IssueStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CitizenController extends Controller
{
    /**
     * Display the citizen portal home page.
     */
    public function index(): View
    {
        $categories = IssueCategory::all();
        $areas = Area::all();

        // 1. Calculate Live Resolution Rate
        $totalIssues = Issue::count();
        $resolvedIssues = Issue::whereIn('status_id', [5, 6])->count();
        $resolutionRate = $totalIssues > 0 ? round(($resolvedIssues / $totalIssues) * 100, 1) : 0;

        // 2. Calculate Average Response Time
        $avgHours = \App\Models\StatusHistory::join('issues', 'status_history.issue_id', '=', 'issues.id')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, issues.created_at, status_history.created_at)) as avg_hours')
            ->value('avg_hours');
        $avgResponseTime = $avgHours ? round($avgHours, 1) . 'h' : '2.4h';

        // 3. Fetch Recent Activities Logs (Latest 3)
        $recentActivities = \App\Models\StatusHistory::with(['issue', 'issue.area', 'newStatus'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($history) {
                $timeAgo = $history->created_at->diffForHumans();
                $issueTitle = $history->issue->title ?? 'Civic issue';
                $areaName = $history->issue->area->area_name ?? 'Dhaka';
                $statusName = $history->newStatus->status_name ?? 'Pending';

                if ($history->new_status_id == 1) {
                    $text = "📢 New issue reported: \"{$issueTitle}\" in {$areaName}";
                } elseif ($history->new_status_id == 2 || $history->new_status_id == 3) {
                    $text = "🔧 Worker assigned to \"{$issueTitle}\" in {$areaName}";
                } elseif ($history->new_status_id == 5 || $history->new_status_id == 6) {
                    $text = "✅ Repair completed for \"{$issueTitle}\" in {$areaName}";
                } else {
                    $text = "🔔 Status updated to {$statusName} for \"{$issueTitle}\" in {$areaName}";
                }

                return [
                    'time' => $timeAgo,
                    'text' => $text
                ];
            });

        return view('citizen.index', compact('categories', 'areas', 'resolutionRate', 'avgResponseTime', 'recentActivities'));
    }

    /**
     * Get issues with filters and search query.
     */
    public function getIssues(Request $request): JsonResponse
    {
        $query = Issue::with(['category', 'area', 'status', 'reportedBy', 'media'])
            ->withCount('comments');

        // Filter by category_id if provided and not "all"
        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by authenticated user's own reports
        if ($request->has('my_reports') && $request->my_reports == 1) {
            $query->where('reported_by', Auth::id());
        }

        // Search query
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $userId = Auth::id();
        $issues = $query->orderBy('created_at', 'desc')->get()->map(function ($issue) use ($userId) {
            $hasVoted = $userId ? IssueVote::where('issue_id', $issue->id)->where('user_id', $userId)->exists() : false;

            return [
                'id' => $issue->id,
                'title' => $issue->title,
                'description' => $issue->description,
                'category_name' => $issue->category->category_name ?? 'Other',
                'area_name' => $issue->area->area_name ?? 'N/A',
                'status_name' => $issue->status->status_name ?? 'Pending',
                'status_id' => $issue->status_id,
                'status_class' => strtolower(str_replace(' ', '', $issue->status->status_name ?? 'pending')),
                'reported_by' => $issue->reportedBy->full_name ?? 'Anonymous',
                'reported_by_initial' => strtoupper(substr($issue->reportedBy->full_name ?? 'A', 0, 1)),
                'votes' => $issue->upvote_count,
                'comments' => $issue->comments_count,
                'time_ago' => $issue->created_at->diffForHumans(),
                'voted' => $hasVoted,
                'media' => $issue->media->pluck('file_url')->toArray(),
            ];
        });

        // Compute summary metrics
        $stats = [
            'total' => Issue::count(),
            'resolved' => Issue::whereHas('status', function ($q) {
                $q->where('status_name', 'Resolved');
            })->count(),
            'inprogress' => Issue::whereHas('status', function ($q) {
                $q->where('status_name', 'In Progress');
            })->count(),
            'total_votes' => IssueVote::count(),
        ];

        // Retrieve active citizens for leaderboard
        $leaders = \App\Models\User::where('role_id', 1)
            ->withCount(['reportedIssues', 'votes'])
            ->orderBy('votes_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $user->full_name,
                    'issues' => $user->reported_issues_count,
                    'votes' => $user->votes_count
                ];
            });

        return response()->json([
            'issues' => $issues,
            'stats' => $stats,
            'leaders' => $leaders
        ]);
    }

    /**
     * Store a new civic issue report.
     */
    public function storeIssue(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'category_id' => 'required|exists:issue_categories,id',
            'area_name' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $areaName = ucwords(strtolower(trim($request->area_name)));
        $area = \App\Models\Area::firstOrCreate(
            ['area_name' => $areaName],
            [
                'division' => 'N/A',
                'district' => 'N/A',
                'upazila' => 'N/A',
                'union_parishad' => null,
                'city' => 'N/A',
                'latitude_center' => 23.8103,
                'longitude_center' => 90.4125,
            ]
        );

        $pendingStatus = IssueStatus::where('status_name', 'Pending')->first();
        $statusId = $pendingStatus ? $pendingStatus->id : 1;

        $issue = Issue::create([
            'title' => $request->title,
            'description' => $request->description,
            'reported_by' => Auth::id(),
            'category_id' => $request->category_id,
            'area_id' => $area->id,
            'status_id' => $statusId,
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'upvote_count' => 0,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('issues', 'public');
                $url = asset('storage/' . $path);
                
                \App\Models\IssueMedia::create([
                    'issue_id' => $issue->id,
                    'file_url' => $url,
                    'media_type' => 'image',
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Issue reported successfully!',
            'issue' => $issue
        ]);
    }

    /**
     * Toggle upvote status of an issue.
     */
    public function toggleVote(Request $request, $id): JsonResponse
    {
        $issue = Issue::findOrFail($id);
        $userId = Auth::id();

        $existingVote = IssueVote::where('issue_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            $existingVote->delete();
            $issue->upvote_count = max(0, $issue->upvote_count - 1);
            $issue->save();
            $voted = false;
        } else {
            IssueVote::create([
                'issue_id' => $id,
                'user_id' => $userId,
            ]);
            $issue->upvote_count += 1;
            $issue->save();
            $voted = true;
        }

        return response()->json([
            'success' => true,
            'votes' => $issue->upvote_count,
            'voted' => $voted
        ]);
    }

    /**
     * Get details of a single issue, including status history, media, and comments.
     */
    public function getIssueDetails($id): JsonResponse
    {
        $userId = Auth::id();
        $issue = Issue::with([
            'category', 
            'area', 
            'status', 
            'reportedBy', 
            'media',
            'statusHistory' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'statusHistory.oldStatus',
            'statusHistory.newStatus',
            'statusHistory.changedBy',
            'comments' => function ($q) {
                $q->where('is_internal', false)->orderBy('created_at', 'desc');
            },
            'comments.user'
        ])->findOrFail($id);

        $hasVoted = $userId ? IssueVote::where('issue_id', $issue->id)->where('user_id', $userId)->exists() : false;

        $mappedHistory = $issue->statusHistory->map(function ($history) {
            return [
                'id' => $history->id,
                'old_status' => $history->oldStatus->status_name ?? 'Reported',
                'new_status' => $history->newStatus->status_name ?? 'Pending',
                'changed_by' => $history->changedBy->full_name ?? 'System',
                'remark' => $history->remark,
                'time_ago' => $history->created_at->diffForHumans(),
                'date_formatted' => $history->created_at->format('M d, Y h:i A')
            ];
        });

        $mappedComments = $issue->comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'body' => $comment->body,
                'author_name' => $comment->user->full_name ?? 'Anonymous User',
                'author_initial' => strtoupper(substr($comment->user->full_name ?? 'A', 0, 1)),
                'time_ago' => $comment->created_at->diffForHumans(),
                'date_formatted' => $comment->created_at->format('M d, Y h:i A')
            ];
        });

        $details = [
            'id' => $issue->id,
            'title' => $issue->title,
            'description' => $issue->description,
            'category_name' => $issue->category->category_name ?? 'Other',
            'area_name' => $issue->area->area_name ?? 'N/A',
            'status_name' => $issue->status->status_name ?? 'Pending',
            'status_class' => strtolower(str_replace(' ', '', $issue->status->status_name ?? 'pending')),
            'reported_by' => $issue->reportedBy->full_name ?? 'Anonymous User',
            'reported_by_initial' => strtoupper(substr($issue->reportedBy->full_name ?? 'A', 0, 1)),
            'votes' => $issue->upvote_count,
            'voted' => $hasVoted,
            'media' => $issue->media->pluck('file_url')->toArray(),
            'history' => $mappedHistory,
            'comments' => $mappedComments,
            'time_ago' => $issue->created_at->diffForHumans(),
            'latitude' => $issue->latitude,
            'longitude' => $issue->longitude,
            'status_id' => $issue->status_id,
            'is_own_report' => ($issue->reported_by === $userId),
        ];

        return response()->json($details);
    }

    /**
     * Store a comment on a specific issue.
     */
    public function storeComment(Request $request, $id): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $issue = Issue::findOrFail($id);

        $comment = \App\Models\IssueComment::create([
            'issue_id' => $issue->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'is_internal' => false
        ]);

        $responseComment = [
            'id' => $comment->id,
            'body' => $comment->body,
            'author_name' => Auth::user()->full_name,
            'author_initial' => strtoupper(substr(Auth::user()->full_name, 0, 1)),
            'time_ago' => $comment->created_at->diffForHumans(),
            'date_formatted' => $comment->created_at->format('M d, Y h:i A')
        ];

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => $responseComment
        ]);
    }

    /**
     * Submit feedback and close or reopen the issue.
     */
    public function submitFeedback(Request $request, $id): JsonResponse
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'action' => 'required|string|in:satisfied,reopen',
        ]);

        $issue = Issue::findOrFail($id);

        // Security check: Only the citizen who reported the issue can review it
        if ($issue->reported_by !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Only resolved issues can be reviewed
        if ($issue->status_id !== 5) {
            return response()->json([
                'success' => false,
                'message' => 'Only resolved complaints can be reviewed.'
            ], 422);
        }

        $oldStatusId = $issue->status_id;
        
        // Save feedback
        \App\Models\IssueFeedback::create([
            'issue_id' => $issue->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'submitted_by' => Auth::id(),
        ]);

        if ($request->action === 'satisfied') {
            // Close the ticket (status 6)
            $newStatusId = 6;
            $remark = "Citizen is satisfied. Rating: {$request->rating}/5. " . ($request->comment ?: 'No feedback comments provided.');
        } else {
            // Re-open ticket back to In Progress (status 3)
            $newStatusId = 3;
            $remark = "Citizen re-opened complaint. Rating: {$request->rating}/5. Reopen Reason: " . ($request->comment ?: 'No comments.');
        }

        $issue->status_id = $newStatusId;
        $issue->save();

        // Write status history log
        \App\Models\StatusHistory::create([
            'issue_id' => $issue->id,
            'old_status_id' => $oldStatusId,
            'new_status_id' => $newStatusId,
            'changed_by' => Auth::id(),
            'remark' => $remark,
        ]);

        // Send notification to worker if reopen and worker is assigned
        $assignment = $issue->assignments->first();
        if ($newStatusId === 3 && $assignment) {
            \App\Models\Notification::create([
                'user_id' => $assignment->worker_id,
                'issue_id' => $issue->id,
                'message' => 'A resolved issue has been re-opened by the citizen: "' . $issue->title . '". Please review feedback.',
                'is_read' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $newStatusId === 6 ? 'Feedback submitted! Ticket has been closed successfully.' : 'Complaint has been re-opened for further work.'
        ]);
    }
}
