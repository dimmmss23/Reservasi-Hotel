@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="login-header">
    <a href="/" class="logo">D'Mas Hotel</a>
    <div class="nav-links">
        <a href="/login" class="active">Masuk</a>
        <a href="/register">Daftar</a>
    </div>
</div>

<div class="login-bg"></div>
<div class="login-overlay"></div>

<div class="container">
    <div class="row justify-content-center" style="min-height: calc(100vh - 80px); padding-top: 15vh; padding-bottom: 3rem;">
        <div class="col-md-8 col-lg-6">
            <div class="card glass-card">
                <div class="card-header">Masuk</div>

                <div class="card-body px-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                                
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" style="font-size: 0.9rem;">
                                        Lupa Kata Sandi?
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                Masuk
                            </button>
                            
                            <div class="text-center">
                                <span>Belum punya akun? </span>
                                <a href="{{ route('register') }}" class="fw-bold">
                                    Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer style="background: transparent; text-align: center; padding: 1.5rem 0; color: rgba(255,255,255,0.8); font-size: 0.9rem;">
    <p class="mb-0">&copy; {{ date('Y') }} D'Mas Hotel. Dimas Agung Subayu_23041450144.</p>
</footer>
@endsection
