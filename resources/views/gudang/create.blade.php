@extends('layouts.app')
@section('title', 'Tambah Gudang Baru')
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

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--primary-light);
        margin: 0;
        padding: 0;
    }

    .main-container {
        padding: 20px;
        margin-left: 250px; /* Adjust based on your sidebar width */
        width: calc(100% - 250px); /* Adjust based on your sidebar width */
        box-sizing: border-box;
    }

    .card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--dark-color);
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: var(--border-radius);
        font-size: 14px;
        box-sizing: border-box;
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .btn {
        padding: 12px 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
        font-size: 14px;
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

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-color);
    }

    .checkbox-group label {
        margin: 0;
        font-size: 14px;
    }

    .required-field::after {
        content: " *";
        color: var(--danger-color);
    }

    @media (max-width: 992px) {
        .main-container {
            margin-left: 0;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Form Tambah Gudang</h2>
        </div>
        
        <div class="card-body">
            <form action="{{ route('gudang.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode" class="required-field">Kode Gudang</label>
                            <input type="text" name="kode" id="kode" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nama" class="required-field">Nama Gudang</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="alamat" class="required-field">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="telepon" class="required-field">Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>

                       <div class="form-group">
                            <label for="user_id" class="required-field">Penanggung Jawab</label>
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
                            <label for="jenis" class="required-field">Jenis Gudang</label>
                            <select name="jenis" id="jenis" class="form-select" required>
                                <option value="utama">Utama</option>
                                <option value="cabang">Cabang</option>
                                <option value="retur">Retur</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" name="aktif" id="aktif" checked>
                                <label for="aktif">Status Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('gudang.index') }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Gudang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection