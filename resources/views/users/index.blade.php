@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-kicker', 'Admin panel')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1">Team Directory</h2>
        <div class="text-muted">{{ $users->count() }} registered user(s)</div>
    </div>
    <span class="badge text-bg-light border rounded-pill px-3 py-2">
        <i class="bi bi-shield-check me-1"></i>Admin only
    </span>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Assigned</th>
                    <th>Pending</th>
                    <th>In Progress</th>
                    <th>Completed</th>
                    <th class="pe-4">Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4" style="min-width: 220px;">
                            <div class="d-flex align-items-center gap-3">
                                @if($user->profilePhotoUrl())
                                    <img src="{{ $user->profilePhotoUrl() }}" class="avatar" alt="{{ $user->name }}">
                                @else
                                    <span class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <div class="text-muted small">User #{{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $user->role === 'admin' ? 'text-bg-danger' : 'text-bg-secondary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td><span class="badge text-bg-primary rounded-pill px-3">{{ $user->assigned_tasks_count }}</span></td>
                        <td><span class="badge text-bg-secondary rounded-pill px-3">{{ $user->pending_count }}</span></td>
                        <td><span class="badge text-bg-warning rounded-pill px-3">{{ $user->in_progress_count }}</span></td>
                        <td><span class="badge text-bg-success rounded-pill px-3">{{ $user->completed_count }}</span></td>
                        <td class="text-muted pe-4">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <x-empty-state icon="bi-people" title="No users found" message="Registered users will appear here." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
