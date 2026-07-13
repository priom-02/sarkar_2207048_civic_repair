<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerController;

// Welcome Page
Route::get('/', function () {
    return view('home');
});

// Citizen Portal Routes (protected by auth and citizen role)
Route::middleware(['auth', 'role:citizen'])->group(function () {
    Route::get('/citizen', [CitizenController::class, 'index'])->name('citizen.index');
    Route::get('/citizen/api/issues', [CitizenController::class, 'getIssues'])->name('citizen.api.issues');
    Route::post('/citizen/api/issues', [CitizenController::class, 'storeIssue'])->name('citizen.api.issues.store');
    Route::post('/citizen/api/issues/{id}/vote', [CitizenController::class, 'toggleVote'])->name('citizen.api.issues.vote');
    Route::get('/citizen/api/issues/{id}', [CitizenController::class, 'getIssueDetails'])->name('citizen.api.issues.details');
    Route::post('/citizen/api/issues/{id}/comments', [CitizenController::class, 'storeComment'])->name('citizen.api.issues.comments.store');
});

// Worker Dashboard Routes (protected by auth and worker role)
Route::middleware(['auth', 'role:worker'])->group(function () {
    Route::get('/worker/dashboard', [WorkerController::class, 'dashboard'])->name('worker.dashboard');
    Route::get('/worker/api/assignments', [WorkerController::class, 'getAssignments'])->name('worker.api.assignments');
    Route::post('/worker/api/assignments/{assignment_id}/status', [WorkerController::class, 'updateAssignmentStatus'])->name('worker.api.assignments.status');
});

// Admin Dashboard Routes (protected by auth and admin role)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/api/analytics', [AdminController::class, 'getAnalytics'])->name('admin.api.analytics');
    Route::get('/admin/api/categories', [AdminController::class, 'getCategories'])->name('admin.api.categories');
    Route::post('/admin/api/categories', [AdminController::class, 'storeCategory'])->name('admin.api.categories.store');
    Route::get('/admin/api/issues', [AdminController::class, 'getIssues'])->name('admin.api.issues');
    Route::get('/admin/api/workers', [AdminController::class, 'getWorkers'])->name('admin.api.workers');
    Route::post('/admin/api/assignments', [AdminController::class, 'assignWorker'])->name('admin.api.assignments');
});

require __DIR__.'/auth.php';
