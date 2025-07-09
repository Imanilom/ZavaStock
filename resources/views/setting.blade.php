@extends('layouts.app')
@section('title', 'Pengaturan Akun')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-warning text-white">
            <h6 class="m-0 font-weight-bold">Pengaturan Akun</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="email">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="password">Kata Sandi Baru <small class="text-muted">(Opsional)</small></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Isi jika ingin mengubah password">
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
