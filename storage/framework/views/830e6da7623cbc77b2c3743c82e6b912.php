<?php $__env->startSection('title', 'Activity Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Activity Logs</h1>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-danger" onclick="confirm('Are you sure you want to clear all activity logs?') && document.getElementById('clear-logs-form').submit();">
                    <i class="fas fa-trash me-1"></i>Clear Logs
                </button>
                <form id="clear-logs-form" method="POST" action="<?php echo e(route('admin.activity-logs.clear')); ?>" class="d-none">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                </form>
            </div>
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
                            <i class="fas fa-history me-2"></i>System Activity
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="<?php echo e(route('admin.activity-logs.index')); ?>" class="d-flex">
                            <select name="log_name" class="form-select form-select-sm me-2">
                                <option value="">All Logs</option>
                                <option value="default" <?php echo e(request('log_name') == 'default' ? 'selected' : ''); ?>>Default</option>
                                <option value="auth" <?php echo e(request('log_name') == 'auth' ? 'selected' : ''); ?>>Auth</option>
                                <option value="api" <?php echo e(request('log_name') == 'api' ? 'selected' : ''); ?>>API</option>
                                <option value="admin" <?php echo e(request('log_name') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            </select>
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search logs..." value="<?php echo e(request('search')); ?>">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>User</th>
                                    <th>Activity</th>
                                    <th>Description</th>
                                    <th>Subject</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle me-2 text-muted"></i>
                                                <div>
                                                    <div class="fw-semibold"><?php echo e($log->causer->name ?? 'System'); ?></div>
                                                    <small class="text-muted"><?php echo e($log->causer->email ?? ''); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                                $badgeClass = match($log->event) {
                                                    'created' => 'bg-success',
                                                    'updated' => 'bg-warning text-dark',
                                                    'deleted' => 'bg-danger',
                                                    'login' => 'bg-info',
                                                    'logout' => 'bg-secondary',
                                                    default => 'bg-primary'
                                                };
                                            ?>
                                            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e(ucfirst($log->event)); ?></span>
                                            <div class="small text-muted mt-1"><?php echo e(ucfirst($log->log_name)); ?></div>
                                        </td>
                                        <td>
                                            <div><?php echo e($log->description); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($log->properties) && count($log->properties) > 0): ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="collapse" data-bs-target="#properties-<?php echo e($log->id); ?>">
                                                    <i class="fas fa-info-circle me-1"></i>Details
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->subject): ?>
                                                <span class="badge bg-secondary"><?php echo e(class_basename($log->subject_type)); ?></span>
                                                <div class="small text-muted">ID: <?php echo e($log->subject_id); ?></div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted" title="<?php echo e($log->created_at); ?>">
                                                <?php echo e($log->created_at->diffForHumans()); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('admin.activity-logs.show', $log)); ?>" class="btn btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form method="POST" action="<?php echo e(route('admin.activity-logs.destroy', $log)); ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this log entry?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($log->properties) && count($log->properties) > 0): ?>
                                        <tr class="collapse" id="properties-<?php echo e($log->id); ?>">
                                            <td colspan="7">
                                                <div class="card card-body bg-light border-0 shadow-sm mx-3 my-2">
                                                    <h6 class="mb-3 text-primary"><i class="fas fa-list me-2"></i>Activity Details</h6>
                                                    <?php
                                                        $properties = $log->properties;
                                                        $attributes = $properties['attributes'] ?? [];
                                                        $old = $properties['old'] ?? [];
                                                        $displayData = count($attributes) > 0 ? $attributes : $old;
                                                    ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($displayData) > 0): ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered bg-white mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Field</th>
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($old) > 0 && count($attributes) > 0): ?>
                                                                            <th>Old Value</th>
                                                                            <th>New Value</th>
                                                                        <?php elseif(count($old) > 0): ?>
                                                                            <th>Value</th>
                                                                        <?php else: ?>
                                                                            <th>Value</th>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $displayData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr>
                                                                            <td class="fw-bold"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?></td>
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($old) > 0 && count($attributes) > 0): ?>
                                                                                <td class="text-danger">
                                                                                    <?php echo e(is_array($old[$key] ?? null) ? json_encode($old[$key]) : ($old[$key] ?? 'N/A')); ?>

                                                                                </td>
                                                                                <td class="text-success">
                                                                                    <?php echo e(is_array($value) ? json_encode($value) : $value); ?>

                                                                                </td>
                                                                            <?php elseif(count($old) > 0): ?>
                                                                                <td class="text-danger">
                                                                                    <?php echo e(is_array($value) ? json_encode($value) : $value); ?>

                                                                                </td>
                                                                            <?php else: ?>
                                                                                <td class="text-success">
                                                                                    <?php echo e(is_array($value) ? json_encode($value) : $value); ?>

                                                                                </td>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <pre class="mb-0 small bg-white p-2 border rounded"><code><?php echo e(json_encode($log->properties, JSON_PRETTY_PRINT)); ?></code></pre>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-history text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">No activity logs found</h5>
                        <p class="text-muted">System activity will appear here.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logs->count() > 0): ?>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-end">
                        <?php echo e($logs->links()); ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/activity-logs/index.blade.php ENDPATH**/ ?>