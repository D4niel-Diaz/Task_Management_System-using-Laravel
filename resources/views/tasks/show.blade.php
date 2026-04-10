@extends('layouts.app')

@section('title', 'View Task')
@section('page-title', 'Task Details')

@section('content')

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body p-4">

        <h5 class="fw-bold mb-1">{{ $task->title }}</h5>
        <p class="text-muted small mb-3">
            Created by {{ $task->creator->name ?? 'Unknown' }}
        </p>

        <hr>

        <div class="mb-3">
            <span class="fw-semibold">Status:</span>
            @if($task->status === 'completed')
                <span class="badge bg-success ms-1">Completed</span>
            @elseif($task->status === 'in_progress')
                <span class="badge bg-warning text-dark ms-1">In Progress</span>
            @else
                <span class="badge bg-secondary ms-1">Pending</span>
            @endif
        </div>

        <div class="mb-3">
            <span class="fw-semibold">Assigned To:</span>
            {{ $task->assignedUser->name ?? 'Unassigned' }}
        </div>

        <div class="mb-3">
            <span class="fw-semibold">Due Date:</span>
            {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
        </div>

        <div class="mb-4">
            <span class="fw-semibold">Description:</span>
            <p class="mt-1">{{ $task->description ?? 'No description provided.' }}</p>
        </div>

        <hr>

        {{-- Update Status Form (for assigned user) --}}
        @if(Auth::user()->role !== 'admin' && $task->assigned_to === Auth::id())
        <div class="mb-4">
            <h6 class="fw-semibold mb-3">Update Status</h6>
            <form method="POST" action="{{ route('tasks.update', $task->id) }}">
                @csrf
                @method('PUT')
                <div class="d-flex gap-2 align-items-center">
                    <select name="status" class="form-select" style="max-width: 200px;">
                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Update
                    </button>
                </div>
            </form>
        </div>
        @endif

        <hr>

        {{-- File Upload Form --}}
        @if(Auth::user()->role === 'admin' || $task->assigned_to === Auth::id())
        <div class="mb-4">
            <h6 class="fw-semibold mb-3">Upload File</h6>
            <form method="POST"
                  action="{{ route('tasks.files.upload', $task->id) }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="d-flex gap-2 align-items-center">
                    <input type="file"
                           name="file"
                           class="form-control @error('file') is-invalid @enderror"
                           accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx">
                    <button type="submit" class="btn btn-success btn-sm text-nowrap">
                        <i class="bi bi-upload me-1"></i> Upload
                    </button>
                </div>
                @error('file')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <small class="text-muted mt-1 d-block">
                    Allowed: PDF, JPG, PNG, DOCX, XLSX — Max 5MB
                </small>
            </form>
        </div>
        @endif

        {{-- File Attachments List --}}
        <div class="mb-4">
            <h6 class="fw-semibold mb-2">
                <i class="bi bi-paperclip me-1"></i> Attachments
                <span class="badge bg-secondary ms-1">{{ $task->files->count() }}</span>
            </h6>
            @forelse($task->files as $file)
                <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light rounded">
                    <i class="bi bi-file-earmark text-primary"></i>
                    <a href="{{ route('tasks.files.download', [$task->id, $file->id]) }}"
                       class="text-decoration-none">
                        {{ basename($file->file_path) }}
                    </a>
                    <span class="text-muted small ms-auto">
                        {{ $file->created_at->format('M d, Y') }}
                    </span>
                </div>
            @empty
                <p class="text-muted small">No files attached yet.</p>
            @endforelse
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Tasks
            </a>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Task
            </a>
            @endif
        </div>

    </div>
</div>

@endsection