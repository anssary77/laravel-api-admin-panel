@extends('admin.layouts.app')

@section('title', 'Activity Reports')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Activity Reports</h1>
            <p class="text-muted">System activity and user engagement analytics</p>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <label class="mr-2">Date Range:</label>
                        <select name="date_range" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                            <option value="7" {{ $dateRange == 7 ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ $dateRange == 30 ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ $dateRange == 90 ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ $dateRange == 365 ? 'selected' : '' }}>Last year</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Activities</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($activityStats['total_activities']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Recent Activities ({{ $dateRange }}d)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($activityStats['recent_activities']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Daily Average</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dateRange > 0 ? round($activityStats['recent_activities'] / $dateRange, 1) : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Most Active User</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                @if($activityStats['activities_by_user']->isNotEmpty())
                                    {{ $activityStats['activities_by_user']->first()->name ?: 'User #' . $activityStats['activities_by_user']->first()->causer_id }}
                                    <small class="text-muted">({{ $activityStats['activities_by_user']->first()->total }} activities)</small>
                                @else
                                    <span class="text-muted">No data</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
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
                    <h6 class="m-0 font-weight-bold text-primary">Activities by Type</h6>
                </div>
                <div class="card-body">
                    <canvas id="activitiesByTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Activity Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="activitiesByDayChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Users Table -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Most Active Users</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Activities</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityStats['activities_by_user']->take(10) as $user)
                                    <tr>
                                        <td>{{ $user->name ?: 'User #' . $user->causer_id }}</td>
                                        <td>{{ number_format($user->total) }}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $activityStats['recent_activities'] > 0 ? ($user->total / $activityStats['recent_activities']) * 100 : 0 }}%">
                                                    {{ $activityStats['recent_activities'] > 0 ? round(($user->total / $activityStats['recent_activities']) * 100, 1) : 0 }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No user activity data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities->take(15) as $activity)
                                    <tr>
                                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                                        <td>{{ $activity->causer ? $activity->causer->name : 'System' }}</td>
                                        <td>{{ ucfirst($activity->description) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No recent activities</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
    // Activities by Type Chart
    const typeCtx = document.getElementById('activitiesByTypeChart').getContext('2d');
    const typeData = {
        labels: {!! json_encode(array_keys($activityStats['activities_by_type']->toArray())) !!},
        datasets: [{
            data: {!! json_encode(array_values($activityStats['activities_by_type']->toArray())) !!},
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796',
                '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
            ],
            borderWidth: 0
        }]
    };
    
    new Chart(typeCtx, {
        type: 'doughnut',
        data: typeData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Activities by Day Chart
    const dayCtx = document.getElementById('activitiesByDayChart').getContext('2d');
    const dayData = {
        labels: {!! json_encode(array_keys($activityStats['activities_by_day']->toArray())) !!},
        datasets: [{
            label: 'Activities',
            data: {!! json_encode(array_values($activityStats['activities_by_day']->toArray())) !!},
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };
    
    new Chart(dayCtx, {
        type: 'line',
        data: dayData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush