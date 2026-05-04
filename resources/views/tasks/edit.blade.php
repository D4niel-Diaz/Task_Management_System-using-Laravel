@extends('layouts.app')

@section('title', 'Edit Task')
@section('page-title', 'Edit Task')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 fw-bold">Edit Task</h5>
                <small class="text-muted">Task #{{ $task->id }}</small>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf @method('PUT')

                    {{-- Title --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Task Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $task->title) }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        {{-- Status --}}
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending"     {{ old('status', $task->status) === 'pending'     ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed"   {{ old('status', $task->status) === 'completed'   ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low"    {{ old('priority', $task->priority) === 'low'    ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high"   {{ old('priority', $task->priority) === 'high'   ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date"
                                   class="form-control @error('due_date') is-invalid @enderror"
                                   value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Assign To --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Assign To</label>
                        <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                            <option value="">— Unassigned —</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Changing the assignee will send a new email notification.</div>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check2 me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
