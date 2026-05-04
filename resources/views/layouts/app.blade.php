<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            min-height: 100vh;
            background-color: #f0f2f5;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3a5f 0%, #162d4a 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            padding-top: 0;
            box-shadow: 2px 0 8px rgba(0,0,0,.15);
        }

        #sidebar .brand {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            padding: 20px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: .02em;
        }

        #sidebar .brand-icon {
            background: rgba(255,255,255,.15);
            border-radius: 8px;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
        }

        #sidebar .nav-section-label {
            color: rgba(255,255,255,.4);
            font-size: .67rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 18px 20px 6px;
        }

        #sidebar .nav-link {
            color: rgba(255,255,255,.7);
            padding: 10px 20px;
            font-size: .88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 0;
            transition: all .2s;
            position: relative;
        }

        #sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,.08);
        }

        #sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,.12);
        }

        #sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: #4da3ff;
            border-radius: 0 2px 2px 0;
        }

        #sidebar .nav-link i { width: 18px; text-align: center; font-size: 1rem; }

        #sidebar .role-badge {
            margin: 12px 20px 0;
        }

        #sidebar .logout-btn {
            position: absolute;
            bottom: 20px; left: 0; right: 0;
            padding: 0 20px;
        }

        /* ── Topbar ── */
        #topbar {
            margin-left: 250px;
            height: 60px;
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: fixed;
            top: 0; right: 0; left: 0;
            z-index: 99;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }

        #topbar .page-title {
            font-weight: 600;
            font-size: .95rem;
            color: #1e3a5f;
        }

        #topbar .user-info {
            font-size: .875rem;
            color: #555;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #topbar .user-avatar {
            width: 32px; height: 32px;
            background: #1e3a5f;
            border-radius: 50%;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem;
            font-weight: 700;
        }

        /* ── Main Content ── */
        #main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 28px 30px;
            min-height: calc(100vh - 60px);
        }

        /* ── Cards ── */
        .card { border-radius: 10px; }
        .card-header { border-radius: 10px 10px 0 0 !important; }

        /* ── Tables ── */
        .table th { font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; }

        /* ── Alerts ── */
        .alert { border-radius: 10px; border: none; }

        /* ── Badges ── */
        .badge { font-weight: 500; }
    </style>
</head>
<body>

{{-- ═══ SIDEBAR ═══ --}}
<div id="sidebar">
    <div class="brand">
        <div class="brand-icon"><i class="bi bi-check2-square"></i></div>
        Task Manager
    </div>

    <div class="nav-section-label">Navigation</div>
    <nav>
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('tasks.index') }}"
           class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <i class="bi bi-list-task"></i> Tasks
        </a>

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('tasks.create') }}"
           class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
            <i class="bi bi-plus-square"></i> New Task
        </a>

        <div class="nav-section-label">Admin</div>
        <a href="{{ route('users.index') }}"
           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        @endif

        <div class="nav-section-label">Account</div>
        <a href="{{ route('profile.show') }}"
           class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> My Profile
        </a>
    </nav>

    {{-- Role Badge --}}
    <div class="role-badge">
        @if(Auth::user()->role === 'admin')
            <span class="badge bg-danger">Admin</span>
        @else
            <span class="badge bg-secondary">User</span>
        @endif
    </div>

    {{-- Logout --}}
    <div class="logout-btn">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-left me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- ═══ TOPBAR ═══ --}}
<div id="topbar">
    <span class="page-title">@yield('page-title', 'Dashboard')</span>
    <a href="{{ route('profile.show') }}" class="user-info text-decoration-none" style="color:inherit;">
        @if(Auth::user()->profilePhotoUrl())
            <img src="{{ Auth::user()->profilePhotoUrl() }}"
                 class="rounded-circle border"
                 style="width:32px;height:32px;object-fit:cover;"
                 alt="Photo">
        @else
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        @endif
        {{ Auth::user()->name }}
    </a>
</div>

{{-- ═══ MAIN CONTENT ═══ --}}
<div id="main-content">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>