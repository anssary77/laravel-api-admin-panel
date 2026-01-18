<?php $__env->startSection('title', 'Roles'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Roles</h1>
            <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Role
            </a>
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
                            <i class="fas fa-user-shield me-2"></i>Roles Management
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="<?php echo e(route('admin.roles.index')); ?>" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search roles..." value="<?php echo e(request('search')); ?>">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($roles->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th>Users</th>
                                    <th>Permissions</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>" class="form-check-input role-checkbox">
                                        </td>
                                        <td>
                                            <strong><?php echo e($role->name); ?></strong>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role->name === 'super-admin'): ?>
                                                <span class="badge bg-danger ms-1">System</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo e($role->guard_name); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($role->users_count ?? 0); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning"><?php echo e($role->permissions_count ?? 0); ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo e($role->created_at->format('M d, Y')); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('admin.roles.show', $role)); ?>" class="btn btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.roles.edit', $role)); ?>" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role->name !== 'super-admin'): ?>
                                                    <form method="POST" action="<?php echo e(route('admin.roles.destroy', $role)); ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
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
                        <i class="fas fa-user-shield text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">No roles found</h5>
                        <p class="text-muted">Create your first role to get started.</p>
                        <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-1"></i>Create Role
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($roles->count() > 0): ?>
                <div class="card-footer bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-outline-danger btn-sm me-2" id="bulk-delete" disabled>
                                    <i class="fas fa-trash me-1"></i>Delete Selected
                                </button>
                                <span class="text-muted" id="selected-count">0 selected</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <?php echo e($roles->links()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete');
        const selectedCountSpan = document.getElementById('selected-count');
        
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            roleCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
        });
        
        // Individual checkbox change
        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedCount();
                // Update select all checkbox state
                selectAllCheckbox.checked = Array.from(roleCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.indeterminate = Array.from(roleCheckboxes).some(cb => cb.checked) && !Array.from(roleCheckboxes).every(cb => cb.checked);
            });
        });
        
        // Update selected count and button state
        function updateSelectedCount() {
            const selectedCount = Array.from(roleCheckboxes).filter(cb => cb.checked).length;
            selectedCountSpan.textContent = selectedCount + ' selected';
            bulkDeleteBtn.disabled = selectedCount === 0;
        }
        
        // Bulk delete functionality
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(roleCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                return;
            }
            
            if (confirm('Are you sure you want to delete ' + selectedIds.length + ' role(s)?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo e(route('admin.roles.bulk-delete')); ?>';
                form.innerHTML = '<?php echo e(csrf_field()); ?><?php echo e(method_field("DELETE")); ?>' + selectedIds.map(id => '<input type="hidden" name="ids[]" value="' + id + '">').join('');
                document.body.appendChild(form);
                form.submit();
            }
        });
        
        // Initial count update
        updateSelectedCount();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/roles/index.blade.php ENDPATH**/ ?>