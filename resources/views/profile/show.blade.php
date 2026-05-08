@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-kicker', 'Account settings')

@section('content')
<div class="row g-4">
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    @if($user->profilePhotoUrl())
                        <img src="{{ $user->profilePhotoUrl() }}" alt="Profile Photo" class="avatar avatar-lg border border-4 border-white shadow">
                    @else
                        <span class="avatar avatar-lg shadow">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>

                <h2 class="h4 fw-bold mb-1">{{ $user->name }}</h2>
                <div class="text-muted mb-3">{{ $user->email }}</div>
                <span class="badge {{ $user->role === 'admin' ? 'text-bg-danger' : 'text-bg-secondary' }} rounded-pill px-3 py-2 mb-4">
                    {{ ucfirst($user->role) }}
                </span>

                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" class="text-start">
                    @csrf @method('POST')

                    <div class="upload-panel p-3 mb-3">
                        <label for="profilePhotoInput" class="form-label">Upload Profile Photo</label>
                        <input type="file"
                               name="profile_photo"
                               id="profilePhotoInput"
                               class="form-control @error('profile_photo') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.gif,.webp">
                        <div class="form-text">Max 2MB. JPG, PNG, GIF, or WebP.</div>
                        @error('profile_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Photo
                    </button>
                </form>

                @if($user->profile_photo)
                    <form method="POST" action="{{ route('profile.photo.delete') }}" onsubmit="return confirm('Remove your profile photo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-2"></i>Remove Photo
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-person-gear text-primary me-2"></i>Account Settings</h2>
            </div>
            <div class="card-body p-4 p-lg-5">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input id="email" type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-4 border p-4 my-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="stat-icon soft-primary" style="width: 42px; height: 42px;">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div>
                                <h3 class="h6 fw-bold mb-0">Password</h3>
                                <div class="text-muted small">Leave these fields blank to keep your current password.</div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input id="current_password" type="password" name="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Required only if changing password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input id="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimum 6 characters">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       class="form-control"
                                       placeholder="Repeat new password">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2-circle me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
