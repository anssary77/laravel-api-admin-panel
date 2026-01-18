@extends('admin.layouts.app')

@section('title', isset($user) ? 'Edit User' : 'Create User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">{{ isset($user) ? 'Edit User' : 'Create User' }}</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Users
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>User Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username ?? '') }}">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number ?? '') }}">
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                {{ isset($user) ? 'New Password (leave blank to keep current)' : 'Password' }}
                                @if(!isset($user)) <span class="text-danger">*</span> @endif
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ !isset($user) ? 'required' : '' }}>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                {{ isset($user) ? 'Confirm New Password' : 'Confirm Password' }}
                                @if(!isset($user)) <span class="text-danger">*</span> @endif
                            </label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ !isset($user) ? 'required' : '' }}>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="banned" {{ old('status', $user->status ?? '') == 'banned' ? 'selected' : '' }}>Banned</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="user" {{ old('role', $user->role ?? 'user') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="avatar" class="form-label">Avatar</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($user) && $user->avatar)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($user->avatar) }}" alt="Current Avatar" class="img-thumbnail" style="max-width: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $user->bio ?? '') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            {{ isset($user) ? 'Update User' : 'Create User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        @if(isset($user))
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>User Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>User ID:</strong>
                        <span class="text-muted">{{ $user->id }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <span class="text-muted">{{ $user->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <span class="text-muted">{{ $user->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($user->last_login_at)
                        <div class="mb-3">
                            <strong>Last Login:</strong>
                            <span class="text-muted">{{ $user->last_login_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                    @if($user->email_verified_at)
                        <div class="mb-3">
                            <strong>Email Verified:</strong>
                            <span class="badge bg-success">Yes</span>
                        </div>
                    @else
                        <div class="mb-3">
                            <strong>Email Verified:</strong>
                            <span class="badge bg-warning">No</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Quick Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Use strong passwords for better security
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Assign appropriate roles based on user responsibilities
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Set status to "Inactive" instead of deleting users
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Avatar images should be square for best display
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection