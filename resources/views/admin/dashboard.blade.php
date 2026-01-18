@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    .stat-card-modern {
        border-radius: 15px;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .stat-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .quick-action-btn {
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
        background: white;
        border: 1px solid #eee;
        color: #333;
        text-decoration: none;
        display: block;
    }
    .quick-action-btn:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
        color: #0d6efd;
        transform: scale(1.05);
    }
    .activity-item {
        border-left: 2px solid #e9ecef;
        padding-left: 20px;
        position: relative;
        padding-bottom: 20px;
    }
    .activity-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #0d6efd;
    }
    .activity-item:last-child {
        padding-bottom: 0;
    }
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner shadow-sm">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="lead mb-0">Here's a quick look at your system performance today.</p>
        </div>
        <div class="col-md-4 text-md-end d-none d-md-block">
            <div class="h4 mb-0"><i class="far fa-calendar-alt me-2"></i>{{ now()->format('Y-m-d') }}</div>
            <div class="small opacity-75" id="real-time-clock"></div>
        </div>
    </div>
</div>

<!-- Main Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card-modern h-100">
            <div class="card-body">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="fas fa-users"></i>
                </div>
                <h6 class="text-muted mb-1">Total Users</h6>
                <h2 class="fw-bold mb-0">{{ number_format($stats['total_users']) }}</h2>
                <div class="mt-2 small">
                    <span class="text-success"><i class="fas fa-user-check me-1"></i>{{ $stats['active_users'] }} Active</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card-modern h-100">
            <div class="card-body">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h6 class="text-muted mb-1">Total Posts</h6>
                <h2 class="fw-bold mb-0">{{ number_format($stats['total_posts']) }}</h2>
                <div class="mt-2 small">
                    <span class="text-info"><i class="fas fa-paper-plane me-1"></i>{{ $postStats['published'] }} Published</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card-modern h-100">
            <div class="card-body">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h6 class="text-muted mb-1">Daily Activity</h6>
                <h2 class="fw-bold mb-0">{{ number_format($stats['today_activity']) }}</h2>
                <div class="mt-2 small">
                    <span class="text-muted">Last 24 hours</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card-modern h-100">
            <div class="card-body">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h6 class="text-muted mb-1">Roles & Permissions</h6>
                <h2 class="fw-bold mb-0">{{ $stats['total_roles'] }} / {{ $stats['total_permissions'] }}</h2>
                <div class="mt-2 small">
                    <span class="text-muted">Access system secured</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Charts -->
<div class="row mb-4">
    <!-- User Growth Chart -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-area text-primary me-2"></i>User Growth (Last 30 Days)</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="userGrowthChart" data-chart-data="{{ json_encode($userGrowth) }}"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-bolt text-warning me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('admin.posts.create') }}" class="quick-action-btn">
                            <i class="fas fa-plus-circle d-block mb-2 fa-lg"></i>
                            <span>New Post</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.users.create') }}" class="quick-action-btn">
                            <i class="fas fa-user-plus d-block mb-2 fa-lg"></i>
                            <span>Add User</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.settings.index') }}" class="quick-action-btn">
                            <i class="fas fa-cogs d-block mb-2 fa-lg"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.file-manager.index') }}" class="quick-action-btn">
                            <i class="fas fa-folder-open d-block mb-2 fa-lg"></i>
                            <span>File Manager</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.reports.index') }}" class="quick-action-btn">
                            <i class="fas fa-file-invoice d-block mb-2 fa-lg"></i>
                            <span>Reports</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.activity-logs.index') }}" class="quick-action-btn">
                            <i class="fas fa-history d-block mb-2 fa-lg"></i>
                            <span>Logs</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Recent Posts Table -->
    <div class="col-lg-7 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-newspaper text-success me-2"></i>Recent Posts</h5>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Title</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th class="pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPosts as $post)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ Str::limit($post->title, 45) }}</div>
                                </td>
                                <td>{{ $post->user->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}-subtle text-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 small text-muted">{{ $post->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No posts found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-stream text-primary me-2"></i>Recent Activities</h5>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-light">Logs</a>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($recentActivity as $activity)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-bold small">{{ $activity->causer->name ?? 'System' }}</span>
                            <span class="text-muted smaller">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mb-0 small text-secondary">{{ $activity->description }}</p>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">No activities recorded</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="row align-items-center text-center text-md-start">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                            <i class="fas fa-server fa-2x text-secondary opacity-50 me-3"></i>
                            <div>
                                <div class="small text-muted">Server</div>
                                <div class="fw-bold">PHP {{ PHP_VERSION }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0 border-start-md">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start ps-md-4">
                            <i class="fas fa-database fa-2x text-secondary opacity-50 me-3"></i>
                            <div>
                                <div class="small text-muted">Database</div>
                                <div class="fw-bold text-success"><i class="fas fa-circle me-1 small"></i> Connected</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0 border-start-md">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start ps-md-4">
                            <i class="fas fa-microchip fa-2x text-secondary opacity-50 me-3"></i>
                            <div>
                                <div class="small text-muted">Memory Usage</div>
                                <div class="fw-bold">{{ $systemHealth['memory_usage'] ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 border-start-md">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start ps-md-4">
                            <i class="fas fa-hdd fa-2x text-secondary opacity-50 me-3"></i>
                            <div>
                                <div class="small text-muted">Disk Free</div>
                                <div class="fw-bold">{{ $systemHealth['disk_free'] ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Real-time clock
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('real-time-clock').textContent = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // User Growth Chart
    const chartElement = document.getElementById('userGrowthChart');
    const ctx = chartElement.getContext('2d');
    const userGrowthData = JSON.parse(chartElement.getAttribute('data-chart-data'));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: userGrowthData.labels,
            datasets: [{
                label: 'New Users',
                data: userGrowthData.data,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#0d6efd',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    padding: 12,
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        stepSize: 1,
                        font: { size: 12 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 12 }
                    }
                }
            }
        }
    });
</script>
@endpush
