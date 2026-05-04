<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Show all users with their task counts (admin only).
     */
    public function index()
    {
        $users = User::withCount([
            'assignedTasks as assigned_tasks_count',
            'assignedTasks as pending_count' => function ($q) {
                $q->where('status', 'pending');
            },
            'assignedTasks as in_progress_count' => function ($q) {
                $q->where('status', 'in_progress');
            },
            'assignedTasks as completed_count' => function ($q) {
                $q->where('status', 'completed');
            },
        ])->orderBy('name')->get();

        return view('users.index', compact('users'));
    }
}
