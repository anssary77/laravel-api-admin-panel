<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
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
                    <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>" class="rounded-circle me-3" width="64" height="64">
                    <div>
                        <h4 class="mb-0 text-white"><?php echo e($user->name); ?></h4>
                        <small class="text-white-50">Welcome back!</small>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="mb-1 text-white-50 small">Member since</p>
                    <h5 class="text-white"><?php echo e($user->created_at->format('M d, Y')); ?></h5>
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
                        <h2 class="mb-0"><?php echo e($stats['account_status']); ?></h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="badge bg-white text-success">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->email_verified_at): ?>
                            Verified
                        <?php else: ?>
                            Pending Verification
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <h2 class="mb-0"><?php echo e($stats['total_posts']); ?></h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-file-alt fa-3x"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="small text-white-50">Role: <?php echo e($stats['role_name']); ?></span>
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
                <a href="<?php echo e(route('user.posts.index')); ?>" class="btn btn-sm btn-link text-primary text-decoration-none">
                    View All Posts <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $otherPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="post-item mb-4 pb-4 border-bottom last-child-border-0">
                        <div class="d-flex align-items-start">
                            <img src="<?php echo e($post->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name ?? 'User')); ?>" 
                                 alt="<?php echo e($post->user->name ?? 'User'); ?>" 
                                 class="rounded-circle me-3" width="48" height="48">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-primary"><?php echo e($post->title); ?></h6>
                                    <small class="text-muted"><?php echo e($post->created_at->diffForHumans()); ?></small>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">By <strong><?php echo e($post->user->name ?? 'Unknown'); ?></strong></small>
                                </div>
                                <p class="text-secondary mb-0">
                                    <?php echo e(Str::limit($post->description, 512)); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper text-muted mb-3 fa-3x"></i>
                        <p class="text-muted fw-bold">No posts from other users found.</p>
                        <p class="text-muted small">When other users publish posts, they will appear here automatically.</p>
                        <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-sync me-1"></i> Refresh Feed
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="mt-4 d-flex justify-content-center">
                    <?php echo e($otherPosts->links()); ?>

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
                    <div class="col-sm-8"><?php echo e($user->email); ?></div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Username</div>
                    <div class="col-sm-8"><?php echo e($user->username ?: 'Not set'); ?></div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Bio</div>
                    <div class="col-sm-8">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->bio): ?>
                            <p class="mb-0"><?php echo e($user->bio); ?></p>
                        <?php else: ?>
                            <p class="text-muted italic mb-0">No bio provided yet.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="<?php echo e(route('user.profile')); ?>" class="btn btn-primary btn-sm">Edit Profile</a>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentActivity->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 small fw-bold"><?php echo e(ucfirst($activity->description)); ?></h6>
                                    <small class="text-muted"><?php echo e($activity->created_at->diffForHumans()); ?></small>
                                </div>
                                <p class="mb-1 small text-muted">
                                    <?php echo e(ucfirst($activity->log_name)); ?> update
                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-tasks text-muted mb-2 fa-2x"></i>
                        <p class="text-muted small">No recent activity recorded.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->email_verified_at): ?>
                                <span class="badge bg-success">Verified</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Unverified</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light mb-3 mb-md-0">
                            <i class="fas fa-lock fa-2x text-info mb-2"></i>
                            <h6>Password</h6>
                            <span class="text-muted small">Last updated: <?php echo e($user->updated_at->diffForHumans()); ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-sign-in-alt fa-2x text-secondary mb-2"></i>
                            <h6>Last Login</h6>
                            <span class="text-muted small"><?php echo e($user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Just now'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="<?php echo e(route('user.settings')); ?>" class="btn btn-outline-secondary btn-sm">Manage Security Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/user/dashboard.blade.php ENDPATH**/ ?>