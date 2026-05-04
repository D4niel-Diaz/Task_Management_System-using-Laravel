@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">All Users</h5>
        <small class="text-muted">{{ $users->count() }} registered user(s)</small>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Assigned Tasks</th>
                        <th>Pending</th>
                        <th>In Progress</th>
                        <th>Completed</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                     style="width:34px;height:34px;font-size:.85rem;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger rounded-pill">Admin</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">User</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill px-3">
                                {{ $user->assigned_tasks_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary rounded-pill px-3">
                                {{ $user->pending_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning rounded-pill px-3">
                                {{ $user->in_progress_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success rounded-pill px-3">
                                {{ $user->completed_count }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
