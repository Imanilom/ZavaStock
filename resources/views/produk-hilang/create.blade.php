@extends('layouts.app')
@section('title', 'Tambah Laporan Produk Hilang')
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
        --border-radius: 12px;
        --box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .main-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        margin-top: 20px;
        border: none;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .card-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--dark-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: var(--primary-color);
    }

    .form-section {
        margin-bottom: 30px;
        background-color: #f9faff;
        padding: 25px;
        border-radius: var(--border-radius);
        border-left: 4px solid var(--primary-color);
    }

    .form-section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        display: block;
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 10px;
        color: var(--dark-color);
    }

    .form-label.required:after {
        content: " *";
        color: var(--danger-color);
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: var(--border-radius);
        font-size: 15px;
        transition: var(--transition);
        background-color: #fff;
        height: 46px;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(92, 72, 238, 0.2);
        outline: none;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
        padding: 15px;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 12px;
    }

    .error-message {
        color: var(--danger-color);
        font-size: 13px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .error-message i {
        font-size: 14px;
    }

    .error-container {
        background-color: #fff5f5;
        border: 1px solid #fed7d7;
        color: var(--danger-color);
        padding: 18px;
        border-radius: var(--border-radius);
        margin-bottom: 25px;
        font-size: 15px;
    }

    .error-container strong {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .error-container ul {
        margin: 10px 0 0 25px;
        padding: 0;
    }

    .btn {
        padding: 12px 24px;
        border-radius: var(--border-radius);
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 46px;
    }

    .btn i {
        font-size: 14px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #4a3ac4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(92, 72, 238, 0.2);
    }

    .btn-outline {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .btn-outline:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }

    .stok-info {
        font-size: 14px;
        color: var(--secondary-color);
        margin-top: 8px;
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stok-value {
        font-weight: 600;
        color: var(--success-color);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -12px;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 12px;
    }

    @media (max-width: 768px) {
        .main-container {
            padding: 0 15px;
        }
        
        .card {
            padding: 25px 20px;
        }
        
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .form-section {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }

    /* Animation for form sections */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-section {
        animation: fadeIn 0.4s ease-out forwards;
    }

    .form-section:nth-child(2) {
        animation-delay: 0.1s;
    }

    /* Hover effects for form controls */
    .form-control:hover {
        border-color: #b3b3b3;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-exclamation-triangle"></i>
                Tambah Laporan Produk Hilang
            </h1>
        </div>

        @if ($errors->any())
            <div class="error-container">
                <strong>
                    <i class="fas fa-exclamation-circle"></i>
                    Terjadi kesalahan:
                </strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk-hilang.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-box-open"></i>
                    Informasi Produk
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="produk_id" class="form-label required">Produk</label>
                            <select name="produk_id" id="produk_id" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk['id'] }}" 
                                        {{ old('produk_id') == $produk['id'] ? 'selected' : '' }}>
                                        {{ $produk['text'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('produk_id')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan_id" class="form-label required">Keterangan</label>
                            <select name="keterangan_id" id="keterangan_id" class="form-control" required>
                                <option value="">-- Pilih Keterangan --</option>
                                @foreach ($keterangans as $keterangan)
                                    <option value="{{ $keterangan->id }}" {{ old('keterangan_id') == $keterangan->id ? 'selected' : '' }}>
                                        {{ $keterangan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('keterangan_id')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="varian_id" class="form-label">Varian</label>
                            <select name="varian_id" id="varian_id" class="form-control">
                                <option value="">-- Pilih Varian --</option>
                                @if(old('produk_id'))
                                    @php
                                        $selectedProduk = collect($produks)->firstWhere('id', old('produk_id'));
                                    @endphp
                                    @if($selectedProduk && $selectedProduk['varian'])
                                        @foreach($selectedProduk['varian'] as $varian)
                                            <option value="{{ $varian['id'] }}" {{ old('varian_id') == $varian['id'] ? 'selected' : '' }}>
                                                {{ $varian['text'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                            @error('varian_id')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="detail_id" class="form-label">Detail</label>
                            <select name="detail_id" id="detail_id" class="form-control">
                                <option value="">-- Pilih Detail --</option>
                                @if(old('varian_id') && old('produk_id'))
                                    @php
                                        $selectedProduk = collect($produks)->firstWhere('id', old('produk_id'));
                                        $selectedVarian = collect($selectedProduk['varian'] ?? [])->firstWhere('id', old('varian_id'));
                                    @endphp
                                    @if($selectedVarian && $selectedVarian['detail'])
                                        @foreach($selectedVarian['detail'] as $detail)
                                            <option value="{{ $detail['id'] }}" {{ old('detail_id') == $detail['id'] ? 'selected' : '' }}>
                                                {{ $detail['text'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                            <div id="stok-info" class="stok-info" style="{{ old('detail_id') ? '' : 'display: none;' }}">
                                <i class="fas fa-cubes"></i>
                                Stok tersedia: <span id="stok-value" class="stok-value">
                                    @if(old('detail_id') && old('varian_id') && old('produk_id'))
                                        @php
                                            $selectedProduk = collect($produks)->firstWhere('id', old('produk_id'));
                                            $selectedVarian = collect($selectedProduk['varian'] ?? [])->firstWhere('id', old('varian_id'));
                                            $selectedDetail = collect($selectedVarian['detail'] ?? [])->firstWhere('id', old('detail_id'));
                                        @endphp
                                        {{ $selectedDetail['stok'] ?? 0 }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </div>
                            @error('detail_id')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-clipboard-list"></i>
                    Detail Laporan
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jumlah_hilang" class="form-label required">Jumlah Hilang</label>
                            <input type="number" min="1" name="jumlah_hilang" id="jumlah_hilang" class="form-control" 
                                   value="{{ old('jumlah_hilang') }}" required>
                            @error('jumlah_hilang')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_kejadian" class="form-label required">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" id="tanggal_kejadian" class="form-control" 
                                   value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('tanggal_kejadian')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="catatan_tambahan" class="form-label">Catatan Tambahan</label>
                    <textarea name="catatan_tambahan" id="catatan_tambahan" class="form-control" rows="4">{{ old('catatan_tambahan') }}</textarea>
                    @error('catatan_tambahan')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('produk-hilang.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Data produk dari controller
const produkData = @json($produks);

$(document).ready(function() {
    // Saat produk dipilih, load varian
    $('#produk_id').on('change', function() {
        const produkId = $(this).val();
        const varianSelect = $('#varian_id');
        
        // Reset detail
        $('#detail_id').empty().append('<option value="">-- Pilih Detail --</option>');
        $('#stok-info').hide();

        // Isi varian jika ada
        if (produkId) {
            const selectedProduk = produkData.find(p => p.id == produkId);
            
            if (selectedProduk && selectedProduk.varian && selectedProduk.varian.length) {
                varianSelect.empty();
                varianSelect.append('<option value="">-- Pilih Varian --</option>');
                
                selectedProduk.varian.forEach(function(v) {
                    varianSelect.append(new Option(v.text, v.id));
                });
            } else {
                varianSelect.empty();
                varianSelect.append('<option value="">-- Pilih Varian --</option>');
            }
        } else {
            varianSelect.empty();
            varianSelect.append('<option value="">-- Pilih Varian --</option>');
        }
    });

    // Saat varian dipilih, load detail
    $('#varian_id').on('change', function() {
        const varianId = $(this).val();
        const produkId = $('#produk_id').val();
        const detailSelect = $('#detail_id');
        
        // Reset stok info
        $('#stok-info').hide();

        if (varianId && produkId) {
            const selectedProduk = produkData.find(p => p.id == produkId);
            
            if (selectedProduk && selectedProduk.varian) {
                const selectedVarian = selectedProduk.varian.find(v => v.id == varianId);
                
                if (selectedVarian && selectedVarian.detail) {
                    detailSelect.empty();
                    detailSelect.append('<option value="">-- Pilih Detail --</option>');
                    
                    selectedVarian.detail.forEach(function(d) {
                        detailSelect.append(new Option(d.text, d.id));
                    });
                } else {
                    detailSelect.empty();
                    detailSelect.append('<option value="">-- Pilih Detail --</option>');
                }
            }
        } else {
            detailSelect.empty();
            detailSelect.append('<option value="">-- Pilih Detail --</option>');
        }
    });

    // Saat detail dipilih, tampilkan stok
    $('#detail_id').on('change', function() {
        const detailId = $(this).val();
        const produkId = $('#produk_id').val();
        const varianId = $('#varian_id').val();
        
        if (detailId && produkId && varianId) {
            const selectedProduk = produkData.find(p => p.id == produkId);
            
            if (selectedProduk && selectedProduk.varian) {
                const selectedVarian = selectedProduk.varian.find(v => v.id == varianId);
                
                if (selectedVarian && selectedVarian.detail) {
                    const selectedDetail = selectedVarian.detail.find(d => d.id == detailId);
                    
                    if (selectedDetail) {
                        $('#stok-value').text(selectedDetail.stok);
                        $('#stok-info').show();
                        return;
                    }
                }
            }
        }
        
        $('#stok-info').hide();
    });

    // Trigger change event jika ada old value
    @if(old('produk_id'))
        $('#produk_id').trigger('change');
    @endif
    
    // Animasi saat form muncul
    $('.form-section').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});
</script>

@endsection