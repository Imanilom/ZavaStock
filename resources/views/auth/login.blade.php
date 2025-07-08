<!-- resources/views/auth/login.blade.php -->
@extends('auth.layouts.login-layout')

@section('title', 'Login')

@section('content')
<style>
    body {
        background-color: #5954eb;
        background-image: linear-gradient(180deg, #4e73df 10%, #4743ba 100%);
        background-size: cover;
        height: 100vh;
        display: flex;
        align-items: center;
    }
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        min-height: 100vh;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .form-control {
        padding: 10px 15px;
        border-radius: 5px;
    }
    .btn-login {
        background-color: #5954eb;
        border: none;
        padding: 10px;
        font-weight: 600;
    }
    .logo-container {
        position: absolute;
        top: 20px;
        left: 20px;
    }
</style>

<!-- Logo in top left corner -->
<div class="logo-container">
    <img src="{{ asset('images/assets/logo.jpg') }}" alt="ZavaStock." style="max-width: 200px; height: auto;">
</div>

<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-8">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Selamat Datang Kembali!</h1>
                                <p class="mb-4">Masukkan email dan password</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control form-control-user"
                                           id="exampleInputEmail" placeholder="Masukkan Email..." required autofocus>
                                </div>

                                <div class="form-group mb-3">
                                    <input type="password" name="password" class="form-control form-control-user"
                                           id="exampleInputPassword" placeholder="Password" required>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="customCheck" name="remember">
                                        <label class="form-check-label" for="customCheck">Ingat Saya</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block btn-login">
                                    Login
                                </button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <a class="small" href="#">Lupa Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection