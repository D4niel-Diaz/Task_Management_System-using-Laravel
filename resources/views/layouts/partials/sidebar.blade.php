@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
        ['label' => 'Tasks', 'icon' => 'bi-list-check', 'route' => 'tasks.index', 'active' => request()->routeIs('tasks.index') || request()->routeIs('tasks.show') || request()->routeIs('tasks.edit')],
    ];
@endphp

<div class="p-4 pb-3">
    <div class="d-flex align-items-center gap-3">
        <span class="brand-mark"><i class="bi bi-check2-square fs-4"></i></span>
        <div>
            <div class="fw-bold fs-5 lh-sm">Task Manager</div>
            <div class="text-white-50 small">SaaS Workspace</div>
        </div>
    </div>
</div>

<div class="nav-label">Navigation</div>
<nav class="nav flex-column">
    @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}" class="nav-link {{ $item['active'] ? 'active' : '' }}">
            <i class="bi {{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach

    @if($currentUser->role === 'admin')
        <a href="{{ route('tasks.create') }}" class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
            <i class="bi bi-plus-square"></i>
            <span>New Task</span>
        </a>

        <div class="nav-label">Admin</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Users</span>
        </a>
    @endif

    <div class="nav-label">Account</div>
    <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i>
        <span>My Profile</span>
    </a>
</nav>

<div class="mt-auto p-4">
    <div class="rounded-4 p-3 mb-3" style="background: rgba(255,255,255,.08);">
        <div class="d-flex align-items-center gap-2 mb-2">
            @if($currentUser->profilePhotoUrl())
                <img src="{{ $currentUser->profilePhotoUrl() }}" class="avatar" alt="{{ $currentUser->name }}">
            @else
                <span class="avatar">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</span>
            @endif
            <div class="min-w-0">
                <div class="fw-bold text-truncate">{{ $currentUser->name }}</div>
                <div class="text-white-50 small text-truncate">{{ $currentUser->email }}</div>
            </div>
        </div>
        <span class="badge rounded-pill {{ $currentUser->role === 'admin' ? 'text-bg-danger' : 'text-bg-secondary' }}">
            {{ ucfirst($currentUser->role) }}
        </span>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light w-100">
            <i class="bi bi-box-arrow-left me-2"></i>Logout
        </button>
    </form>
</div>
