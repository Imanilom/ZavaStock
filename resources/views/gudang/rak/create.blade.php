@extends('layouts.app')
@section('title', 'Tambah Rak')
@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #eef0ff;
        margin: 0;
        padding: 30px 20px;
    }

    .main-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-top: 40px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 6px;
        display: block;
        color: #555;
    }

    input, textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 13px;
        box-sizing: border-box;
    }

    .form-actions {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        display: inline-block;
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

    .text-danger {
        font-size: 12px;
        color: #dc3545;
        margin-top: 5px;
    }
</style>

<div class="main-container">
    <div class="card">
        <h2 style="margin-bottom: 25px;">Tambah Rak untuk Gudang: <strong>{{ $gudang->nama }}</strong></h2>

        <form action="{{ route('gudang.rak.store', $gudang->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="kode_rak">Kode Rak</label>
                <input type="text" name="kode_rak" id="kode_rak" value="{{ old('kode_rak') }}">
                @error('kode_rak')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama_rak">Nama Rak</label>
                <input type="text" name="nama_rak" id="nama_rak" value="{{ old('nama_rak') }}">
                @error('nama_rak')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="kapasitas">Kapasitas (opsional)</label>
                <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}">
                @error('kapasitas')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Rak</button>
                <a href="{{ route('gudang.show', $gudang->id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
