@extends('layouts.app')

@section('title', 'Tambah Stok Masuk')
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
        --border-radius: 10px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
        --form-control-height: 48px;
        --form-control-font-size: 16px;
    }

    .stok-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
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
        padding: 25px 30px;
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
        padding: 30px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-size: 0.95rem;
        font-weight: 500;
        display: block;
        margin-bottom: 8px;
        color: var(--dark-color);
    }

    .required:after {
        content: " *";
        color: var(--danger-color);
    }

    .form-control {
        width: 100%;
        height: var(--form-control-height);
        padding: 12px 15px;
        font-size: var(--form-control-font-size);
        border-radius: var(--border-radius);
        border: 1px solid #e0e0e0;
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 16px;
    }

    textarea.form-control {
        height: auto;
        min-height: 120px;
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
    }

    .btn {
        padding: 12px 24px;
        border-radius: var(--border-radius);
        font-weight: 500;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 10px;
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

    .produk-info {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        border-left: 4px solid var(--primary-color);
    }

    .produk-info p {
        margin: 8px 0;
        font-size: 0.95rem;
    }

    .produk-info .label {
        font-weight: 600;
        display: inline-block;
        width: 120px;
        color: var(--secondary-color);
    }

    .error-message {
        color: var(--danger-color);
        font-size: 0.85rem;
        margin-top: 8px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px 20px;
        border-radius: var(--border-radius);
        margin-bottom: 25px;
        font-size: 0.95rem;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-actions {
        margin-top: 30px;
        display: flex;
        gap: 15px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .card-header {
            padding: 20px;
        }
        
        .card-body {
            padding: 20px;
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

<div class="stok-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Tambah Stok Masuk</h2>
        </div>
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('stok-masuk.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    <h3 class="form-section-title">Informasi Produk</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="required">Produk</label>
                            <select name="produk_id" id="produk_id" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk['id'] }}" {{ old('produk_id') == $produk['id'] ? 'selected' : '' }}>
                                        {{ $produk['text'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Varian</label>
                            <select name="varian_id" id="varian_id" class="form-control">
                                <option value="">-- Pilih Varian --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Detail/Warna</label>
                            <select name="detail_id" id="detail_id" class="form-control">
                                <option value="">-- Pilih Detail --</option>
                            </select>
                        </div>
                    </div>

                    <div class="produk-info" id="produkInfo" style="display: none;">
                        <p><span class="label">SKU:</span> <span id="infoSku">-</span></p>
                        <p><span class="label">Produk:</span> <span id="infoProduk">-</span></p>
                        <p><span class="label">Varian:</span> <span id="infoVarian">-</span></p>
                        <p><span class="label">Detail:</span> <span id="infoDetail">-</span></p>
                        <p><span class="label">Stok:</span> <span id="infoStok">-</span></p>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Informasi Penyimpanan</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="required">Gudang</label>
                            <select name="gudang_id" id="gudang_id" class="form-control" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}" {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                        {{ $gudang->nama }} ({{ $gudang->kode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="required">Rak</label>
                            <select name="rak" id="rak" class="form-control" required>
                                <option value="">-- Pilih Rak --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">Detail Stok Masuk</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="required">Kuantitas</label>
                            <input type="number" name="kuantitas" class="form-control" min="1" required value="{{ old('kuantitas') }}">
                        </div>

                        <div class="form-group">
                            <label class="required">Harga Satuan</label>
                            <input type="number" name="harga_satuan" class="form-control" min="0" step="0.01" required value="{{ old('harga_satuan') }}">
                        </div>

                        <div class="form-group">
                            <label class="required">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control" required value="{{ old('tanggal_masuk', date('Y-m-d')) }}">
                        </div>

                        <div class="form-group">
                            <label>Tanggal Expired</label>
                            <input type="date" name="tanggal_expired" class="form-control" value="{{ old('tanggal_expired') }}">
                        </div>

                        <div class="form-group">
                            <label>No. Batch</label>
                            <input type="text" name="no_batch" class="form-control" value="{{ old('no_batch') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="4">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('stok-masuk.index') }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const produkData = @json($produks);

    $('#produk_id').change(function () {
        const id = $(this).val();
        const produk = produkData.find(p => p.id == id);
        const varianSelect = $('#varian_id').empty().append('<option value="">-- Pilih Varian --</option>');
        const detailSelect = $('#detail_id').empty().append('<option value="">-- Pilih Detail --</option>');

        if (produk) {
            $('#infoSku').text(produk.text.split(' - ')[0]);
            $('#infoProduk').text(produk.text.split(' - ')[1]);

            produk.varian.forEach(v => {
                varianSelect.append(new Option(v.text, v.id));
            });

            $('#produkInfo').show();
        } else {
            $('#produkInfo').hide();
        }
    });

    $('#varian_id').change(function () {
        const produkId = $('#produk_id').val();
        const varianId = $(this).val();
        const produk = produkData.find(p => p.id == produkId);
        const varian = produk?.varian?.find(v => v.id == varianId);

        const detailSelect = $('#detail_id').empty().append('<option value="">-- Pilih Detail --</option>');

        if (varian) {
            $('#infoVarian').text(varian.text);
            varian.detail.forEach(d => {
                detailSelect.append(new Option(d.text, d.id));
            });
        } else {
            $('#infoVarian').text('-');
            $('#infoDetail').text('-');
            $('#infoStok').text('-');
        }
    });

    $('#detail_id').change(function () {
        const produkId = $('#produk_id').val();
        const varianId = $('#varian_id').val();
        const detailId = $(this).val();
        const produk = produkData.find(p => p.id == produkId);
        const varian = produk?.varian?.find(v => v.id == varianId);
        const detail = varian?.detail?.find(d => d.id == detailId);

        $('#infoDetail').text(detail?.text || '-');
        $('#infoStok').text(detail?.stok || '-');
    });

    $('#gudang_id').change(function () {
        const gudangId = $(this).val();
        const rakSelect = $('#rak').empty().append('<option value="">Memuat...</option>');

        if (gudangId) {
            $.get(`/api/gudang/${gudangId}/rak`, function (data) {
                rakSelect.empty().append('<option value="">-- Pilih Rak --</option>');
                data.forEach(r => {
                    rakSelect.append(new Option(`${r.kode_rak} - ${r.nama_rak}`, r.kode_rak));
                });
            }).fail(function () {
                rakSelect.empty().append('<option value="">Gagal memuat rak</option>');
            });
        } else {
            rakSelect.empty().append('<option value="">-- Pilih Rak --</option>');
        }
    });

    // Initialize form if there are old values
    $(document).ready(function() {
        if ($('#produk_id').val()) {
            $('#produk_id').trigger('change');
            
            // Timeout to ensure the first change completes
            setTimeout(() => {
                if ($('#varian_id').val()) {
                    $('#varian_id').trigger('change');
                }
            }, 100);
        }
    });
</script>

@endsection