@extends('layouts.app')

@section('title', 'Tasks')
@section('page-title', Auth::user()->role === 'admin' ? 'All Tasks' : 'My Tasks')
@section('page-kicker', 'Task operations')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1">{{ Auth::user()->role === 'admin' ? 'All Tasks' : 'My Tasks' }}</h2>
        <div class="text-muted">{{ $tasks->total() }} task(s) match the current view</div>
    </div>
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Task
        </a>
    @endif
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label" for="task-search">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-4"><i class="bi bi-search text-muted"></i></span>
                    <input id="task-search" type="text" name="search" value="{{ request('search') }}"
                           class="form-control border-start-0" placeholder="Search by title">
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label class="form-label" for="task-status">Status</label>
                <select id="task-status" name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label class="form-label" for="task-priority">Priority</label>
                <select id="task-priority" name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-2"></i>Apply
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-icon" title="Clear filters" aria-label="Clear filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    @if($tasks->isEmpty())
        <x-empty-state
            icon="bi-clipboard2-check"
            title="No tasks found"
            message="Try adjusting the filters or create a new task to start tracking work."
            :action-href="Auth::user()->role === 'admin' ? route('tasks.create') : null"
            action-label="Create task"
        />
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Task</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Progress</th>
                        @if(Auth::user()->role === 'admin')<th>Assigned To</th>@endif
                        <th>Due Date</th>
                        <th>Files</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td class="ps-4" style="min-width: 260px;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="stat-icon soft-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi bi-check2-square"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('tasks.show', $task) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $task->title }}
                                        </a>
                                        <div class="text-muted small">Task #{{ $task->id }}</div>
                                        @if($task->description)
                                            <div class="text-muted small text-truncate mt-1" style="max-width: 320px;">
                                                {{ $task->description }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><x-status-badge :status="$task->status" /></td>
                            <td><x-priority-badge :priority="$task->priority" /></td>
                            <td style="min-width: 150px;"><x-task-progress :status="$task->status" /></td>
                            @if(Auth::user()->role === 'admin')
                                <td style="min-width: 180px;">
                                    @if($task->assignedTo)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar" style="width: 32px; height: 32px;">{{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}</span>
                                            <span class="fw-semibold">{{ $task->assignedTo->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Unassigned</span>
                                    @endif
                                </td>
                            @endif
                            <td style="min-width: 150px;">
                                @if($task->due_date)
                                    <span class="{{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-danger fw-bold' : 'text-muted' }}">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $task->due_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">No due date</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge text-bg-light border rounded-pill px-3">
                                    <i class="bi bi-paperclip me-1"></i>{{ $task->files->count() }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-primary btn-icon" title="View task" aria-label="View task">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Edit task" aria-label="Edit task">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline"
                                              onsubmit="return confirm('Delete task \'{{ addslashes($task->title) }}\'?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" title="Delete task" aria-label="Delete task">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if($tasks->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $tasks->links() }}
    </div>
@endif
@endsection
