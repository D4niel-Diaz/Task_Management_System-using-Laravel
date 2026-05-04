<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Root URL redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest only routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes (any authenticated user)
    Route::get('/profile',              [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',              [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo',       [ProfileController::class, 'uploadPhoto'])->name('profile.photo');
    Route::delete('/profile/photo',     [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Task list (admin sees all, users see assigned)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    // Admin-only task routes (create, store, edit, destroy)
    Route::middleware('is_admin')->group(function () {
        Route::get('/tasks/create',       [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks',             [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}/edit',  [TaskController::class, 'edit'])->name('tasks.edit');
        Route::delete('/tasks/{task}',    [TaskController::class, 'destroy'])->name('tasks.destroy');

        // User management (admin only)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    // Task show and update (both admin and assigned users)
    Route::get('/tasks/{task}',  [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{task}',  [TaskController::class, 'update'])->name('tasks.update');

    // File routes (upload, download, delete)
    Route::post('/tasks/{task}/upload',              [FileController::class, 'upload'])->name('tasks.files.upload');
    Route::get('/tasks/{task}/files/{file}',         [FileController::class, 'download'])->name('tasks.files.download');
    Route::delete('/tasks/{task}/files/{file}',      [FileController::class, 'destroy'])->name('tasks.files.destroy');
});
