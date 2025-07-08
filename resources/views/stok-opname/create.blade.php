@extends('layouts.app')
@section('title', 'Tambah Stok Opname')
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
    
    .stok-container {
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
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: var(--dark-color);
    }
    
    .form-control,
    .form-select {
        width: 100%;
        padding: 10px 15px;
        border-radius: var(--border-radius);
        border: 1px solid #e0e0e0;
        font-size: 0.875rem;
        transition: var(--transition);
        background-color: #f8f9fa;
    }
    
    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
    }
    
    .btn {
        padding: 10px 20px;
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
    
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
    
    .error-message ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
</style>

<div class="stok-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Form Tambah Stok Opname</h2>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('stok-opname.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="produk_id">Produk <span style="color: red;">*</span></label>
                    <select name="produk_id" id="produk_id" class="form-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($produks as $produk)
                            <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="varian_id">Varian</label>
                    <select name="varian_id" id="varian_id" class="form-select">
                        <option value="">-- Pilih Varian (opsional) --</option>
                        @foreach ($produks as $produk)
                            @foreach ($produk->varian as $varian)
                                <option value="{{ $varian->id }}">{{ $produk->nama_produk }} - {{ $varian->varian }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="detail_id">Detail</label>
                    <select name="detail_id" id="detail_id" class="form-select">
                        <option value="">-- Pilih Detail (opsional) --</option>
                        @foreach ($produks as $produk)
                            @foreach ($produk->varian as $varian)
                                @foreach ($varian->detail as $detail)
                                    <option value="{{ $detail->id }}">{{ $produk->nama_produk }} - {{ $varian->varian }} - {{ $detail->detail }}</option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="gudang_id">Gudang <span style="color: red;">*</span></label>
                    <select name="gudang_id" id="gudang_id" class="form-select" required>
                        <option value="">-- Pilih Gudang --</option>
                        @foreach ($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="rak">Rak</label>
                    <select name="rak" id="rak" class="form-select">
                        <option value="">-- Pilih Rak --</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="stok_sistem">Stok Sistem <span style="color: red;">*</span></label>
                    <input type="number" name="stok_sistem" id="stok_sistem" class="form-control" required min="0" value="0">
                </div>
                
                <div class="form-group">
                    <label for="stok_fisik">Stok Fisik <span style="color: red;">*</span></label>
                    <input type="number" name="stok_fisik" id="stok_fisik" class="form-control" required min="0" value="0">
                </div>
                
                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Opname
                    </button>
                    <a href="{{ route('stok-opname.index') }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js "></script>
<script>
    // Load racks based on warehouse selection
    $('#gudang_id').on('change', function () {
        const gudangId = $(this).val();
        const rakSelect = $('#rak').empty().append('<option value="">Memuat...</option>');
        
        if (!gudangId) return;
        
        $.ajax({
            url: `/api/gudang/${gudangId}/rak`,
            type: 'GET',
            success: function (data) {
                rakSelect.empty().append('<option value="">-- Pilih Rak --</option>');
                data.forEach(rak => {
                    rakSelect.append(`<option value="${rak.kode_rak}">${rak.nama_rak} (${rak.kode_rak})</option>`);
                });
            },
            error: function () {
                rakSelect.empty().append('<option value="">Gagal memuat rak</option>');
            }
        });
    });
</script>

@endsection