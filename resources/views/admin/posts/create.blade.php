@extends('admin.layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Create New Post</h1>
        <form action="{{ route('admin.posts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description <small class="text-muted">(Max 2048 characters)</small></label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" maxlength="2048" required>{{ old('description') }}</textarea>
                <div class="form-text">{{ Str::length(old('description', '')) }}/2048 characters</div>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="contact_phone_number" class="form-label">Contact Phone Number</label>
                <input type="tel" name="contact_phone_number" id="contact_phone_number" class="form-control @error('contact_phone_number') is-invalid @enderror" value="{{ old('contact_phone_number') }}" required>
                @error('contact_phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">Author</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
</div>
@endsection
