@extends('layouts.app')
@section('title', 'Edit Rak')
@section('content')

<style>
    :root {
        --primary-color: #5C48EE;
        --primary-light: #eef0ff;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-radius: 8px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .rak-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 15px;
    }

    .card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        overflow: hidden;
    }

    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        background-color: white;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .card-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 8px;
        display: block;
        color: var(--secondary-color);
    }

    input, textarea, select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        box-sizing: border-box;
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
    }

    .form-actions {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }

    .btn {
        padding: 12px 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #4a3ac4;
        transform: translateY(-2px);
    }

    .btn-outline {
        border: 1px solid var(--primary-color);
        background-color: white;
        color: var(--primary-color);
    }

    .btn-outline:hover {
        background-color: var(--primary-light);
    }

    .text-danger {
        font-size: 0.75rem;
        color: var(--danger-color);
        margin-top: 5px;
    }

    .gudang-info {
        font-size: 0.875rem;
        color: var(--secondary-color);
        margin-bottom: 20px;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
    }

    .gudang-info strong {
        color: var(--dark-color);
    }
</style>

<div class="rak-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Edit Rak</h2>
        </div>
        
        <div class="card-body">
            <div class="gudang-info">
                Untuk Gudang: <strong>{{ $gudang->nama }}</strong>
            </div>

            <form action="{{ route('gudang.rak.update', ['gudangId' => $gudang->id, 'rakId' => $rak->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="kode_rak">Kode Rak</label>
                    <input type="text" name="kode_rak" id="kode_rak" value="{{ old('kode_rak', $rak->kode_rak) }}" required>
                    @error('kode_rak')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_rak">Nama Rak</label>
                    <input type="text" name="nama_rak" id="nama_rak" value="{{ old('nama_rak', $rak->nama_rak) }}" required>
                    @error('nama_rak')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3">{{ old('deskripsi', $rak->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kapasitas">Kapasitas (opsional)</label>
                    <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', $rak->kapasitas) }}">
                    @error('kapasitas')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Rak
                    </button>
                    <a href="{{ route('gudang.show', $gudang->id) }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection