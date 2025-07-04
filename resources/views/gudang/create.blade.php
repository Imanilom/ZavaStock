@extends('layouts.app')
@section('title', 'Tambah Gudang Baru')
@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #eef0ff;
        margin: 0;
        padding: 30px 20px;
    }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .card {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        width: 100%;
        box-sizing: border-box;
        margin-top: 40px;
    }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header-row h1 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
        box-sizing: border-box;
    }

    .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
        box-sizing: border-box;
        background-color: white;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background-color: #5C48EE;
        color: white;
    }

    .btn-outline {
        background-color: white;
        color: #5C48EE;
        border: 1px solid #5C48EE;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col-md-6 {
        width: 50%;
        padding: 0 10px;
        box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Form Tambah Gudang</h1>
        </div>

        <form action="{{ route('gudang.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kode">Kode Gudang <span class="text-danger">*</span></label>
                        <input type="text" name="kode" id="kode" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Gudang <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="telepon">Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" id="telepon" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>

                   <div class="form-group">
                        <label for="user_id">Penanggung Jawab <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis">Jenis Gudang <span class="text-danger">*</span></label>
                        <select name="jenis" id="jenis" class="form-select" required>
                            <option value="utama">Utama</option>
                            <option value="cabang">Cabang</option>
                            <option value="retur">Retur</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="aktif">Status</label>
                        <div>
                            <input type="checkbox" name="aktif" id="aktif" checked>
                            <label for="aktif">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group text-right mt-4">
                <button type="submit" class="btn btn-primary">Simpan Gudang</button>
                <a href="{{ route('gudang.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection