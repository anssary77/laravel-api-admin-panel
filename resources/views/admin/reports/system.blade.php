@extends('admin.layouts.app')

@section('title', 'System Reports')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">System Reports</h1>
            <p class="text-muted">System information and performance metrics</p>
        </div>
    </div>

    <!-- System Information Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">PHP Version</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $systemStats['php_version'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-php fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Laravel Version</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $systemStats['laravel_version'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fab fa-laravel fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Database Driver</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ ucfirst($systemStats['database_driver']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Memory and Disk Usage Row -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Memory Usage</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Current Memory <span class="float-right">{{ $systemStats['memory_usage'] }}</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Peak Memory <span class="float-right">{{ $systemStats['memory_peak'] }}</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Disk Usage</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Free Space <span class="float-right">{{ $systemStats['disk_free'] }}</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Disk Usage <span class="float-right">{{ $systemStats['disk_usage_percent'] }}</span></h4>
                                <div class="progress mb-4">
                                    @php
                                        $usagePercent = (float) str_replace('%', '', $systemStats['disk_usage_percent']);
                                    @endphp
                                    <div class="progress-bar {{ $usagePercent > 80 ? 'bg-danger' : ($usagePercent > 60 ? 'bg-warning' : 'bg-info') }}" 
                                         role="progressbar" style="width: {{ $usagePercent }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-light shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Cache Driver</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ ucfirst($systemStats['cache_driver']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-light shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Session Driver</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ ucfirst($systemStats['session_driver']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-light shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Queue Driver</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ ucfirst($systemStats['queue_driver']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-light shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Mail Driver</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ ucfirst($systemStats['mail_driver']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Database Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Total Users</h4>
                                <div class="h2 mb-0 font-weight-bold text-primary">{{ number_format($dbStats['users_count']) }}</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Activities</h4>
                                <div class="h2 mb-0 font-weight-bold text-info">{{ number_format($dbStats['activities_count']) }}</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Roles</h4>
                                <div class="h2 mb-0 font-weight-bold text-success">{{ number_format($dbStats['roles_count']) }}</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <h4 class="small font-weight-bold">Permissions</h4>
                                <div class="h2 mb-0 font-weight-bold text-warning">{{ number_format($dbStats['permissions_count']) }}</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h4 class="small font-weight-bold">Database Size: <span class="text-primary">{{ $dbStats['database_size'] }}</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Server Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Server Software:</strong></td>
                                    <td>{{ $systemStats['server_software'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>PHP Version:</strong></td>
                                    <td>{{ $systemStats['php_version'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Laravel Version:</strong></td>
                                    <td>{{ $systemStats['laravel_version'] }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Cache Driver:</strong></td>
                                    <td>{{ ucfirst($systemStats['cache_driver']) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Session Driver:</strong></td>
                                    <td>{{ ucfirst($systemStats['session_driver']) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Queue Driver:</strong></td>
                                    <td>{{ ucfirst($systemStats['queue_driver']) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection