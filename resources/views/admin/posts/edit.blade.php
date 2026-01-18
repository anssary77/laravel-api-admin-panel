@extends('admin.layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Edit Post</h1>
        <form action="{{ route('admin.posts.update', [$post, 'page' => request('page')]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <small class="text-muted">(Max 2048 characters)</small></label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" maxlength="2048" required>{{ old('description', $post->description) }}</textarea>
                <div class="form-text">{{ Str::length(old('description', $post->description)) }}/2048 characters</div>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contact_phone_number" class="form-label">Contact Phone Number</label>
                <input type="tel" name="contact_phone_number" id="contact_phone_number" class="form-control @error('contact_phone_number') is-invalid @enderror" value="{{ old('contact_phone_number', $post->contact_phone_number) }}" required>
                @error('contact_phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">Author</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $post->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </div>
</div>
@endsection
