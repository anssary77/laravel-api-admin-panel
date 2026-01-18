@extends('admin.layouts.app')

@section('title', isset($role) ? 'Edit Role' : 'Create Role')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h1>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Roles
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Role Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
                    @csrf
                    @if(isset($role))
                        @method('PUT')
                    @endif

                    @php
                        $currentGuard = request()->input('guard_name', old('guard_name', $role->guard_name ?? 'web'));
                    @endphp
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name ?? '') }}" required {{ isset($role) && $role->name === 'super-admin' ? 'readonly' : '' }}>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Use lowercase letters and hyphens (e.g., 'admin-user')</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="guard_name" class="form-label">Guard Name <span class="text-danger">*</span></label>
                            <select class="form-select @error('guard_name') is-invalid @enderror" id="guard_name" name="guard_name" required {{ isset($role) && $role->name === 'super-admin' ? 'disabled' : '' }}>
                                        <option value="web" {{ $currentGuard == 'web' ? 'selected' : '' }}>Web</option>
                                        <option value="api" {{ $currentGuard == 'api' ? 'selected' : '' }}>API</option>
                                    </select>
                            @error('guard_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $role->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Brief description of what this role allows</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Permissions <span class="text-danger">*</span></label>
                        <div class="row">
                            @php
                                $selectedPermissions = old('permissions', isset($role)
                                    ? $role->permissions->where('guard_name', $currentGuard)->pluck('name')->toArray()
                                    : []);
                                $groupedPermissions = $permissions->groupBy(function($permission) {
                                    return explode('-', $permission->name)[0] ?? 'other';
                                });
                            @endphp
                            
                            @foreach($groupedPermissions as $group => $groupPermissions)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="mb-0 text-capitalize">{{ $group }} Permissions</h6>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($groupPermissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                                        {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}
                                                        {{ isset($role) && $role->name === 'super-admin' ? 'checked disabled' : '' }}>
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                        <small class="text-muted d-block">{{ $permission->description }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            {{ isset($role) ? 'Update Role' : 'Create Role' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        @if(isset($role))
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Role Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Role ID:</strong>
                        <span class="text-muted">{{ $role->id }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <span class="text-muted">{{ $role->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <span class="text-muted">{{ $role->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Users with this role:</strong>
                        <span class="badge bg-info">{{ $role->users_count ?? 0 }}</span>
                    </div>
                    <div>
                        <strong>Total permissions:</strong>
                        <span class="badge bg-warning">{{ $role->permissions_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Role Guidelines
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Use descriptive names that clearly indicate the role's purpose
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Follow the naming convention: resource-action (e.g., 'users-create')
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Assign only necessary permissions to maintain security
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Super-admin role cannot be modified or deleted
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var guardSelect = document.getElementById('guard_name');
        if (!guardSelect) return;
        guardSelect.addEventListener('change', function () {
            var url = new URL(window.location.href);
            url.searchParams.set('guard_name', guardSelect.value);
            window.location.href = url.toString();
        });
    });
</script>
@endsection