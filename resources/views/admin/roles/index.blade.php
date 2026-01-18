@extends('admin.layouts.app')

@section('title', 'Roles')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Roles</h1>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Role
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>Roles Management
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.roles.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search roles..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($roles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th>Users</th>
                                    <th>Permissions</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="form-check-input role-checkbox">
                                        </td>
                                        <td>
                                            <strong>{{ $role->name }}</strong>
                                            @if($role->name === 'super-admin')
                                                <span class="badge bg-danger ms-1">System</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $role->guard_name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $role->users_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $role->permissions_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $role->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($role->name !== 'super-admin')
                                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-shield text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">No roles found</h5>
                        <p class="text-muted">Create your first role to get started.</p>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-1"></i>Create Role
                        </a>
                    </div>
                @endif
            </div>
            @if($roles->count() > 0)
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-outline-danger btn-sm me-2" id="bulk-delete" disabled>
                                    <i class="fas fa-trash me-1"></i>Delete Selected
                                </button>
                                <span class="text-muted" id="selected-count">0 selected</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{ $roles->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete');
        const selectedCountSpan = document.getElementById('selected-count');
        
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            roleCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
        });
        
        // Individual checkbox change
        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedCount();
                // Update select all checkbox state
                selectAllCheckbox.checked = Array.from(roleCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.indeterminate = Array.from(roleCheckboxes).some(cb => cb.checked) && !Array.from(roleCheckboxes).every(cb => cb.checked);
            });
        });
        
        // Update selected count and button state
        function updateSelectedCount() {
            const selectedCount = Array.from(roleCheckboxes).filter(cb => cb.checked).length;
            selectedCountSpan.textContent = selectedCount + ' selected';
            bulkDeleteBtn.disabled = selectedCount === 0;
        }
        
        // Bulk delete functionality
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(roleCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                return;
            }
            
            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' role(s)?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.roles.bulk-delete') }}';
                form.innerHTML = '{{ csrf_field() }}{{ method_field("DELETE") }}' + selectedIds.map(id => '<input type="hidden" name="ids[]" value="' + id + '">').join('');
                document.body.appendChild(form);
                form.submit();
            }
        });
        
        // Initial count update
        updateSelectedCount();
    });
</script>
@endpush