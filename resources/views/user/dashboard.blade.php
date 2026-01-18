@extends('user.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">User Dashboard</h1>
    </div>
</div>

<!-- Welcome and Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stats-card border-0 h-100">
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="64" height="64">
                    <div>
                        <h4 class="mb-0 text-white">{{ $user->name }}</h4>
                        <small class="text-white-50">Welcome back!</small>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="mb-1 text-white-50 small">Member since</p>
                    <h5 class="text-white">{{ $user->created_at->format('M d, Y') }}</h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card bg-success border-0 text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">Account Status</h6>
                        <h2 class="mb-0">{{ $stats['account_status'] }}</h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="badge bg-white text-success">
                        @if($user->email_verified_at)
                            Verified
                        @else
                            Pending Verification
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card bg-info border-0 text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">My Total Posts</h6>
                        <h2 class="mb-0">{{ $stats['total_posts'] }}</h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-file-alt fa-3x"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="small text-white-50">Role: {{ $stats['role_name'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Posts from Other Users -->
    <div class="col-lg-12 mb-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-rss text-primary me-2"></i>Recent Posts from Others
                </h5>
                <a href="{{ route('user.posts.index') }}" class="btn btn-sm btn-link text-primary text-decoration-none">
                    View All Posts <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @forelse($otherPosts as $post)
                    <div class="post-item mb-4 pb-4 border-bottom last-child-border-0">
                        <div class="d-flex align-items-start">
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
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper text-muted mb-3 fa-3x"></i>
                        <p class="text-muted fw-bold">No posts from other users found.</p>
                        <p class="text-muted small">When other users publish posts, they will appear here automatically.</p>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-sync me-1"></i> Refresh Feed
                        </a>
                    </div>
                @endforelse

                <div class="mt-4 d-flex justify-content-center">
                    {{ $otherPosts->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Profile Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Email Address</div>
                    <div class="col-sm-8">{{ $user->email }}</div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Username</div>
                    <div class="col-sm-8">{{ $user->username ?: 'Not set' }}</div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Bio</div>
                    <div class="col-sm-8">
                        @if($user->bio)
                            <p class="mb-0">{{ $user->bio }}</p>
                        @else
                            <p class="text-muted italic mb-0">No bio provided yet.</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('user.profile') }}" class="btn btn-primary btn-sm">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2 text-warning"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body p-0">
                @if($recentActivity->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentActivity as $activity)
                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 small fw-bold">{{ ucfirst($activity->description) }}</h6>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 small text-muted">
                                    {{ ucfirst($activity->log_name) }} update
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tasks text-muted mb-2 fa-2x"></i>
                        <p class="text-muted small">No recent activity recorded.</p>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white text-center py-2">
                <a href="#" class="small text-decoration-none">View All Activity</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Account Security -->
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2 text-success"></i>Security & Access
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light mb-3 mb-md-0">
                            <i class="fas fa-envelope-open-text fa-2x text-primary mb-2"></i>
                            <h6>Email Status</h6>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Unverified</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light mb-3 mb-md-0">
                            <i class="fas fa-lock fa-2x text-info mb-2"></i>
                            <h6>Password</h6>
                            <span class="text-muted small">Last updated: {{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-sign-in-alt fa-2x text-secondary mb-2"></i>
                            <h6>Last Login</h6>
                            <span class="text-muted small">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Just now' }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('user.settings') }}" class="btn btn-outline-secondary btn-sm">Manage Security Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
