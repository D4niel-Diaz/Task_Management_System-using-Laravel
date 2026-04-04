@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4">

    {{-- Total Tasks --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-1 text-primary fw-bold">{{ $totalTasks }}</div>
            <div class="text-muted mt-1">Total Tasks</div>
        </div>
    </div>

    {{-- Pending --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-1 text-secondary fw-bold">{{ $pendingTasks }}</div>
            <div class="text-muted mt-1">Pending</div>
        </div>
    </div>

    {{-- In Progress --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-1 text-warning fw-bold">{{ $inProgressTasks }}</div>
            <div class="text-muted mt-1">In Progress</div>
        </div>
    </div>

    {{-- Completed --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-1 text-success fw-bold">{{ $completedTasks }}</div>
            <div class="text-muted mt-1">Completed</div>
        </div>
    </div>

</div>

{{-- Admin Only: Tasks Per User --}}
@if(Auth::user()->role === 'admin')
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white fw-semibold">
        Tasks Per User
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Assigned Tasks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usersWithTasks as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-primary">
                            {{ $user->assigned_tasks_count }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-3">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection