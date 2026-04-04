<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background-color: #f4f6f9;
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #1e3a5f;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding-top: 20px;
        }

        #sidebar .brand {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            padding: 10px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: block;
        }

        #sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 12px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #ffffff;
            background-color: rgba(255,255,255,0.1);
        }

        #sidebar .role-badge {
            margin: 15px 20px;
        }

        #sidebar .logout-btn {
            position: absolute;
            bottom: 20px;
            width: 100%;
            padding: 0 20px;
        }

        /* Topbar */
        #topbar {
            margin-left: 250px;
            height: 60px;
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 99;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        #topbar .page-title {
            font-weight: 600;
            font-size: 1rem;
            color: #1e3a5f;
        }

        #topbar .user-info {
            font-size: 0.9rem;
            color: #555;
        }

        /* Main Content */
        #main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 30px;
        }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<div id="sidebar">
    <span class="brand">
        <i class="bi bi-check2-square me-2"></i>Task Manager
    </span>

    <nav class="mt-3">
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('tasks.index') }}"
        class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <i class="bi bi-list-task"></i> Tasks
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

    {{-- Logout Button --}}
    <div class="logout-btn">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-left me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- TOPBAR --}}
<div id="topbar">
    <span class="page-title">@yield('page-title', 'Dashboard')</span>
    <span class="user-info">
        <i class="bi bi-person-circle me-1"></i>
        {{ Auth::user()->name }}
    </span>
</div>

{{-- MAIN CONTENT --}}
<div id="main-content">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Page Content --}}
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>