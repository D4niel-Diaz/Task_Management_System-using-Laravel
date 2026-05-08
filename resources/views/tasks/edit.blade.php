@extends('layouts.app')

@section('title', 'Edit Task')
@section('page-title', 'Edit Task')
@section('page-kicker', 'Admin workspace')

@section('content')
<div class="row justify-content-center">
    <div class="col-xxl-9 col-xl-10">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary btn-icon" aria-label="Back to task">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="h4 fw-bold mb-1">Edit Task</h2>
                <div class="text-muted">Task #{{ $task->id }} updated {{ $task->updated_at->diffForHumans() }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                            <input id="title" type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $task->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="5"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input id="due_date" type="date" name="due_date"
                                   class="form-control @error('due_date') is-invalid @enderror"
                                   value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="assigned_to" class="form-label">Assign To</label>
                            <select id="assigned_to" name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text"><i class="bi bi-envelope me-1"></i>Changing the assignee will send a new email notification.</div>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-5 pt-4 border-top">
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
