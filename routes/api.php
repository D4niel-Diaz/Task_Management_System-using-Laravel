<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskFileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.basic')->group(function () {
    Route::apiResource('tasks', TaskController::class)->names('api.tasks');

    Route::post('/tasks/{task}/files', [TaskFileController::class, 'store'])->name('api.tasks.files.store');
    Route::get('/tasks/{task}/files/{file}', [TaskFileController::class, 'download'])->name('api.tasks.files.download');
    Route::delete('/tasks/{task}/files/{file}', [TaskFileController::class, 'destroy'])->name('api.tasks.files.destroy');
});
