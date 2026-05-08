@props([
    'icon' => 'bi-inbox',
    'title' => 'Nothing here yet',
    'message' => 'There is no data to show right now.',
    'actionHref' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'empty-state text-center py-5 px-3']) }}>
    <div class="empty-state-icon mx-auto mb-3">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h2 class="h5 fw-bold mb-1">{{ $title }}</h2>
    <p class="text-muted mb-3">{{ $message }}</p>
    @if($actionHref && $actionLabel)
        <a href="{{ $actionHref }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>{{ $actionLabel }}
        </a>
    @endif
</div>
