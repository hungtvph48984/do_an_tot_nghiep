@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-5 border-0" style="width: 100%; max-width: 480px; border-radius: 1rem;">
        <div class="text-center mb-4">
            <i class="fas fa-user-circle fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold text-primary">Đăng nhập quản trị</h3>
        </div>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <!-- Email input -->
            <div class="form-group mb-4">
                <label for="email" class="form-label">Email </label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autocomplete="email" autofocus>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password input -->
            <div class="form-group mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="current-password">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember me & forgot -->
            <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">
                        Forgot password?
                    </a>
                @endif
            </div> -->

            <!-- Submit -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
            </div>

            <!-- Social logins -->
            <div class="text-center mb-3">
                <p class="mb-2">or sign in with</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-primary rounded-circle">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger rounded-circle">
                        <i class="fab fa-google"></i>
                    </button>
                    <button type="button" class="btn btn-outline-info rounded-circle">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button type="button" class="btn btn-outline-dark rounded-circle">
                        <i class="fab fa-github"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
