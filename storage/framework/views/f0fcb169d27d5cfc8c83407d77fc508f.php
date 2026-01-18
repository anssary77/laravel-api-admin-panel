<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Settings</h1>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-warning" onclick="confirm('Are you sure you want to clear the cache?') && document.getElementById('clear-cache-form').submit();">
                    <i class="fas fa-broom me-1"></i>Clear Cache
                </button>
                <form id="clear-cache-form" method="POST" action="<?php echo e(route('admin.settings.clear-cache')); ?>" class="d-none">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="settingsSearch" class="form-control border-start-0 ps-0" placeholder="Search settings...">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Settings Groups
                </h5>
            </div>
            <div class="list-group list-group-flush">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.settings.index', ['group' => $groupName])); ?>" 
                       class="list-group-item list-group-item-action <?php echo e(request('group', 'general') == $groupName ? 'active' : ''); ?>">
                        <i class="fas fa-<?php echo e($groupName == 'general' ? 'cog' : ($groupName == 'api' ? 'code' : ($groupName == 'email' ? 'envelope' : 'shield'))); ?> me-2"></i>
                        <?php echo e(ucfirst($groupName)); ?>

                        <span class="badge bg-secondary float-end"><?php echo e($settings->where('group', $groupName)->count()); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-download me-2"></i>Backup & Restore
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('backup-form').submit();">
                        <i class="fas fa-download me-1"></i>Backup Settings
                    </button>
                    <form id="backup-form" method="POST" action="<?php echo e(route('admin.settings.backup')); ?>" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>
                    
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#restoreModal">
                        <i class="fas fa-upload me-1"></i>Restore Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-<?php echo e(request('group', 'general') == 'general' ? 'cog' : (request('group') == 'api' ? 'code' : (request('group') == 'email' ? 'envelope' : 'shield'))); ?> me-2"></i>
                    <?php echo e(ucfirst(request('group', 'general'))); ?> Settings
                </h5>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('group') == 'email'): ?>
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                        <i class="fas fa-paper-plane me-1"></i>Test Connection
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.settings.group.update', request('group', 'general'))); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $settings->where('group', request('group', 'general')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row mb-3 align-items-center setting-row">
                            <div class="col-md-4">
                                <label for="<?php echo e($setting->key); ?>" class="form-label fw-semibold">
                                    <?php echo e(ucwords(str_replace('_', ' ', $setting->key))); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->is_required): ?>
                                        <span class="text-danger">*</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->description): ?>
                                    <small class="text-muted d-block"><?php echo e($setting->description); ?></small>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($setting->type):
                                    case ('boolean'): ?>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" 
                                                   <?php echo e(old($setting->key, $setting->value) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="<?php echo e($setting->key); ?>">
                                                <?php echo e($setting->value ? 'Enabled' : 'Disabled'); ?>

                                            </label>
                                        </div>
                                        <?php break; ?>
                                    
                                    <?php case ('number'): ?>
                                        <input type="number" class="form-control <?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" 
                                               value="<?php echo e(old($setting->key, $setting->value)); ?>"
                                               <?php echo e($setting->is_required ? 'required' : ''); ?>>
                                        <?php break; ?>
                                    
                                    <?php case ('textarea'): ?>
                                        <textarea class="form-control <?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                  id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" 
                                                  rows="3"><?php echo e(old($setting->key, $setting->value)); ?></textarea>
                                        <?php break; ?>
                                    
                                    <?php case ('select'): ?>
                                        <select class="form-select <?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>"
                                                <?php echo e($setting->is_required ? 'required' : ''); ?>>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = json_decode($setting->options, true) ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($option['value']); ?>" <?php echo e(old($setting->key, $setting->value) == $option['value'] ? 'selected' : ''); ?>>
                                                    <?php echo e($option['label']); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                        <?php break; ?>
                                    
                                    <?php default: ?>
                                        <input type="text" class="form-control <?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" 
                                               value="<?php echo e(old($setting->key, $setting->value)); ?>"
                                               <?php echo e($setting->is_required ? 'required' : ''); ?>>
                                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$setting->key];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                            <hr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Restore Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>Restore Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.restore')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="backup_file" class="form-label">Backup File</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['backup_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="backup_file" name="backup_file" accept=".json" required>
                        <small class="text-muted">Select a JSON backup file to restore settings from.</small>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['backup_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This will overwrite all current settings with the backup data.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Restore Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('settingsSearch');
        const settingRows = document.querySelectorAll('.setting-row');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            settingRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Test Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.test-email')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Enter an email address to send a test message using your current SMTP settings.</p>
                    <div class="mb-3">
                        <label for="test_email" class="form-label">Recipient Email</label>
                        <input type="email" class="form-control" id="test_email" name="test_email" placeholder="example@domain.com" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Send Test
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>