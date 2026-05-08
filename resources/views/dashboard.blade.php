@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-kicker', Auth::user()->role === 'admin' ? 'Admin overview' : 'My workspace')

@section('content')
@php
    $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    $openTasks = max($totalTasks - $completedTasks, 0);
    $stats = [
        ['label' => 'Total Tasks', 'value' => $totalTasks, 'icon' => 'bi-kanban', 'tone' => 'soft-primary', 'note' => 'All visible work'],
        ['label' => 'Pending', 'value' => $pendingTasks, 'icon' => 'bi-clock-history', 'tone' => 'soft-secondary', 'note' => 'Needs attention'],
        ['label' => 'In Progress', 'value' => $inProgressTasks, 'icon' => 'bi-arrow-repeat', 'tone' => 'soft-warning', 'note' => 'Currently moving'],
        ['label' => 'Completed', 'value' => $completedTasks, 'icon' => 'bi-check2-circle', 'tone' => 'soft-success', 'note' => $completionRate . '% completion'],
    ];
@endphp

<div class="row g-4 mb-4">
    @foreach($stats as $stat)
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="text-muted small fw-semibold">{{ $stat['label'] }}</div>
                            <div class="display-6 fw-bold mb-1">{{ $stat['value'] }}</div>
                            <div class="small text-muted">{{ $stat['note'] }}</div>
                        </div>
                        <div class="stat-icon {{ $stat['tone'] }}">
                            <i class="bi {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="h6 fw-bold mb-0">Work Health</h2>
                    <div class="text-muted small">Status distribution across your task list</div>
                </div>
                <span class="badge text-bg-primary rounded-pill">{{ $completionRate }}%</span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div class="position-relative d-inline-flex align-items-center justify-content-center" style="width: 168px; height: 168px;">
                        <div class="rounded-circle border border-5 border-primary-subtle w-100 h-100"></div>
                        <div class="position-absolute text-center">
                            <div class="display-6 fw-bold">{{ $completionRate }}%</div>
                            <div class="small text-muted">complete</div>
                        </div>
                    </div>
                </div>

                <div class="vstack gap-3">
                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-semibold">Completed</span>
                            <span class="text-muted">{{ $completedTasks }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-semibold">Open tasks</span>
                            <span class="text-muted">{{ $openTasks }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $totalTasks > 0 ? round(($openTasks / $totalTasks) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card h-100">
            <div class="card-header d-flex flex-wrap gap-3 align-items-center justify-content-between">
                <div>
                    <h2 class="h6 fw-bold mb-0">{{ Auth::user()->role === 'admin' ? 'Team Load' : 'Task Summary' }}</h2>
                    <div class="text-muted small">{{ Auth::user()->role === 'admin' ? 'Assigned task volume by user' : 'Your current assignment mix' }}</div>
                </div>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-list-check me-1"></i>View tasks
                </a>
            </div>

            @if(Auth::user()->role === 'admin')
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Email</th>
                                <th class="text-end pe-4">Assigned Tasks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usersWithTasks as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            <span class="fw-semibold">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $user->email }}</td>
                                    <td class="text-end pe-4">
                                        <span class="badge text-bg-primary rounded-pill px-3">{{ $user->assigned_tasks_count }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <x-empty-state icon="bi-people" title="No users found" message="Users will appear here once accounts are created." />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="rounded-4 border p-3 h-100">
                                <x-status-badge status="pending" class="mb-3" />
                                <div class="fs-3 fw-bold">{{ $pendingTasks }}</div>
                                <div class="text-muted small">Waiting to start</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-4 border p-3 h-100">
                                <x-status-badge status="in_progress" class="mb-3" />
                                <div class="fs-3 fw-bold">{{ $inProgressTasks }}</div>
                                <div class="text-muted small">Active right now</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-4 border p-3 h-100">
                                <x-status-badge status="completed" class="mb-3" />
                                <div class="fs-3 fw-bold">{{ $completedTasks }}</div>
                                <div class="text-muted small">Finished</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
