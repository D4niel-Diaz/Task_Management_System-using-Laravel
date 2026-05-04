@extends('layouts.app')

@section('title', $task->title)
@section('page-title', 'Task Details')

@section('content')

<div class="row g-4">

    {{-- Left Column: Task Details --}}
    <div class="col-lg-8">

        {{-- Back + Header --}}
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="flex-grow-1">
                <h5 class="mb-0 fw-bold">{{ $task->title }}</h5>
                <small class="text-muted">Task #{{ $task->id }} &bull; Created by {{ $task->createdBy->name ?? 'Unknown' }}</small>
            </div>
            @if(Auth::user()->role === 'admin')
            <div class="d-flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                      onsubmit="return confirm('Delete this task?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
            </div>
            @endif
        </div>

        {{-- Task Info Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                {{-- Badges --}}
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <span class="badge bg-{{ $task->status_color }} rounded-pill px-3 py-2">
                        <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle;"></i>
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                    <span class="badge bg-{{ $task->priority_color }} rounded-pill px-3 py-2">
                        Priority: {{ ucfirst($task->priority ?? 'medium') }}
                    </span>
                    @if($task->due_date)
                    <span class="badge rounded-pill px-3 py-2 {{ $task->due_date->isPast() && $task->status !== 'completed' ? 'bg-danger' : 'bg-light text-dark border' }}">
                        <i class="bi bi-calendar3 me-1"></i>Due {{ $task->due_date->format('M d, Y') }}
                        @if($task->due_date->isPast() && $task->status !== 'completed')
                            <span class="ms-1">(Overdue)</span>
                        @endif
                    </span>
                    @endif
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <h6 class="fw-semibold text-muted mb-2 text-uppercase" style="font-size:.75rem;letter-spacing:.05em;">Description</h6>
                    <p class="mb-0">{{ $task->description ?: 'No description provided.' }}</p>
                </div>

                <hr>

                {{-- Meta Info --}}
                <div class="row g-3 text-muted small">
                    <div class="col-sm-6">
                        <span class="fw-semibold text-dark d-block">Assigned To</span>
                        @if($task->assignedTo)
                            <i class="bi bi-person-circle me-1"></i>{{ $task->assignedTo->name }}
                            <br><span class="text-muted">{{ $task->assignedTo->email }}</span>
                        @else
                            <span class="fst-italic">Unassigned</span>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-semibold text-dark d-block">Created</span>
                        {{ $task->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="col-sm-6">
                        <span class="fw-semibold text-dark d-block">Last Updated</span>
                        {{ $task->updated_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Status (Regular User) --}}
        @if(Auth::user()->role !== 'admin' && $task->assigned_to === Auth::id())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-arrow-clockwise me-2 text-primary"></i>Update Status
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf @method('PUT')
                    <div class="d-flex gap-2 align-items-end">
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold">Current Status</label>
                            <select name="status" class="form-select">
                                <option value="pending"     {{ $task->status === 'pending'     ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed"   {{ $task->status === 'completed'   ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2 me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>

    {{-- Right Column: File Attachments --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-paperclip me-2 text-primary"></i>Attachments</span>
                <span class="badge bg-light text-dark border">{{ $task->files->count() }}</span>
            </div>
            <div class="card-body p-3">

                {{-- Upload Form --}}
                <form method="POST" action="{{ route('tasks.files.upload', $task) }}" enctype="multipart/form-data"
                      id="uploadForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Upload File</label>
                        <input type="file" name="file" id="fileInput"
                               class="form-control form-control-sm @error('file') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar,.csv">
                        <div class="form-text">Max 10MB. Supported: images, PDF, Word, Excel, ZIP, CSV</div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-cloud-upload me-1"></i>Upload
                    </button>
                </form>

                <hr>

                {{-- File List --}}
                @if($task->files->isEmpty())
                    <div class="text-center text-muted py-3 small">
                        <i class="bi bi-folder2-open fs-4 d-block mb-1"></i>No files uploaded yet.
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($task->files as $file)
                        <li class="list-group-item px-0 py-2">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi {{ $file->icon }} fs-5 text-primary mt-1 flex-shrink-0"></i>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold small text-truncate" title="{{ $file->original_name }}">
                                        {{ $file->original_name }}
                                    </div>
                                    <div class="text-muted" style="font-size:.72rem;">
                                        {{ $file->formatted_size }}
                                        &bull; {{ $file->created_at->format('M d, Y') }}
                                        @if($file->uploadedBy)
                                            &bull; {{ $file->uploadedBy->name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex gap-1 flex-shrink-0">
                                    <a href="{{ route('tasks.files.download', [$task, $file]) }}"
                                       class="btn btn-sm btn-outline-primary p-1 px-2" title="Download">
                                        <i class="bi bi-download" style="font-size:.75rem;"></i>
                                    </a>
                                    @if(Auth::user()->role === 'admin' || $file->uploaded_by === Auth::id())
                                    <form method="POST"
                                          action="{{ route('tasks.files.destroy', [$task, $file]) }}"
                                          onsubmit="return confirm('Delete this file?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Delete">
                                            <i class="bi bi-trash" style="font-size:.75rem;"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>

</div>

@endsection
