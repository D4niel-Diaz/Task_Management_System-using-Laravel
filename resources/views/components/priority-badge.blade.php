@props(['priority'])

@php
    $value = $priority ?: 'medium';
    $label = ucfirst($value);
    $classes = match ($value) {
        'high' => 'text-bg-danger',
        'low' => 'text-bg-info',
        default => 'text-bg-primary',
    };
    $icon = match ($value) {
        'high' => 'bi-exclamation-triangle-fill',
        'low' => 'bi-arrow-down-circle-fill',
        default => 'bi-dot',
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge rounded-pill '.$classes]) }}>
    <i class="bi {{ $icon }} me-1"></i>{{ $label }}
</span>
