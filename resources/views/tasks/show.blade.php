@extends('layouts.app')

@section('title', $task->title)
@section('page-title', 'Task Details')
@section('page-kicker', 'Task #' . $task->id)

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-icon" aria-label="Back to tasks">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="h4 fw-bold mb-1">{{ $task->title }}</h2>
            <div class="text-muted">Created by {{ $task->createdBy->name ?? 'Unknown' }} on {{ $task->created_at->format('M d, Y') }}</div>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
        <div class="d-flex gap-2">
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-2"></i>Delete
                </button>
            </form>
        </div>
    @endif
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <x-status-badge :status="$task->status" class="px-3 py-2" />
                    <x-priority-badge :priority="$task->priority" class="px-3 py-2" />
                    @if($task->due_date)
                        <span class="badge rounded-pill px-3 py-2 {{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-bg-danger' : 'text-bg-light border text-dark' }}">
                            <i class="bi bi-calendar3 me-1"></i>Due {{ $task->due_date->format('M d, Y') }}
                            @if($task->due_date->isPast() && $task->status !== 'completed')
                                <span class="ms-1">Overdue</span>
                            @endif
                        </span>
                    @endif
                </div>

                <div class="mb-4">
                    <h3 class="h6 fw-bold text-uppercase text-muted mb-2" style="font-size: .76rem;">Description</h3>
                    <p class="mb-0 lh-lg">{{ $task->description ?: 'No description provided.' }}</p>
                </div>

                <x-task-progress :status="$task->status" class="mb-4" />

                <div class="row g-3 pt-4 border-top">
                    <div class="col-md-4">
                        <div class="rounded-4 border p-3 h-100">
                            <div class="text-muted small fw-semibold mb-1">Assigned To</div>
                            @if($task->assignedTo)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar">{{ strtoupper(substr($task->assignedTo->name, 0, 1)) }}</span>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-truncate">{{ $task->assignedTo->name }}</div>
                                        <div class="text-muted small text-truncate">{{ $task->assignedTo->email }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="text-muted fst-italic">Unassigned</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="rounded-4 border p-3 h-100">
                            <div class="text-muted small fw-semibold mb-1">Created</div>
                            <div class="fw-bold">{{ $task->created_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $task->created_at->format('h:i A') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="rounded-4 border p-3 h-100">
                            <div class="text-muted small fw-semibold mb-1">Last Updated</div>
                            <div class="fw-bold">{{ $task->updated_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $task->updated_at->format('h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role !== 'admin' && (int) $task->assigned_to === (int) Auth::id())
            <div class="card">
                <div class="card-header">
                    <h3 class="h6 fw-bold mb-0"><i class="bi bi-arrow-clockwise text-primary me-2"></i>Update Status</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf @method('PUT')
                        <div class="row g-3 align-items-end">
                            <div class="col-md">
                                <label for="status" class="form-label">Current Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check2-circle me-2"></i>Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="h6 fw-bold mb-0"><i class="bi bi-paperclip text-primary me-2"></i>Attachments</h3>
                <span class="badge text-bg-light border rounded-pill">{{ $task->files->count() }}</span>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tasks.files.upload', $task) }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="upload-panel p-3 mb-3">
                        <label for="fileInput" class="form-label">Upload File</label>
                        <input type="file" name="file" id="fileInput"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar,.csv">
                        <div class="form-text">Max 10MB. Images, PDF, Word, Excel, ZIP, CSV, and text files are supported.</div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-cloud-upload me-2"></i>Upload
                    </button>
                </form>

                <hr class="my-4">

                @if($task->files->isEmpty())
                    <x-empty-state icon="bi-folder2-open" title="No files yet" message="Uploaded task files will appear here." class="py-4" />
                @else
                    <div class="vstack gap-2">
                        @foreach($task->files as $file)
                            <div class="rounded-4 border p-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="stat-icon soft-primary flex-shrink-0" style="width: 42px; height: 42px;">
                                        <i class="bi {{ $file->icon }}"></i>
                                    </div>
                                    <div class="min-w-0 flex-grow-1">
                                        <div class="fw-bold text-truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</div>
                                        <div class="text-muted small">
                                            {{ $file->formatted_size }} - {{ $file->created_at->format('M d, Y') }}
                                            @if($file->uploadedBy)
                                                - {{ $file->uploadedBy->name }}
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2 mt-3">
                                            <a href="{{ route('tasks.files.download', [$task, $file]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download me-1"></i>Download
                                            </a>
                                            @if(Auth::user()->role === 'admin' || $file->uploaded_by === Auth::id())
                                                <form method="POST" action="{{ route('tasks.files.destroy', [$task, $file]) }}" onsubmit="return confirm('Delete this file?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash me-1"></i>Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
