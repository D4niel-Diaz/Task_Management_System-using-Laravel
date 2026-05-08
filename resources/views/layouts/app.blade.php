<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --app-sidebar-width: 280px;
            --app-bg: #f6f8fb;
            --app-surface: #ffffff;
            --app-border: #e6eaf0;
            --app-text: #172033;
            --app-muted: #65748b;
            --app-primary: #2563eb;
            --app-primary-dark: #1d4ed8;
            --app-sidebar: #0f172a;
        }

        * { letter-spacing: 0; }

        body {
            min-height: 100vh;
            background: var(--app-bg);
            color: var(--app-text);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        a { transition: color .18s ease, background-color .18s ease, border-color .18s ease, box-shadow .18s ease; }

        .app-shell { min-height: 100vh; }

        .app-sidebar {
            width: var(--app-sidebar-width);
            background: var(--app-sidebar);
            color: #fff;
        }

        .app-sidebar-fixed {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 30px rgba(37, 99, 235, .28);
        }

        .nav-label {
            color: rgba(255,255,255,.46);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 1.25rem 1.25rem .45rem;
        }

        .app-sidebar .nav-link {
            color: rgba(255,255,255,.72);
            border-radius: .75rem;
            margin: .12rem .85rem;
            padding: .72rem .9rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-weight: 600;
            font-size: .92rem;
        }

        .app-sidebar .nav-link i {
            width: 1.25rem;
            text-align: center;
            font-size: 1rem;
        }

        .app-sidebar .nav-link:hover,
        .app-sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.11);
        }

        .app-sidebar .nav-link.active {
            box-shadow: inset 3px 0 0 #60a5fa;
        }

        .app-main {
            margin-left: var(--app-sidebar-width);
            min-height: 100vh;
        }

        .app-topbar {
            min-height: 72px;
            background: rgba(255,255,255,.92);
            border-bottom: 1px solid var(--app-border);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .content-wrap {
            padding: 2rem;
        }

        .page-kicker {
            color: var(--app-muted);
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .card {
            border: 1px solid var(--app-border);
            border-radius: 1rem;
            box-shadow: 0 14px 45px rgba(15, 23, 42, .06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--app-border);
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1rem 1.25rem;
        }

        .btn {
            border-radius: .72rem;
            font-weight: 700;
        }

        .btn-primary {
            --bs-btn-bg: var(--app-primary);
            --bs-btn-border-color: var(--app-primary);
            --bs-btn-hover-bg: var(--app-primary-dark);
            --bs-btn-hover-border-color: var(--app-primary-dark);
        }

        .btn-icon {
            width: 2.35rem;
            height: 2.35rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .form-control,
        .form-select {
            border-radius: .78rem;
            border-color: #d8dee8;
            min-height: 2.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93b4ff;
            box-shadow: 0 0 0 .22rem rgba(37, 99, 235, .12);
        }

        .form-label {
            color: #344054;
            font-size: .86rem;
            font-weight: 700;
        }

        .table {
            --bs-table-color: #253047;
            --bs-table-hover-bg: #f8fafc;
        }

        .table thead th {
            background: #f8fafc;
            color: #64748b;
            border-bottom: 1px solid var(--app-border);
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .table tbody td {
            border-color: #eef2f7;
            vertical-align: middle;
        }

        .badge { font-weight: 700; }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 800;
            flex: 0 0 auto;
        }

        .avatar-lg {
            width: 124px;
            height: 124px;
            font-size: 3rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .soft-primary { background: #eaf1ff; color: #1d4ed8; }
        .soft-success { background: #e8f8ef; color: #15803d; }
        .soft-warning { background: #fff5d7; color: #a16207; }
        .soft-secondary { background: #eef2f7; color: #475569; }
        .soft-danger { background: #feecec; color: #b91c1c; }

        .empty-state-icon {
            width: 62px;
            height: 62px;
            border-radius: 18px;
            background: #eef4ff;
            color: var(--app-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .upload-panel {
            border: 1px dashed #b9c5d6;
            background: #f8fafc;
            border-radius: 1rem;
        }

        .hover-lift {
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 50px rgba(15, 23, 42, .09);
            border-color: #d7dfed;
        }

        .pagination {
            --bs-pagination-border-radius: .72rem;
            --bs-pagination-color: var(--app-primary);
            --bs-pagination-active-bg: var(--app-primary);
            --bs-pagination-active-border-color: var(--app-primary);
        }

        @media (max-width: 991.98px) {
            .app-main { margin-left: 0; }
            .content-wrap { padding: 1.25rem; }
            .app-topbar { min-height: 64px; }
        }

        @media (max-width: 575.98px) {
            .content-wrap { padding: 1rem; }
            .card { border-radius: .9rem; }
            .page-title-text { font-size: 1.2rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $currentUser = Auth::user();
    $initial = strtoupper(substr($currentUser->name, 0, 1));
@endphp

<div class="app-shell">
    <aside class="app-sidebar app-sidebar-fixed d-none d-lg-flex flex-column">
        @include('layouts.partials.sidebar', ['currentUser' => $currentUser])
    </aside>

    <div class="offcanvas offcanvas-start app-sidebar text-bg-dark" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-light border-opacity-10">
            <h2 class="offcanvas-title h6 fw-bold mb-0" id="mobileSidebarLabel">Task Manager</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column">
            @include('layouts.partials.sidebar', ['currentUser' => $currentUser])
        </div>
    </div>

    <main class="app-main">
        <header class="app-topbar">
            <div class="container-fluid h-100">
                <div class="d-flex align-items-center justify-content-between gap-3 py-3">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <button class="btn btn-outline-secondary btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Open navigation">
                            <i class="bi bi-list"></i>
                        </button>
                        <div class="min-w-0">
                            <div class="page-kicker">@yield('page-kicker', 'Workspace')</div>
                            <h1 class="h4 page-title-text fw-bold mb-0 text-truncate">@yield('page-title', 'Dashboard')</h1>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light border d-flex align-items-center gap-2 px-2 px-sm-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if($currentUser->profilePhotoUrl())
                                <img src="{{ $currentUser->profilePhotoUrl() }}" class="avatar" alt="{{ $currentUser->name }}">
                            @else
                                <span class="avatar">{{ $initial }}</span>
                            @endif
                            <span class="d-none d-sm-inline text-start">
                                <span class="d-block small fw-bold lh-sm">{{ $currentUser->name }}</span>
                                <span class="d-block text-muted" style="font-size: .72rem;">{{ ucfirst($currentUser->role) }}</span>
                            </span>
                            <i class="bi bi-chevron-down small text-muted d-none d-sm-inline"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">
                            <li><a class="dropdown-item rounded-3" href="{{ route('profile.show') }}"><i class="bi bi-person-circle me-2"></i>My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-wrap">
            @yield('content')
        </div>
    </main>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true" data-bs-delay="4500">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6500">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach((toastNode) => {
        bootstrap.Toast.getOrCreateInstance(toastNode).show();
    });
</script>
@stack('scripts')
</body>
</html>
