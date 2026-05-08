<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $query = Task::with(['assignedTo', 'createdBy', 'files']);

        // Admin sees all tasks; regular users see only their assigned tasks
        if ($user->role !== 'admin') {
            $query->where('assigned_to', $user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->orderByRaw('due_date IS NULL, due_date ASC')->paginate(10)->withQueryString();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,completed',
            'priority'    => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date|after_or_equal:today',
        ]);

        $validated['created_by'] = Auth::id();

        $task = Task::create($validated);

        // Send email notification to assigned user
        if ($task->assigned_to) {
            $assignedUser = User::find($task->assigned_to);
            if ($assignedUser) {
                try {
                    $assignedUser->notify(new TaskAssigned($task));
                } catch (\Exception $e) {
                    // Log but don't fail if mail is down
                    logger()->warning('TaskAssigned notification failed: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task "' . $task->title . '" created successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();

        // Non-admins can only view their own tasks
        // Use loose comparison: assigned_to is an int in DB, $user->id is int - safe.
        // But when task is unassigned (null), null !== int is true, so also handle that.
        if ($user->role !== 'admin' && (int) $task->assigned_to !== (int) $user->id) {
            abort(403, 'You do not have permission to view this task.');
        }

        $task->load(['assignedTo', 'createdBy', 'files.uploadedBy']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $users = User::orderBy('name')->get();
        $task->load('assignedTo');
        return view('tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin can update everything
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'required|in:pending,in_progress,completed',
                'priority'    => 'required|in:low,medium,high',
                'assigned_to' => 'nullable|exists:users,id',
                'due_date'    => 'nullable|date',
            ]);

            $previousAssignee = $task->assigned_to;
            $previousStatus = $task->status;
            $task->update($validated);

            // Notify new assignee if changed
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

            $this->sendTaskCompletedNotification($task->fresh(['assignedTo', 'createdBy']), $user, $previousStatus);
        } else {
            // Regular users can only update the status
            // Cast both sides to int to avoid type-mismatch false positives
            if ((int) $task->assigned_to !== (int) $user->id) {
                abort(403, 'You do not have permission to update this task.');
            }

            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,completed',
            ]);

            $previousStatus = $task->status;
            $task->update($validated);
            $this->sendTaskCompletedNotification($task->fresh(['assignedTo', 'createdBy']), $user, $previousStatus);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    private function sendTaskCompletedNotification(Task $task, User $actor, string $previousStatus): void
    {
        if ($previousStatus === 'completed' || $task->status !== 'completed') {
            return;
        }

        $recipients = collect([$task->createdBy, $task->assignedTo])
            ->filter()
            ->unique('id')
            ->reject(fn (User $recipient) => (int) $recipient->id === (int) $actor->id);

        foreach ($recipients as $recipient) {
            try {
                $recipient->notify(new TaskCompletedNotification($task, $actor));
            } catch (\Exception $e) {
                logger()->warning('TaskCompleted notification failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        $title = $task->title;
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', "Task \"{$title}\" deleted.");
    }
}
