@extends('layouts.app')

@section('title', 'Tambah Stok Masuk')
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

    h1 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-size: 12px;
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    .required:after {
        content: " *";
        color: red;
    }

    .form-control {
        width: 100%;
        padding: 8px 10px;
        font-size: 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .btn {
        font-size: 12px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
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

    .produk-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        border-left: 4px solid #5C48EE;
    }

    .produk-info p {
        margin: 5px 0;
        font-size: 12px;
    }

    .produk-info .label {
        font-weight: 600;
        display: inline-block;
        width: 120px;
    }
</style>

<div class="main-container">
    <div class="card">
        <h1>Tambah Stok Masuk</h1>

        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stok-masuk.store') }}" method="POST">
            @csrf

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

            <div class="produk-info" id="produkInfo" style="display: none;">
                <p><span class="label">SKU:</span> <span id="infoSku">-</span></p>
                <p><span class="label">Produk:</span> <span id="infoProduk">-</span></p>
                <p><span class="label">Varian:</span> <span id="infoVarian">-</span></p>
                <p><span class="label">Detail:</span> <span id="infoDetail">-</span></p>
                <p><span class="label">Stok:</span> <span id="infoStok">-</span></p>
            </div>

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

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan') }}</textarea>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('stok-masuk.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
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

</script>

@endsection
