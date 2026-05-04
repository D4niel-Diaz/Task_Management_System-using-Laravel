<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * GET /api/tasks
     * List all tasks (admin) or assigned tasks (user).
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Task::with(['assignedTo:id,name,email', 'createdBy:id,name,email']);

        if ($user->role !== 'admin') {
            $query->where('assigned_to', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderByRaw('due_date IS NULL, due_date ASC')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $tasks,
        ]);
    }

    /**
     * POST /api/tasks
     * Create a new task (admin only).
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only admins can create tasks.',
            ], 403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,completed',
            'priority'    => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
        ]);

        $validated['created_by'] = $user->id;
        $task = Task::create($validated);

        if ($task->assigned_to) {
            $assignedUser = User::find($task->assigned_to);
            if ($assignedUser) {
                try {
                    $assignedUser->notify(new TaskAssigned($task));
                } catch (\Exception $e) {
                    logger()->warning('TaskAssigned notification failed: ' . $e->getMessage());
                }
            }
        }

        $task->load(['assignedTo:id,name,email', 'createdBy:id,name,email']);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data'    => $task,
        ], 201);
    }

    /**
     * GET /api/tasks/{task}
     * Show a single task.
     */
    public function show(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role !== 'admin' && (int) $task->assigned_to !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        $task->load(['assignedTo:id,name,email', 'createdBy:id,name,email', 'files']);

        return response()->json([
            'success' => true,
            'data'    => $task,
        ]);
    }

    /**
     * PUT/PATCH /api/tasks/{task}
     * Update a task.
     */
    public function update(Request $request, Task $task)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            $validated = $request->validate([
                'title'       => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'sometimes|required|in:pending,in_progress,completed',
                'priority'    => 'sometimes|required|in:low,medium,high',
                'assigned_to' => 'nullable|exists:users,id',
                'due_date'    => 'nullable|date',
            ]);

            $previousAssignee = $task->assigned_to;
            $task->update($validated);

            if (
                isset($validated['assigned_to']) &&
                (int) $validated['assigned_to'] !== (int) $previousAssignee &&
                $validated['assigned_to']
            ) {
                $assignedUser = User::find($validated['assigned_to']);
                if ($assignedUser) {
                    try {
                        $assignedUser->notify(new TaskAssigned($task));
                    } catch (\Exception $e) {
                        logger()->warning('TaskAssigned notification failed: ' . $e->getMessage());
                    }
                }
            }
        } else {
            if ((int) $task->assigned_to !== (int) $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.',
                ], 403);
            }

            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,completed',
            ]);

            $task->update($validated);
        }

        $task->refresh()->load(['assignedTo:id,name,email', 'createdBy:id,name,email']);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data'    => $task,
        ]);
    }

    /**
     * DELETE /api/tasks/{task}
     * Delete a task (admin only).
     */
    public function destroy(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only admins can delete tasks.',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }
}
