@extends('layouts.app')

@section('title', 'Tasks')
@section('page-title', 'Task List')

@section('content')

{{-- Admin: Create Task Button --}}
@if(Auth::user()->role === 'admin')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-semibold">All Tasks</h5>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Create Task
    </a>
</div>
@else
<div class="mb-4">
    <h5 class="mb-0 fw-semibold">My Tasks</h5>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>
                        @if($task->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($task->status === 'in_progress')
                            <span class="badge bg-warning text-dark">In Progress</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                    <td>{{ $task->assignedUser->name ?? 'Unassigned' }}</td>
                    <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}</td>
                    <td>
                        <a href="{{ route('tasks.show', $task->id) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>

                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>

                        <form method="POST"
                              action="{{ route('tasks.destroy', $task->id) }}"
                              style="display:inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No tasks found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection