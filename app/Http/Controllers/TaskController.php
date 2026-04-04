<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Show all tasks
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            $tasks = Task::with(['assignedUser', 'creator'])->latest()->get();
        } else {
            $tasks = Task::with(['assignedUser', 'creator'])
                ->where('assigned_to', $user->id)
                ->latest()
                ->get();
        }
        return view('tasks.index', compact('tasks'));
    }
    // Show create form (Admin only)
    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }
    // Store new task (Admin only)
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);

        /** @var User $user */
        $user = Auth::user();
        Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date'    => $request->due_date,
            'status'      => $request->status,
            'created_by'  => $user->id,
        ]);
        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }
    // Show single task
    public function show(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        // Users can only view their own tasks
        if ($user->role !== 'admin' && $task->assigned_to !== $user->id) {
            return redirect()->route('tasks.index')
                ->with('error', 'Access denied.');
        }
        $task->load(['assignedUser', 'creator', 'files']);
        return view('tasks.show', compact('task'));
    }

    // Show edit form (Admin only)
    public function edit(Task $task)
    {
        $users = User::all();
        return view('tasks.edit', compact('task', 'users'));
    }
    // Update task
    public function update(Request $request, Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->role === 'admin') {
            $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'assigned_to' => 'nullable|exists:users,id',
                'due_date'    => 'nullable|date',
                'status'      => 'required|in:pending,in_progress,completed',
            ]);
            $task->update([
                'title'       => $request->title,
                'description' => $request->description,
                'assigned_to' => $request->assigned_to,
                'due_date'    => $request->due_date,
                'status'      => $request->status,
            ]);
        } else {
            // Users can only update status
            $request->validate([
                'status' => 'required|in:pending,in_progress,completed',
            ]);

            $task->update([
                'status' => $request->status,
            ]);
        }
        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task updated successfully.');
    }
    // Delete task (Admin only)
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}