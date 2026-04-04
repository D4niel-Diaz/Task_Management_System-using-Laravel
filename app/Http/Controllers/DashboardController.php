<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            $totalTasks      = Task::count();
            $pendingTasks    = Task::where('status', 'pending')->count();
            $inProgressTasks = Task::where('status', 'in_progress')->count();
            $completedTasks  = Task::where('status', 'completed')->count();
            $usersWithTasks  = User::withCount('assignedTasks as assigned_tasks_count')->get();
        } else {
            $totalTasks      = Task::where('assigned_to', $user->id)->count();
            $pendingTasks    = Task::where('assigned_to', $user->id)->where('status', 'pending')->count();
            $inProgressTasks = Task::where('assigned_to', $user->id)->where('status', 'in_progress')->count();
            $completedTasks  = Task::where('assigned_to', $user->id)->where('status', 'completed')->count();
            $usersWithTasks  = collect();
        }

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'usersWithTasks'
        ));
    }
}