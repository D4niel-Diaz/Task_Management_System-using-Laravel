@props(['status'])

@php
    $value = $status ?: 'pending';
    $progress = match ($value) {
        'completed' => 100,
        'in_progress' => 62,
        default => 18,
    };
    $bar = match ($value) {
        'completed' => 'bg-success',
        'in_progress' => 'bg-warning',
        default => 'bg-secondary',
    };
@endphp

<div {{ $attributes->merge(['class' => 'task-progress']) }}>
    <div class="d-flex justify-content-between align-items-center small text-muted mb-1">
        <span>Progress</span>
        <span class="fw-semibold text-dark">{{ $progress }}%</span>
    </div>
    <div class="progress" role="progressbar" aria-label="Task progress" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="height: 8px;">
        <div class="progress-bar {{ $bar }}" style="width: {{ $progress }}%"></div>
    </div>
</div>
