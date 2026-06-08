<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\Auth\AuthController;

// Welcome Page
Route::get('/', function () {
    return view('home');
});

// Authentication Routes (public access - guest middleware removed to allow auth users to see login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Citizen Portal Routes
Route::middleware(['auth', 'role:citizen'])->group(function () {
    Route::get('/citizen', [CitizenController::class, 'index'])->name('citizen.index');
});

// Worker Dashboard Routes
Route::middleware(['auth', 'role:worker'])->group(function () {
    Route::get('/worker/dashboard', [WorkerController::class, 'dashboard'])->name('worker.dashboard');
});

// Admin Dashboard Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
