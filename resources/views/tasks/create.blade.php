@extends('layouts.app')

@section('title', 'Create Task')
@section('page-title', 'Create New Task')

@section('content')

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body p-4">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                <input type="text"
                       name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Assign To</label>
                <select name="assigned_to"
                        class="form-select @error('assigned_to') is-invalid @enderror">
                    <option value="">— Unassigned —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status"
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Due Date</label>
                <input type="date"
                       name="due_date"
                       class="form-control @error('due_date') is-invalid @enderror"
                       value="{{ old('due_date') }}">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Task
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection