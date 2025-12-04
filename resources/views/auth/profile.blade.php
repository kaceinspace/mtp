@extends('layouts.dashboard')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('sidebar')
    <a href="{{ route('dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('profile') }}" class="active">
        <i class="bi bi-person"></i> Profile
    </a>
@endsection

@section('content')
<div class="row g-3">
    <!-- Profile Information -->
    <div class="col-md-4">
        <div class="stat-card text-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle mb-3" width="120" height="120">
            <h5 class="mb-1">{{ $user->name }}</h5>
            <p class="text-muted mb-2">{{ $user->email }}</p>
            <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $user->user_type)) }}</span>

            <hr class="my-3">

            <div class="text-start">
                @if($user->nisn)
                <div class="mb-2">
                    <small class="text-muted">NISN:</small>
                    <div>{{ $user->nisn }}</div>
                </div>
                @endif

                @if($user->nip)
                <div class="mb-2">
                    <small class="text-muted">NIP:</small>
                    <div>{{ $user->nip }}</div>
                </div>
                @endif

                @if($user->jurusan)
                <div class="mb-2">
                    <small class="text-muted">Jurusan:</small>
                    <div>{{ $user->jurusan }}</div>
                </div>
                @endif

                @if($user->kelas)
                <div class="mb-2">
                    <small class="text-muted">Kelas:</small>
                    <div>{{ $user->kelas }}</div>
                </div>
                @endif

                @if($user->phone)
                <div class="mb-2">
                    <small class="text-muted">Phone:</small>
                    <div>{{ $user->phone }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Update Profile -->
        <div class="stat-card mb-3">
            <h5 class="mb-3">Update Profile</h5>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Avatar</label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save me-1"></i> Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="stat-card mb-3">
            <h5 class="mb-3">Change Password</h5>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-shield-lock me-1"></i> Change Password
                </button>
            </form>
        </div>

        <!-- Settings -->
        <div class="stat-card">
            <h5 class="mb-3">Settings</h5>
            <form action="{{ route('profile.settings') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input" id="dark_mode" name="dark_mode" value="1" {{ $user->settings['dark_mode'] ?? false ? 'checked' : '' }}>
                    <label class="form-check-label" for="dark_mode">Dark Mode</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input" id="email_notifications" name="email_notifications" value="1" {{ $user->settings['email_notifications'] ?? true ? 'checked' : '' }}>
                    <label class="form-check-label" for="email_notifications">Email Notifications</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input" id="wa_notifications" name="wa_notifications" value="1" {{ $user->settings['wa_notifications'] ?? true ? 'checked' : '' }}>
                    <label class="form-check-label" for="wa_notifications">WhatsApp Notifications</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Language</label>
                    <select name="language" class="form-select">
                        <option value="id" {{ ($user->settings['language'] ?? 'id') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                        <option value="en" {{ ($user->settings['language'] ?? 'id') == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-gear me-1"></i> Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
