@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Users Management</h1>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create User
            </a>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Users List
                    <span class="badge bg-secondary ms-2">{{ $users->total() }} total</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                                            Name
                                            @if(request('sort') == 'name')
                                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle" width="32" height="32">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    @if($user->username)
                                                        <small class="text-muted">{{ '@' . $user->username }}</small>
                                                    @else
                                                        <small class="text-muted">#{{ $user->id }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'info' : 'secondary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->id !== auth('web')->id())
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
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
                        <i class="fas fa-users text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">No users found</h5>
                        <p class="text-muted">Try adjusting your search or filter criteria.</p>
                    </div>
                @endif
            </div>
            @if($users->count() > 0)
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="bulk-delete" disabled>
                                <i class="fas fa-trash me-1"></i>Delete Selected
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" id="bulk-deactivate" disabled>
                                <i class="fas fa-ban me-1"></i>Deactivate Selected
                            </button>
                        </div>
                        <div>
                            {{ $users->links() }}
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
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete');
        const bulkDeactivateBtn = document.getElementById('bulk-deactivate');
        
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBulkButtons();
        });
        
        // Individual checkbox change
        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkButtons();
            });
        });
        
        // Update bulk buttons state
        function updateBulkButtons() {
            const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
            bulkDeleteBtn.disabled = checkedCount === 0;
            bulkDeactivateBtn.disabled = checkedCount === 0;
        }
        
        // Bulk delete functionality
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' user(s)?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.users.bulk-actions') }}";
                form.innerHTML = '{!! csrf_field() !!}' + 
                                 '<input type="hidden" name="action" value="delete">' + 
                                 selectedIds.map(id => '<input type="hidden" name="users[]" value="' + id + '">').join('');
                document.body.appendChild(form);
                form.submit();
            }
        });
        
        // Bulk deactivate functionality
        bulkDeactivateBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
            if (confirm('Are you sure you want to deactivate ' + selectedIds.length + ' user(s)?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.users.bulk-actions') }}";
                form.innerHTML = '{!! csrf_field() !!}' + 
                                 '<input type="hidden" name="action" value="deactivate">' + 
                                 selectedIds.map(id => '<input type="hidden" name="users[]" value="' + id + '">').join('');
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>
@endpush