@extends('admin.layouts.app')

@section('title', 'Activity Log Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Activity Log Details</h1>
            <div>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
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
                        <td><span class="badge bg-primary">{{ ucfirst($activityLog->log_name) }}</span></td>
                    </tr>
                    <tr>
                        <th class="ps-0">Event:</th>
                        <td>{{ ucfirst($activityLog->event) }}</td>
                    </tr>
                    <tr>
                        <th class="ps-0">Description:</th>
                        <td>{{ $activityLog->description }}</td>
                    </tr>
                    <tr>
                        <th class="ps-0">Time:</th>
                        <td>
                            @if($activityLog->created_at)
                                {{ $activityLog->created_at->format('Y-m-d H:i:s') }}<br>
                                <small class="text-muted">{{ $activityLog->created_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
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
                @if($activityLog->causer)
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-user-circle fa-2x me-3 text-muted"></i>
                        <div>
                            <div class="fw-bold">{{ $activityLog->causer->name }}</div>
                            <div class="text-muted small">{{ $activityLog->causer->email }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.show', $activityLog->causer_id) }}" class="btn btn-sm btn-outline-primary">View User Profile</a>
                @else
                    <div class="text-muted">System / Unknown</div>
                @endif
            </div>
        </div>

        @if($activityLog->subject)
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
                            <td><code>{{ class_basename($activityLog->subject_type) }}</code></td>
                        </tr>
                        <tr>
                            <th class="ps-0">ID:</th>
                            <td>{{ $activityLog->subject_id }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-code me-2"></i>Activity Properties
                </h5>
            </div>
            <div class="card-body">
                @if(!empty($activityLog->properties) && count($activityLog->properties) > 0)
                    @php
                        $properties = $activityLog->properties;
                        $attributes = $properties['attributes'] ?? [];
                        $old = $properties['old'] ?? [];
                        $displayData = count($attributes) > 0 ? $attributes : $old;
                    @endphp

                    @if(count($displayData) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Field</th>
                                        @if(count($old) > 0 && count($attributes) > 0)
                                            <th>Old Value</th>
                                            <th>New Value</th>
                                        @elseif(count($old) > 0)
                                            <th>Value (Old)</th>
                                        @else
                                            <th>Value</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($displayData as $key => $value)
                                        <tr>
                                            <td class="fw-bold text-primary">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                            @if(count($old) > 0 && count($attributes) > 0)
                                                <td class="text-danger">
                                                    @if(is_array($old[$key] ?? null))
                                                        <pre class="mb-0 small"><code>{{ json_encode($old[$key], JSON_PRETTY_PRINT) }}</code></pre>
                                                    @else
                                                        {{ $old[$key] ?? 'N/A' }}
                                                    @endif
                                                </td>
                                                <td class="text-success">
                                                    @if(is_array($value))
                                                        <pre class="mb-0 small"><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            @elseif(count($old) > 0)
                                                <td class="text-danger">
                                                    @if(is_array($value))
                                                        <pre class="mb-0 small"><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            @else
                                                <td class="text-success">
                                                    @if(is_array($value))
                                                        <pre class="mb-0 small"><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <pre class="bg-light p-4 rounded"><code>{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</code></pre>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-ghost fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No additional properties recorded for this activity.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
