@props(['status'])

@php
    $value = $status ?: 'pending';
    $label = ucwords(str_replace('_', ' ', $value));
    $classes = match ($value) {
        'completed' => 'text-bg-success',
        'in_progress' => 'text-bg-warning',
        default => 'text-bg-secondary',
    };
    $icon = match ($value) {
        'completed' => 'bi-check-circle-fill',
        'in_progress' => 'bi-arrow-repeat',
        default => 'bi-clock-fill',
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge rounded-pill '.$classes]) }}>
    <i class="bi {{ $icon }} me-1"></i>{{ $label }}
</span>
