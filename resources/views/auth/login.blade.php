<!-- resources/views/auth/login.blade.php -->

@extends('auth.layouts.login-layout')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Selamat Datang Kembali!</h1>
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

                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-user"
                                       id="exampleInputEmail" placeholder="Masukkan Email..." required autofocus>
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-user"
                                       id="exampleInputPassword" placeholder="Password" required>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                                    <label class="custom-control-label" for="customCheck">Ingat Saya</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-user btn-block">
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
@endsection