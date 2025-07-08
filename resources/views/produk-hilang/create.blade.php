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
        --border-radius: 8px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .main-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        margin-top: 20px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--primary-light);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        color: #555;
    }

    .form-label.required:after {
        content: " *";
        color: var(--danger-color);
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-size: 14px;
        transition: var(--transition);
        background-color: #fff;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(92, 72, 238, 0.25);
        outline: none;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .error-message {
        color: var(--danger-color);
        font-size: 12px;
        margin-top: 5px;
    }

    .error-container {
        background-color: #fff5f5;
        border: 1px solid #fed7d7;
        color: var(--danger-color);
        padding: 15px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: var(--border-radius);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn i {
        margin-right: 8px;
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
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .btn-outline:hover {
        background-color: var(--primary-light);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ddd !important;
        border-radius: var(--border-radius) !important;
        min-height: 42px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered,
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        line-height: 42px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
    }

    .stok-info {
        font-size: 12px;
        color: var(--secondary-color);
        margin-top: 5px;
    }

    .stok-value {
        font-weight: 600;
        color: var(--success-color);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 10px;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .card {
            padding: 20px 15px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Tambah Laporan Produk Hilang</h1>
        </div>

        @if ($errors->any())
            <div class="error-container">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk-hilang.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-box-open mr-2"></i>Informasi Produk</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="produk_id" class="form-label required">Produk</label>
                            <select name="produk_id" id="produk_id" class="form-control" required></select>
                            @error('produk_id')
                                <span class="error-message">{{ $message }}</span>
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
                                <span class="error-message">{{ $message }}</span>
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
                            </select>
                            @error('varian_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="detail_id" class="form-label">Detail</label>
                            <select name="detail_id" id="detail_id" class="form-control">
                                <option value="">-- Pilih Detail --</option>
                            </select>
                            <div id="stok-info" class="stok-info" style="display: none;">
                                Stok tersedia: <span id="stok-value" class="stok-value">0</span>
                            </div>
                            @error('detail_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title"><i class="fas fa-info-circle mr-2"></i>Detail Laporan</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jumlah_hilang" class="form-label required">Jumlah Hilang</label>
                            <input type="number" min="1" name="jumlah_hilang" id="jumlah_hilang" class="form-control" 
                                   value="{{ old('jumlah_hilang') }}" required>
                            @error('jumlah_hilang')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_kejadian" class="form-label required">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian" id="tanggal_kejadian" class="form-control" 
                                   value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('tanggal_kejadian')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="catatan_tambahan" class="form-label">Catatan Tambahan</label>
                    <textarea name="catatan_tambahan" id="catatan_tambahan" class="form-control" rows="3">{{ old('catatan_tambahan') }}</textarea>
                    @error('catatan_tambahan')
                        <span class="error-message">{{ $message }}</span>
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 untuk produk
    $('#produk_id').select2({
        placeholder: 'Cari produk...',
        allowClear: true,
        ajax: {
            url: '{{ route("produk-hilang.search") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: true
        }
    });

    // Initialize Select2 untuk varian dan detail
    $('#varian_id').select2({
        placeholder: 'Pilih varian...',
        allowClear: true
    });

    $('#detail_id').select2({
        placeholder: 'Pilih detail...',
        allowClear: true
    });

    // Saat produk dipilih, load varian
    $('#produk_id').on('select2:select', function(e) {
        const data = e.params.data;
        const varianSelect = $('#varian_id');
        
        // Reset select2
        varianSelect.val(null).trigger('change');
        $('#detail_id').val(null).trigger('change');
        $('#stok-info').hide();

        // Isi varian jika ada
        if (data.varian && data.varian.length) {
            varianSelect.empty();
            varianSelect.append('<option value="">-- Pilih Varian --</option>');
            
            data.varian.forEach(function(v) {
                varianSelect.append(new Option(v.text, v.id));
            });
        }
    });

    // Saat varian dipilih, load detail
    $('#varian_id').on('change', function() {
        const varianId = $(this).val();
        const selectedProduk = $('#produk_id').select2('data')[0];
        const detailSelect = $('#detail_id');
        
        // Reset detail
        detailSelect.val(null).trigger('change');
        $('#stok-info').hide();

        if (varianId && selectedProduk && selectedProduk.varian) {
            const selectedVarian = selectedProduk.varian.find(v => v.id == varianId);
            
            if (selectedVarian && selectedVarian.detail) {
                detailSelect.empty();
                detailSelect.append('<option value="">-- Pilih Detail --</option>');
                
                selectedVarian.detail.forEach(function(d) {
                    detailSelect.append(new Option(d.text, d.id));
                });
            }
        }
    });

    // Saat detail dipilih, tampilkan stok
    $('#detail_id').on('change', function() {
        const detailId = $(this).val();
        const selectedProduk = $('#produk_id').select2('data')[0];
        
        if (detailId && selectedProduk) {
            // Cari detail yang dipilih
            let selectedDetail = null;
            for (const v of selectedProduk.varian) {
                if (v.detail) {
                    selectedDetail = v.detail.find(d => d.id == detailId);
                    if (selectedDetail) break;
                }
            }
            
            if (selectedDetail) {
                $('#stok-value').text(selectedDetail.stok);
                $('#stok-info').show();
            } else {
                $('#stok-info').hide();
            }
        } else {
            $('#stok-info').hide();
        }
    });

    // Set nilai old jika ada error validation
    @if(old('produk_id'))
        $.ajax({
            url: '{{ route("produk-hilang.search") }}',
            dataType: 'json',
            data: { q: '{{ old("produk_id") }}' },
            success: function(data) {
                if (data.results.length) {
                    const produk = data.results[0];
                    const option = new Option(produk.text, produk.id, true, true);
                    $('#produk_id').append(option).trigger('change');
                    
                    // Set varian jika ada
                    @if(old('varian_id'))
                        if (produk.varian) {
                            const varian = produk.varian.find(v => v.id == '{{ old("varian_id") }}');
                            if (varian) {
                                const varianOption = new Option(varian.text, varian.id, true, true);
                                $('#varian_id').append(varianOption).trigger('change');
                                
                                // Set detail jika ada
                                @if(old('detail_id'))
                                    if (varian.detail) {
                                        const detail = varian.detail.find(d => d.id == '{{ old("detail_id") }}');
                                        if (detail) {
                                            const detailOption = new Option(detail.text, detail.id, true, true);
                                            $('#detail_id').append(detailOption).trigger('change');
                                            $('#stok-value').text(detail.stok);
                                            $('#stok-info').show();
                                        }
                                    }
                                @endif
                            }
                        }
                    @endif
                }
            }
        });
    @endif
});
</script>

@endsection