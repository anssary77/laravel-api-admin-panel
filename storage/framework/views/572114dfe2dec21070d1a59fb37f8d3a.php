<?php $__env->startSection('title', 'Activity Log Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Activity Log Details</h1>
            <div>
                <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Logs
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
                    <i class="fas fa-info-circle me-2"></i>General Information
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="ps-0" style="width: 40%">Log Name:</th>
                        <td><span class="badge bg-primary"><?php echo e(ucfirst($activityLog->log_name)); ?></span></td>
                    </tr>
                    <tr>
                        <th class="ps-0">Event:</th>
                        <td><?php echo e(ucfirst($activityLog->event)); ?></td>
                    </tr>
                    <tr>
                        <th class="ps-0">Description:</th>
                        <td><?php echo e($activityLog->description); ?></td>
                    </tr>
                    <tr>
                        <th class="ps-0">Time:</th>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activityLog->created_at): ?>
                                <?php echo e($activityLog->created_at->format('Y-m-d H:i:s')); ?><br>
                                <small class="text-muted"><?php echo e($activityLog->created_at->diffForHumans()); ?></small>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Causer (User)
                </h5>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activityLog->causer): ?>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-user-circle fa-2x me-3 text-muted"></i>
                        <div>
                            <div class="fw-bold"><?php echo e($activityLog->causer->name); ?></div>
                            <div class="text-muted small"><?php echo e($activityLog->causer->email); ?></div>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.users.show', $activityLog->causer_id)); ?>" class="btn btn-sm btn-outline-primary">View User Profile</a>
                <?php else: ?>
                    <div class="text-muted">System / Unknown</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activityLog->subject): ?>
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-cube me-2"></i>Subject (Model)
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="ps-0" style="width: 40%">Type:</th>
                            <td><code><?php echo e(class_basename($activityLog->subject_type)); ?></code></td>
                        </tr>
                        <tr>
                            <th class="ps-0">ID:</th>
                            <td><?php echo e($activityLog->subject_id); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-code me-2"></i>Activity Properties
                </h5>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($activityLog->properties) && count($activityLog->properties) > 0): ?>
                    <?php
                        $properties = $activityLog->properties;
                        $attributes = $properties['attributes'] ?? [];
                        $old = $properties['old'] ?? [];
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($attributes) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Field</th>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($old) > 0): ?>
                                            <th>Old Value</th>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <th>New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-bold text-primary"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($old) > 0): ?>
                                                <td class="text-danger">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($old[$key] ?? null)): ?>
                                                        <pre class="mb-0 small"><code><?php echo e(json_encode($old[$key], JSON_PRETTY_PRINT)); ?></code></pre>
                                                    <?php else: ?>
                                                        <?php echo e($old[$key] ?? 'N/A'); ?>

                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <td class="text-success">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($value)): ?>
                                                    <pre class="mb-0 small"><code><?php echo e(json_encode($value, JSON_PRETTY_PRINT)); ?></code></pre>
                                                <?php else: ?>
                                                    <?php echo e($value); ?>

                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <pre class="bg-light p-4 rounded"><code><?php echo e(json_encode($activityLog->properties, JSON_PRETTY_PRINT)); ?></code></pre>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-ghost fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No additional properties recorded for this activity.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/activity-logs/show.blade.php ENDPATH**/ ?>