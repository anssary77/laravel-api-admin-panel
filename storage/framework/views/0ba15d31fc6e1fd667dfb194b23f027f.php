<?php $__env->startSection('title', 'Posts'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Posts Management</h1>
            <a href="<?php echo e(route('admin.posts.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Post
            </a>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('admin.posts.index')); ?>" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="title" class="form-control" placeholder="Search posts..." value="<?php echo e(request('title')); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="user_id" class="form-select">
                            <option value="">All Users</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>>Published</option>
                            <option value="archived" <?php echo e(request('status') == 'archived' ? 'selected' : ''); ?>>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="others_only" value="1" id="othersOnly" <?php echo e(request('others_only') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="othersOnly">Others' Posts</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Posts Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Contact Phone</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration); ?></td>
                            <td><?php echo e($post->title); ?></td>
                            <td><?php echo e(Str::limit($post->description, 512)); ?></td>
                            <td><?php echo e($post->contact_phone_number); ?></td>
                            <td><?php echo e($post->user->name ?? 'N/A'); ?></td>
                            <td><?php echo e(ucfirst($post->status)); ?></td>
                            <td><?php echo e($post->created_at->format('Y-m-d')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.posts.show', $post)); ?>" class="btn btn-sm btn-info">View</a>
                                <a href="<?php echo e(route('admin.posts.edit', [$post, 'page' => request('page')])); ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?php echo e(route('admin.posts.destroy', $post)); ?>" method="POST" style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-5">
                    <?php echo e($posts->withQueryString()->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/posts/index.blade.php ENDPATH**/ ?>