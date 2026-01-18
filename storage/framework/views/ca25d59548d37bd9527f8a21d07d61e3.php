<?php $__env->startSection('title', 'User Details - ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">User Details</h1>
            <div>
                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Users
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Profile Information
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>" class="img-thumbnail rounded-circle" style="width: 120px; height: 120px;">
                </div>
                <h4 class="mb-1"><?php echo e($user->name); ?></h4>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->username): ?>
                    <p class="text-primary mb-1"><?php echo e('@' . $user->username); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="text-muted mb-3"><?php echo e($user->email); ?></p>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="mb-1"><?php echo e($user->created_at->format('M d, Y')); ?></h5>
                            <small class="text-muted">Member Since</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->last_login_at): ?>
                                <?php echo e($user->last_login_at->diffForHumans()); ?>

                            <?php else: ?>
                                Never
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </h5>
                        <small class="text-muted">Last Login</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Account Status
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Status:</span>
                    <span class="badge bg-<?php echo e($user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : 'danger')); ?>">
                        <?php echo e(ucfirst($user->status)); ?>

                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Email Verified:</span>
                    <span class="badge bg-<?php echo e($user->email_verified_at ? 'success' : 'warning'); ?>">
                        <?php echo e($user->email_verified_at ? 'Yes' : 'No'); ?>

                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Two Factor:</span>
                    <span class="badge bg-secondary"><?php echo e($user->two_factor_enabled ? 'Enabled' : 'Disabled'); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Account Role
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-0">
                    <h6 class="text-muted mb-3">Assigned Role</h6>
                    <span class="badge bg-<?php echo e($user->role == 'admin' ? 'info' : 'secondary'); ?> fs-6">
                        <i class="fas fa-user-tag me-1"></i><?php echo e(ucfirst($user->role)); ?>

                    </span>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Additional Information
                </h5>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->bio): ?>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Bio</h6>
                        <p class="mb-0"><?php echo e($user->bio); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2">User ID</h6>
                        <p class="mb-0"><?php echo e($user->id); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2">Email</h6>
                        <p class="mb-0"><?php echo e($user->email); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->mobile_number): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Mobile Number</h6>
                            <p class="mb-0"><?php echo e($user->mobile_number); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2">Created</h6>
                        <p class="mb-0"><?php echo e($user->created_at->format('M d, Y H:i')); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-2">Last Updated</h6>
                        <p class="mb-0"><?php echo e($user->updated_at->format('M d, Y H:i')); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->last_login_at): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Last Login</h6>
                            <p class="mb-0"><?php echo e($user->last_login_at->format('M d, Y H:i')); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->last_login_ip): ?>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Last Login IP</h6>
                            <p class="mb-0"><?php echo e($user->last_login_ip); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentActivity && $recentActivity->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>Description</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e(ucfirst($activity->log_name)); ?></span>
                                        </td>
                                        <td><?php echo e($activity->description); ?></td>
                                        <td>
                                            <small class="text-muted"><?php echo e($activity->created_at->diffForHumans()); ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                        <p class="text-muted">No recent activity found.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/users/show.blade.php ENDPATH**/ ?>