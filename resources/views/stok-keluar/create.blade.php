@extends('layouts.app')
@section('title', 'Tambah Stok Keluar')
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
        max-width: 1000px;
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
    
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group {
        flex: 1;
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 6px;
        color: var(--dark-color);
    }
    
    .form-select,
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border-radius: var(--border-radius);
        border: 1px solid #e0e0e0;
        font-size: 0.875rem;
        transition: var(--transition);
        background-color: #f8f9fa;
    }
    
    .form-select:focus,
    .form-control:focus {
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
    
    .produk-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: var(--border-radius);
        margin-top: -10px;
        margin-bottom: 20px;
        border-left: 4px solid var(--primary-color);
        display: none;
    }
    
    .produk-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .produk-info-item {
        display: flex;
        gap: 8px;
        font-size: 0.875rem;
    }
    
    .produk-info-label {
        font-weight: 600;
        min-width: 80px;
        color: var(--dark-color);
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }
    
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }
        
        .produk-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stok-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Form Tambah Stok Keluar</h2>
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
            
            <form action="{{ route('stok-keluar.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="produk_id">Produk <span style="color: red;">*</span></label>
                        <select name="produk_id" id="produk_id" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produks as $produk)
                                <option value="{{ $produk['id'] }}" {{ old('produk_id') == $produk['id'] ? 'selected' : '' }}>
                                    {{ $produk['text'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="varian_id">Varian</label>
                        <select name="varian_id" id="varian_id" class="form-select">
                            <option value="">-- Pilih Varian --</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="detail_id">Detail</label>
                        <select name="detail_id" id="detail_id" class="form-select">
                            <option value="">-- Pilih Detail --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="gudang_id">Gudang <span style="color: red;">*</span></label>
                        <select name="gudang_id" id="gudang_id" class="form-select" required>
                            <option value="">-- Pilih Gudang --</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}" {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                    {{ $gudang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="rak">Rak</label>
                        <select name="rak" id="rak" class="form-select">
                            <option value="">-- Pilih Rak --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_id">Penerima (Customer)</label>
                        <select name="customer_id" id="customer_id" class="form-select">
                            <option value="">-- Pilih Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="kuantitas">Jumlah Keluar <span style="color: red;">*</span></label>
                        <input type="number" name="kuantitas" id="kuantitas" class="form-control" min="1" required value="{{ old('kuantitas') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="2" class="form-control">{{ old('catatan') }}</textarea>
                    </div>
                </div>
                
                <div class="produk-info" id="produkInfo">
                    <div class="produk-info-grid">
                        <div class="produk-info-item">
                            <span class="produk-info-label">SKU:</span>
                            <span id="infoSku">-</span>
                        </div>
                        <div class="produk-info-item">
                            <span class="produk-info-label">Produk:</span>
                            <span id="infoProduk">-</span>
                        </div>
                        <div class="produk-info-item">
                            <span class="produk-info-label">Varian:</span>
                            <span id="infoVarian">-</span>
                        </div>
                        <div class="produk-info-item">
                            <span class="produk-info-label">Detail:</span>
                            <span id="infoDetail">-</span>
                        </div>
                        <div class="produk-info-item">
                            <span class="produk-info-label">Stok:</span>
                            <span id="infoStok">-</span>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Stok Keluar
                    </button>
                    <a href="{{ route('stok-keluar.index') }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js "></script>
<script>
    const produkData = @json($produks);
    
    function updateProductInfo() {
        const productId = $('#produk_id').val();
        const variantId = $('#varian_id').val();
        const detailId = $('#detail_id').val();
        
        if (!productId) return;
        
        const product = produkData.find(p => p.id == productId);
        if (!product) return;
        
        $('#infoSku').text(product.text.split(' - ')[0]);
        $('#infoProduk').text(product.text.split(' - ')[1]);
        
        if (variantId && product.varian) {
            const variant = product.varian.find(v => v.id == variantId);
            if (variant) {
                $('#infoVarian').text(variant.text);
                
                if (detailId && variant.detail) {
                    const detail = variant.detail.find(d => d.id == detailId);
                    if (detail) {
                        $('#infoDetail').text(detail.text);
                        $('#infoStok').text(detail.stok || '-');
                    } else {
                        $('#infoDetail').text('-');
                        $('#infoStok').text('-');
                    }
                }
            }
        }
        
        $('#produkInfo').show();
    }
    
$('#produk_id').on('change', function () {
    const productId = $(this).val();
    const varianSelect = $('#varian_id').empty().append('<option value="">-- Pilih Varian --</option>');
    $('#detail_id').empty().append('<option value="">-- Pilih Detail --</option>');

    if (!productId) {
        $('#produkInfo').hide();
        return;
    }

    const product = produkData.find(p => p.id == productId);
    if (!product) return;

    // ✅ PERBAIKAN DI SINI
    if (product.varian && Array.isArray(product.varian)) {
        product.varian.forEach(variant => {
            varianSelect.append(`<option value="${variant.id}">${variant.text}</option>`);
        });
    }

    updateProductInfo();
});

$('#varian_id').on('change', function () {
    const productId = $('#produk_id').val();
    const varianId = $(this).val();
    const detailSelect = $('#detail_id').empty().append('<option value="">-- Pilih Detail --</option>');

    if (!productId || !varianId) {
        $('#infoVarian').text('-');
        $('#infoDetail').text('-');
        $('#infoStok').text('-');
        return;
    }

    const product = produkData.find(p => p.id == productId);
    const variant = product?.varian?.find(v => v.id == varianId);

    if (variant) {
        $('#infoVarian').text(variant.text);

        if (variant.detail && Array.isArray(variant.detail)) {
            variant.detail.forEach(detailItem => {
                detailSelect.append(`<option value="${detailItem.id}">${detailItem.text}</option>`);
            });
        }
    }

    updateProductInfo();
});

    
    $('#detail_id').on('change', function () {
        updateProductInfo();
    });
    
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
    
    // Trigger initial load if there are old input values
    @if(old('produk_id'))
        $('#produk_id').trigger('change');
    @endif
    
    @if(old('varian_id'))
        $('#varian_id').trigger('change');
    @endif
    
    @if(old('gudang_id'))
        $('#gudang_id').trigger('change');
    @endif
</script>

@endsection