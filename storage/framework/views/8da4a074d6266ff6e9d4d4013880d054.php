<?php $__env->startSection('title', 'User Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">User Reports</h1>
            <p class="text-muted">Analytics and statistics about user activity</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-light rounded">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Search</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="<?php echo e($search); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" class="form-select form-select-sm">
                                <option value="">All Roles</option>
                                <option value="admin" <?php echo e($role == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <option value="user" <?php echo e($role == 'user' ? 'selected' : ''); ?>>User</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="active" <?php echo e($status == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e($status == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                <option value="pending" <?php echo e($status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="banned" <?php echo e($status == 'banned' ? 'selected' : ''); ?>>Banned</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Date Range</label>
                            <select name="date_range" class="form-select form-select-sm">
                                <option value="7" <?php echo e($dateRange == 7 ? 'selected' : ''); ?>>Last 7 days</option>
                                <option value="30" <?php echo e($dateRange == 30 ? 'selected' : ''); ?>>Last 30 days</option>
                                <option value="90" <?php echo e($dateRange == 90 ? 'selected' : ''); ?>>Last 90 days</option>
                                <option value="365" <?php echo e($dateRange == 365 ? 'selected' : ''); ?>>Last year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <i class="fas fa-filter me-1"></i> Apply Filters
                            </button>
                            <a href="<?php echo e(route('admin.reports.users')); ?>" class="btn btn-outline-secondary btn-sm px-4">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-start border-primary border-4 shadow-sm h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($userStats['total_users'])); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            $statusColors = [
                'active' => 'success',
                'inactive' => 'warning',
                'pending' => 'secondary',
                'banned' => 'danger',
            ];
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['active', 'inactive', 'pending', 'banned']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card border-start border-<?php echo e($statusColors[$statusName] ?? 'info'); ?> border-4 shadow-sm h-100 py-2">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-<?php echo e($statusColors[$statusName] ?? 'info'); ?> text-uppercase mb-1">
                                    <?php echo e(ucfirst($statusName)); ?>

                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo e(number_format($userStats['users_by_status'][$statusName] ?? 0)); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-start border-info border-4 shadow-sm h-100 py-2">
                <div class="card-body py-2">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Filtered</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($userStats['filtered_users'])); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Users by Role</h6>
                </div>
                <div class="card-body">
                    <canvas id="usersByRoleChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Registration Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="usersByMonthChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Details Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Detailed User Report</h6>
                    <span class="badge bg-primary"><?php echo e($userStats['users_list']->total()); ?> Users Found</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Joined Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $userStats['users_list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(($userStats['users_list']->currentPage() - 1) * $userStats['users_list']->perPage() + $loop->iteration); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo e($user->name); ?></div>
                                                    <div class="small text-muted"><?php echo e($user->email); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($user->role == 'admin' ? 'danger' : 'info'); ?>">
                                                <?php echo e(ucfirst($user->role)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'warning' : ($user->status == 'banned' ? 'danger' : 'secondary'))); ?>">
                                                <?php echo e(ucfirst($user->status)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php echo e($user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never'); ?>

                                        </td>
                                        <td>
                                            <?php echo e($user->created_at->format('Y-m-d')); ?>

                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-search fa-3x mb-3 d-block"></i>
                                            No users found matching your criteria.
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <?php echo e($userStats['users_list']->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Users by Role Chart
    const roleCtx = document.getElementById('usersByRoleChart').getContext('2d');
    const roleData = {
        labels: <?php echo json_encode(array_keys($userStats['users_by_role']->toArray())); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($userStats['users_by_role']->toArray())); ?>,
            backgroundColor: [
                '#0d6efd', // Primary Blue (Stronger)
                '#198754', // Success Green (Stronger)
                '#0dcaf0', // Info Cyan
                '#ffc107', // Warning Yellow
                '#dc3545', // Danger Red
                '#6c757d'  // Secondary Gray
            ],
            hoverBackgroundColor: [
                '#0a58ca', '#146c43', '#0bacbe', '#d39e00', '#a52834', '#495057'
            ],
            hoverBorderColor: "#fff",
            borderWidth: 2
        }]
    };
    
    new Chart(roleCtx, {
        type: 'doughnut',
        data: roleData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: "'Nunito', sans-serif",
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#5a5c69',
                    bodyColor: '#5a5c69',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    caretPadding: 10,
                }
            }
        }
    });

    // Users by Month Chart
    const monthCtx = document.getElementById('usersByMonthChart').getContext('2d');
    
    // Create a more solid gradient for better visibility
    const gradient = monthCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(13, 110, 253, 0.6)'); // Stronger Blue
    gradient.addColorStop(1, 'rgba(13, 110, 253, 0.05)');

    const monthData = {
        labels: <?php echo json_encode(array_keys($userStats['users_by_month']->toArray())); ?>,
        datasets: [{
            label: 'New Users',
            data: <?php echo json_encode(array_values($userStats['users_by_month']->toArray())); ?>,
            backgroundColor: gradient,
            borderColor: '#0d6efd', // Stronger Blue (Bootstrap Primary)
            borderWidth: 4, // Thicker line
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#0d6efd',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8,
            pointHoverBackgroundColor: '#0d6efd',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 3,
        }]
    };
    
    new Chart(monthCtx, {
        type: 'line',
        data: monthData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#5a5c69',
                    bodyColor: '#5a5c69',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Users: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(234, 236, 244, 1)',
                        zeroLineColor: 'rgba(234, 236, 244, 1)'
                    },
                    ticks: {
                        padding: 10,
                        color: '#858796',
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        padding: 10,
                        color: '#858796'
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/anssary/Desktop/Task/laravel-api-admin-panel/resources/views/admin/reports/users.blade.php ENDPATH**/ ?>