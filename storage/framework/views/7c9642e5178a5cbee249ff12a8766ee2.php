<?php $__env->startSection('title', 'All Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Notifications</h1>
            <button class="btn btn-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double me-1"></i>Mark All as Read
            </button>
        </div>
        
        <div class="card">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item list-group-item-action py-3 <?php echo e($notification->read_at ? 'bg-light' : ''); ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo e($notification->data['title']); ?></h5>
                                <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                            </div>
                            <p class="mb-1"><?php echo e($notification->data['message']); ?></p>
                            <div class="mt-2">
                                <a href="<?php echo e($notification->data['url']); ?>" class="btn btn-sm btn-outline-primary">View Detail</a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->read_at): ?>
                                    <button class="btn btn-sm btn-link text-muted mark-as-read" data-id="<?php echo e($notification->id); ?>">Mark as read</button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <p>No notifications found.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notifications->hasPages()): ?>
                    <div class="p-3 border-top">
                        <?php echo e($notifications->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).on('click', '.mark-as-read', function() {
        const btn = $(this);
        const id = btn.data('id');
        $.post('<?php echo e(route("admin.notifications.mark-as-read")); ?>', { id: id }, function() {
            btn.closest('.list-group-item').addClass('bg-light');
            btn.remove();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/notifications/index.blade.php ENDPATH**/ ?>