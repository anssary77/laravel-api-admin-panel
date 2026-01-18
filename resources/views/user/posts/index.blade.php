@extends('user.layouts.app')

@section('title', 'All Posts from Others')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">All Posts from Others</h1>
            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                @forelse($posts as $post)
                    <div class="post-item mb-4 pb-4 border-bottom last-child-border-0">
                        <div class="d-flex align-items-start">
                            <div class="post-number me-3 text-muted fw-bold">
                                #{{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}
                            </div>
                            <img src="{{ $post->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name ?? 'User') }}" 
                                 alt="{{ $post->user->name ?? 'User' }}" 
                                 class="rounded-circle me-3" width="48" height="48">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-primary">{{ $post->title }}</h6>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">By <strong>{{ $post->user->name ?? 'Unknown' }}</strong></small>
                                </div>
                                <p class="text-secondary mb-0">
                                    {{ Str::limit($post->description, 512) }}
                                </p>
                                @if($post->contact_phone_number)
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-phone-alt me-1"></i> Contact: {{ $post->contact_phone_number }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper text-muted mb-3 fa-3x"></i>
                        <p class="text-muted fw-bold">No posts from other users found.</p>
                        <p class="text-muted small">When other users publish posts, they will appear here automatically.</p>
                    </div>
                @endforelse

                <div class="mt-4 d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .post-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    .post-number {
        font-size: 1.2rem;
        min-width: 40px;
        opacity: 0.5;
    }
</style>
@endpush
