@extends('admin.layouts.app')

@section('title', 'Reports Overview')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Reports Overview</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Date Range Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="date_range" class="form-label">Date Range</label>
                        <select name="date_range" id="date_range" class="form-select" onchange="this.form.submit()">
                            <option value="7" {{ $dateRange == 7 ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ $dateRange == 30 ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ $dateRange == 90 ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ $dateRange == 365 ? 'selected' : '' }}>Last year</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Overview Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">Total Users</h6>
                        <h2 class="mb-0">{{ number_format($overviewStats['total_users']) }}</h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">New Users ({{ $dateRange }}d)</h6>
                        <h2 class="mb-0">{{ number_format($overviewStats['new_users']) }}</h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-user-plus fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">Total Activities</h6>
                        <h2 class="mb-0">{{ number_format($overviewStats['total_activities']) }}</h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-history fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-0" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2">Recent Activities ({{ $dateRange }}d)</h6>
                        <h2 class="mb-0">{{ number_format($overviewStats['recent_activities']) }}</h2>
                    </div>
                    <div class="text-white-50">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-user-chart text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">User Reports</h5>
                        <p class="text-muted mb-0">Detailed user statistics and analytics</p>
                    </div>
                </div>
                <p class="card-text">View user registration trends, role distributions, and user activity patterns.</p>
                <a href="{{ route('admin.reports.users') }}" class="btn btn-primary">View Users Report</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-history text-info fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Activity Reports</h5>
                        <p class="text-muted mb-0">System activity and audit logs</p>
                    </div>
                </div>
                <p class="card-text">Monitor system activities, user actions, and audit trail analysis.</p>
                <a href="{{ route('admin.reports.activity') }}" class="btn btn-info">View Activity Report</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                        <i class="fas fa-server text-success fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">System Reports</h5>
                        <p class="text-muted mb-0">System performance and health metrics</p>
                    </div>
                </div>
                <p class="card-text">Check system performance, resource usage, and technical statistics.</p>
                <a href="{{ route('admin.reports.system') }}" class="btn btn-success">View System Report</a>
            </div>
        </div>
    </div>
</div>
@endsection