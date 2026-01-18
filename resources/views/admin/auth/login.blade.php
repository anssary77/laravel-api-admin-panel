@extends('admin.layouts.auth')

@section('title', 'Login')

@section('content')
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    
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
    
    <div class="form-floating mb-4">
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
        <label for="password">
            <i class="fas fa-lock me-1"></i>Password
        </label>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">
            Remember me
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In
    </button>
    
    <div class="text-center">
        <a href="#" class="text-decoration-none text-muted">
            Forgot your password?
        </a>
    </div>
</form>
@endsection