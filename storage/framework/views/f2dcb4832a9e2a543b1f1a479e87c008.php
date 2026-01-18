<?php $__env->startSection('title', 'Post Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <h1>Post Details</h1>
        <div class="card">
            <div class="card-body">
                <h2><?php echo e($post->title); ?></h2>
                <p><strong>Author:</strong> <?php echo e($post->user->name ?? 'N/A'); ?></p>
                <p><strong>Status:</strong> <?php echo e(ucfirst($post->status)); ?></p>
                <p><strong>Contact Phone:</strong> <?php echo e($post->contact_phone_number); ?></p>
                <p><strong>Created At:</strong> <?php echo e($post->created_at->format('Y-m-d H:i')); ?></p>
                <hr>
                <p><strong>Description:</strong></p>
                <p><?php echo e($post->description); ?></p>
                <hr>
                <p><strong>Content:</strong></p>
                <p><?php echo e($post->content); ?></p>
                <a href="<?php echo e(route('admin.posts.edit', $post)); ?>" class="btn btn-warning">Edit</a>
                <a href="<?php echo e(route('admin.posts.index')); ?>" class="btn btn-secondary">Back to Posts</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/posts/show.blade.php ENDPATH**/ ?>