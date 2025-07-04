@extends('layouts.app')
@section('title', 'Tambah Laporan Produk Hilang')
@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #eef0ff;
        padding: 30px 20px;
    }

    .main-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-top: 40px;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
        color: #4a5568;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        font-size: 13px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #5C48EE;
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
        outline: none;
    }

    .error-message {
        color: #e53e3e;
        font-size: 12px;
        margin-top: 5px;
    }

    .error-container {
        background-color: #fff5f5;
        color: #e53e3e;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #fed7d7;
    }

    .btn {
        font-size: 13px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #5C48EE;
        color: white;
    }

    .btn-primary:hover {
        background-color: #4a3bc8;
    }

    .btn-outline {
        background-color: white;
        color: #5C48EE;
        border: 1px solid #5C48EE;
    }

    .btn-outline:hover {
        background-color: #f8f9fa;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 25px;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-selection {
        min-height: 42px !important;
        display: flex !important;
        align-items: center !important;
    }

    .stok-info {
        font-size: 12px;
        color: #718096;
        margin-top: 5px;
    }
</style>

<div class="main-container">
    <div class="card">
        <h1 class="card-title">Tambah Laporan Produk Hilang</h1>

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

            <div class="form-group">
                <label for="produk_id" class="form-label">Produk <span class="text-danger">*</span></label>
                <select name="produk_id" id="produk_id" class="form-control" required></select>
                @error('produk_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="varian_id" class="form-label">Varian</label>
                <select name="varian_id" id="varian_id" class="form-control">
                    <option value="">-- Pilih Varian --</option>
                </select>
                @error('varian_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="detail_id" class="form-label">Detail</label>
                <select name="detail_id" id="detail_id" class="form-control">
                    <option value="">-- Pilih Detail --</option>
                </select>
                <div id="stok-info" class="stok-info" style="display: none;">
                    Stok tersedia: <span id="stok-value">0</span>
                </div>
                @error('detail_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="keterangan_id" class="form-label">Keterangan <span class="text-danger">*</span></label>
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

            <div class="form-group">
                <label for="jumlah_hilang" class="form-label">Jumlah Hilang <span class="text-danger">*</span></label>
                <input type="number" min="1" name="jumlah_hilang" id="jumlah_hilang" class="form-control" 
                       value="{{ old('jumlah_hilang') }}" required>
                @error('jumlah_hilang')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="tanggal_kejadian" class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_kejadian" id="tanggal_kejadian" class="form-control" 
                       value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                @error('tanggal_kejadian')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="catatan_tambahan" class="form-label">Catatan Tambahan</label>
                <textarea name="catatan_tambahan" id="catatan_tambahan" class="form-control" rows="3">{{ old('catatan_tambahan') }}</textarea>
                @error('catatan_tambahan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                <a href="{{ route('produk-hilang.index') }}" class="btn btn-outline">Batal</a>
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