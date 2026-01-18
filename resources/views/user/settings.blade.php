@extends('user.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h2 mb-4">Settings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">General Settings</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Email Notifications</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="notify_login" checked>
                            <label class="form-check-label" for="notify_login">Notify me on new login</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="notify_updates" checked>
                            <label class="form-check-label" for="notify_updates">Product updates and newsletters</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4 mt-4">
                        <h6 class="fw-bold mb-3">Privacy Settings</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="public_profile">
                            <label class="form-check-label" for="public_profile">Make my profile public</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="show_email" checked>
                            <label class="form-check-label" for="show_email">Show my email to other users</label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card border-danger">
            <div class="card-header bg-white border-danger">
                <h5 class="card-title mb-0 text-danger">Danger Zone</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Once you delete your account, there is no going back. Please be certain.</p>
                <button type="button" class="btn btn-outline-danger btn-sm">Delete My Account</button>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="small text-muted text-uppercase fw-bold">Current Plan</label>
                    <p class="mb-0">Free Plan</p>
                </div>
                <div class="mb-3">
                    <label class="small text-muted text-uppercase fw-bold">Joined Date</label>
                    <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div class="mb-3">
                    <label class="small text-muted text-uppercase fw-bold">Account ID</label>
                    <p class="mb-0 text-monospace small">{{ $user->id }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
