@extends('layouts.app')

@section('title', 'Tasks')
@section('page-title', 'Task List')

@section('content')

{{-- Header Row --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold text-dark">
            @if(Auth::user()->role === 'admin') All Tasks @else My Tasks @endif
        </h5>
        <small class="text-muted">{{ $tasks->total() }} task(s) found</small>
    </div>
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm px-4">
        <i class="bi bi-plus-circle me-1"></i> New Task
    </a>
    @endif
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Search by title...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Priority</label>
                <select name="priority" class="form-select form-select-sm">
                    <option value="">All Priorities</option>
                    <option value="low"    {{ request('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high"   {{ request('priority') === 'high'   ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tasks Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($tasks->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No tasks found. @if(Auth::user()->role === 'admin') <a href="{{ route('tasks.create') }}">Create one?</a> @endif
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        @if(Auth::user()->role === 'admin')<th>Assigned To</th>@endif
                        <th>Due Date</th>
                        <th>Files</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $task->id }}</td>
                        <td>
                            <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-decoration-none text-dark">
                                {{ $task->title }}
                            </a>
                            @if($task->description)
                            <div class="text-muted small text-truncate" style="max-width:250px;">
                                {{ $task->description }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $task->status_color }} rounded-pill">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $task->priority_color }} rounded-pill">
                                {{ ucfirst($task->priority ?? 'medium') }}
                            </span>
                        </td>
                        @if(Auth::user()->role === 'admin')
                        <td>
                            @if($task->assignedTo)
                                <i class="bi bi-person-circle me-1 text-muted"></i>{{ $task->assignedTo->name }}
                            @else
                                <span class="text-muted fst-italic">Unassigned</span>
                            @endif
                        </td>
                        @endif
                        <td>
                            @if($task->due_date)
                                <span class="{{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-danger fw-semibold' : 'text-muted' }}">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $task->due_date->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">No due date</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-paperclip me-1"></i>{{ $task->files->count() }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-primary me-1" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(Auth::user()->role === 'admin')
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="d-inline"
                                  onsubmit="return confirm('Delete task \'{{ addslashes($task->title) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Pagination --}}
@if($tasks->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $tasks->links() }}
</div>
@endif

@endsection
