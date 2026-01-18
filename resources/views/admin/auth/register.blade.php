@extends('admin.layouts.auth')

@section('title', 'Register')

@section('content')
<form method="POST" action="{{ route('admin.register.post') }}">
    @csrf
    
    <div class="form-floating mb-3">
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
        <label for="name">
            <i class="fas fa-user me-1"></i>Full Name
        </label>
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    
    <div class="form-floating mb-3">
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
        <label for="email">
            <i class="fas fa-envelope me-1"></i>Email Address
        </label>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    
    <div class="form-floating mb-3">
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
    
    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
        <label for="password_confirmation">
            <i class="fas fa-lock me-1"></i>Confirm Password
        </label>
    </div>
    
    <div class="form-check mb-3">
        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" required>
        <label class="form-check-label" for="terms">
            I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a>
        </label>
        @error('terms')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="fas fa-user-plus me-2"></i>Create Account
    </button>
    
    <div class="text-center">
        <a href="{{ route('admin.login') }}" class="text-decoration-none">
            Already have an account? Sign in
        </a>
    </div>
</form>
@endsection