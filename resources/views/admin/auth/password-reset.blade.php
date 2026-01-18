@extends('admin.layouts.auth')

@section('title', 'Reset Password')

@section('content')
<form method="POST" action="{{ route('admin.password.reset') }}">
    @csrf
    
    <div class="text-center mb-4">
        <p class="text-muted">Enter your email address and we'll send you a link to reset your password.</p>
    </div>
    
    <div class="form-floating mb-4">
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
        <label for="email">
            <i class="fas fa-envelope me-1"></i>Email Address
        </label>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="fas fa-paper-plane me-2"></i>Send Reset Link
    </button>
    
    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="text-decoration-none">
            Back to Sign In
        </a>
    </div>
</form>
@endsection