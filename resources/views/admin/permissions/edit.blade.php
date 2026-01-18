@extends('admin.layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Edit Permission</h1>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Permissions
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>Edit Permission
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $permission->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" 
                                  rows="3">{{ old('description', $permission->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="group" class="form-label">Group</label>
                        <input type="text" class="form-control @error('group') is-invalid @enderror" 
                               id="group" name="group" 
                               value="{{ old('group', $permission->group) }}">
                        @error('group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Group name to categorize this permission (e.g., users, posts, settings)</small>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Permission Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Guard Name:</strong>
                    <span class="badge bg-secondary">{{ $permission->guard_name }}</span>
                </div>
                <div class="mb-3">
                    <strong>Created:</strong>
                    <span class="text-muted">{{ $permission->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <span class="text-muted">{{ $permission->updated_at->format('M d, Y H:i') }}</span>
                </div>
                <div>
                    <strong>Assigned to Roles:</strong>
                    <div class="mt-2">
                        @forelse($permission->roles as $role)
                            <span class="badge bg-info me-1 mb-1">{{ $role->name }}</span>
                        @empty
                            <span class="text-muted">Not assigned to any roles</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        @if(!$permission->is_system)
        <div class="card mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this permission? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-1"></i> Delete Permission
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
