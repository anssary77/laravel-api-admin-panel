@extends('admin.layouts.app')

@section('title', 'Posts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Posts Management</h1>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Post
            </a>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.posts.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="title" class="form-control" placeholder="Search posts..." value="{{ request('title') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="others_only" value="1" id="othersOnly" {{ request('others_only') ? 'checked' : '' }}>
                            <label class="form-check-label" for="othersOnly">Others' Posts</label>
                        </div>
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

<!-- Posts Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Contact Phone</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ Str::limit($post->description, 512) }}</td>
                            <td>{{ $post->contact_phone_number }}</td>
                            <td>{{ $post->user->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($post->status) }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.posts.edit', [$post, 'page' => request('page')]) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-5">
                    {{ $posts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
