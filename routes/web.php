<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerController;

// Welcome Page
Route::get('/', function () {
    // 1. Calculate Live Resolution Rate
    $totalIssues = \App\Models\Issue::count();
    $resolvedIssues = \App\Models\Issue::whereIn('status_id', [5, 6])->count();
    $resolutionRate = $totalIssues > 0 ? round(($resolvedIssues / $totalIssues) * 100, 1) : 100;

    // 2. Calculate Average Response Time (Pending to Acknowledged status transition)
    $avgHours = \App\Models\StatusHistory::join('issues', 'status_history.issue_id', '=', 'issues.id')
        ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, issues.created_at, status_history.created_at)) as avg_hours')
        ->value('avg_hours');
    $avgResponseTime = $avgHours ? (round($avgHours, 1) . 'h') : '< 2.4h';

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

    return view('home', compact('resolutionRate', 'avgResponseTime', 'recentActivities'));
});

// Citizen Portal Routes (protected by auth, role:citizen, and active check)
Route::middleware(['auth', 'role:citizen', 'active'])->group(function () {
    Route::get('/citizen', [CitizenController::class, 'index'])->name('citizen.index');
    Route::get('/citizen/api/issues', [CitizenController::class, 'getIssues'])->name('citizen.api.issues');
    Route::post('/citizen/api/issues', [CitizenController::class, 'storeIssue'])
        ->middleware('sanitize')
        ->name('citizen.api.issues.store');
    Route::post('/citizen/api/issues/{id}/vote', [CitizenController::class, 'toggleVote'])
        ->middleware('throttle:10,1')
        ->name('citizen.api.issues.vote');
    Route::get('/citizen/api/issues/{id}', [CitizenController::class, 'getIssueDetails'])->name('citizen.api.issues.details');
    Route::post('/citizen/api/issues/{id}/comments', [CitizenController::class, 'storeComment'])
        ->middleware(['sanitize', 'throttle:5,1'])
        ->name('citizen.api.issues.comments.store');
    Route::post('/citizen/api/issues/{id}/feedback', [CitizenController::class, 'submitFeedback'])
        ->middleware('sanitize')
        ->name('citizen.api.issues.feedback');
});

// Worker Dashboard Routes (protected by auth, role:worker, and active check)
Route::middleware(['auth', 'role:worker', 'active'])->group(function () {
    Route::get('/worker/dashboard', [WorkerController::class, 'dashboard'])->name('worker.dashboard');
    Route::get('/worker/api/assignments', [WorkerController::class, 'getAssignments'])->name('worker.api.assignments');
    Route::post('/worker/api/assignments/{assignment_id}/status', [WorkerController::class, 'updateAssignmentStatus'])
        ->middleware(['assignment.owner', 'sanitize'])
        ->name('worker.api.assignments.status');
});

// Admin Dashboard Routes (protected by auth, role:admin, and active check)
Route::middleware(['auth', 'role:admin', 'active'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/api/analytics', [AdminController::class, 'getAnalytics'])->name('admin.api.analytics');
    Route::get('/admin/api/categories', [AdminController::class, 'getCategories'])->name('admin.api.categories');
    Route::post('/admin/api/categories', [AdminController::class, 'storeCategory'])
        ->middleware('sanitize')
        ->name('admin.api.categories.store');
    Route::get('/admin/api/issues', [AdminController::class, 'getIssues'])->name('admin.api.issues');
    Route::get('/admin/api/workers', [AdminController::class, 'getWorkers'])->name('admin.api.workers');
    Route::post('/admin/api/assignments', [AdminController::class, 'assignWorker'])
        ->middleware('sanitize')
        ->name('admin.api.assignments');
    Route::get('/admin/api/users', [AdminController::class, 'getUsers'])->name('admin.api.users');
    Route::post('/admin/api/users/{id}/toggle-active', [AdminController::class, 'toggleUserActive'])->name('admin.api.users.toggle');
    Route::post('/admin/api/users/{id}/verify-nid', [AdminController::class, 'verifyNid'])->name('admin.api.users.verify-nid');
    
    // Areas Management Routes
    Route::get('/admin/api/areas', [AdminController::class, 'getAreas'])->name('admin.api.areas');
    Route::post('/admin/api/areas', [AdminController::class, 'storeArea'])->middleware('sanitize')->name('admin.api.areas.store');
    Route::delete('/admin/api/areas/{id}', [AdminController::class, 'deleteArea'])->name('admin.api.areas.delete');
});

require __DIR__.'/auth.php';
