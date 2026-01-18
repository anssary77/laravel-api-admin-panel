@extends('admin.layouts.app')

@section('title', 'Permissions')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Permissions</h1>
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
                            <i class="fas fa-key me-2"></i>Permissions Management
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.permissions.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search permissions..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($permissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th>Description</th>
                                    <th>Group</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            <strong>{{ $permission->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $permission->guard_name }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $permission->description }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $group = explode('-', $permission->name)[0] ?? 'other';
                                            @endphp
                                            <span class="badge bg-info text-capitalize">{{ $group }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $permission->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(!$permission->is_system)
                                                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permission?');">
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
                        <i class="fas fa-key text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">No permissions found</h5>
                        <p class="text-muted">Permissions are typically created automatically when you create roles.</p>
                    </div>
                @endif
            </div>
            @if($permissions->count() > 0)
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-end">
                        {{ $permissions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection