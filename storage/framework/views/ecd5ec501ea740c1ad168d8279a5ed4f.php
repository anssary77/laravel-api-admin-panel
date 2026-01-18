<?php $__env->startSection('title', 'All Posts from Others'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">All Posts from Others</h1>
            <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="post-item mb-4 pb-4 border-bottom last-child-border-0">
                        <div class="d-flex align-items-start">
                            <div class="post-number me-3 text-muted fw-bold">
                                #<?php echo e(($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration); ?>

                            </div>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->contact_phone_number): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-phone-alt me-1"></i> Contact: <?php echo e($post->contact_phone_number); ?>

                                        </small>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper text-muted mb-3 fa-3x"></i>
                        <p class="text-muted fw-bold">No posts from other users found.</p>
                        <p class="text-muted small">When other users publish posts, they will appear here automatically.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="mt-4 d-flex justify-content-center">
                    <?php echo e($posts->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/user/posts/index.blade.php ENDPATH**/ ?>