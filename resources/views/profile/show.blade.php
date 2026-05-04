@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

<div class="row g-4 justify-content-center">

    {{-- Left: Profile Photo --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4">

                {{-- Avatar display --}}
                <div class="mb-3">
                    @if($user->profilePhotoUrl())
                        <img src="{{ $user->profilePhotoUrl() }}"
                             alt="Profile Photo"
                             class="rounded-circle border shadow-sm"
                             style="width:120px;height:120px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold mx-auto shadow-sm"
                             style="width:120px;height:120px;font-size:3rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <div class="text-muted small mb-3">{{ $user->email }}</div>
                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' }} rounded-pill px-3 py-1 mb-4">
                    {{ ucfirst($user->role) }}
                </span>

                {{-- Upload photo form --}}
                <form method="POST"
                      action="{{ route('profile.photo') }}"
                      enctype="multipart/form-data">
                    @csrf @method('POST')

                    <div class="mb-3 text-start">
                        <label class="form-label fw-semibold small">Upload Profile Photo</label>
                        <input type="file"
                               name="profile_photo"
                               id="profilePhotoInput"
                               class="form-control form-control-sm @error('profile_photo') is-invalid @enderror"
                               accept=".jpg,.jpeg,.png,.gif,.webp">
                        <div class="form-text">Max 2MB. JPG, PNG, GIF, WebP.</div>
                        @error('profile_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                        <i class="bi bi-cloud-upload me-1"></i> Upload Photo
                    </button>
                </form>

                {{-- Remove photo --}}
                @if($user->profile_photo)
                <form method="POST" action="{{ route('profile.photo.delete') }}"
                      onsubmit="return confirm('Remove your profile photo?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-trash me-1"></i> Remove Photo
                    </button>
                </form>
                @endif

            </div>
        </div>
    </div>

    {{-- Right: Account Details --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-person-gear me-2 text-primary"></i>Account Settings
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')

                    {{-- Name --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">
                    <p class="text-muted small mb-3">Leave password fields blank to keep your current password.</p>

                    {{-- Current Password --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Required only if changing password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 6 characters">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repeat new password">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check2 me-1"></i> Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection
