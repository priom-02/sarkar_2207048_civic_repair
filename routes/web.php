<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerController;

// Welcome Page
Route::get('/', function () {
    return view('home');
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
    
    // Areas Management Routes
    Route::get('/admin/api/areas', [AdminController::class, 'getAreas'])->name('admin.api.areas');
    Route::post('/admin/api/areas', [AdminController::class, 'storeArea'])->middleware('sanitize')->name('admin.api.areas.store');
    Route::delete('/admin/api/areas/{id}', [AdminController::class, 'deleteArea'])->name('admin.api.areas.delete');
});

require __DIR__.'/auth.php';
