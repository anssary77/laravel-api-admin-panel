@extends('admin.layouts.app')

@section('title', 'Post Details')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Post Details</h1>
        <div class="card">
            <div class="card-body">
                <h2>{{ $post->title }}</h2>
                <p><strong>Author:</strong> {{ $post->user->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> {{ ucfirst($post->status) }}</p>
                <p><strong>Contact Phone:</strong> {{ $post->contact_phone_number }}</p>
                <p><strong>Created At:</strong> {{ $post->created_at->format('Y-m-d H:i') }}</p>
                <hr>
                <p><strong>Description:</strong></p>
                <p>{{ $post->description }}</p>
                <hr>
                <p><strong>Content:</strong></p>
                <p>{{ $post->content }}</p>
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back to Posts</a>
            </div>
        </div>
    </div>
</div>
@endsection
