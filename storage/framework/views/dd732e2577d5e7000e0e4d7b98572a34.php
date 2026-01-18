<?php $__env->startSection('title', 'Permissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Permissions</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-key me-2"></i>Permissions Management
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="<?php echo e(route('admin.permissions.index')); ?>" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search permissions..." value="<?php echo e(request('search')); ?>">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($permissions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th>Description</th>
                                    <th>Group</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($permission->name); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo e($permission->guard_name); ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo e($permission->description); ?></small>
                                        </td>
                                        <td>
                                            <?php
                                                $group = explode('-', $permission->name)[0] ?? 'other';
                                            ?>
                                            <span class="badge bg-info text-capitalize"><?php echo e($group); ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo e($permission->created_at->format('M d, Y')); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('admin.permissions.edit', $permission)); ?>" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$permission->is_system): ?>
                                                <form action="<?php echo e(route('admin.permissions.destroy', $permission)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-key text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">No permissions found</h5>
                        <p class="text-muted">Permissions are typically created automatically when you create roles.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($permissions->count() > 0): ?>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-end">
                        <?php echo e($permissions->links()); ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/permissions/index.blade.php ENDPATH**/ ?>