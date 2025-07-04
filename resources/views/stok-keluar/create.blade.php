@extends('layouts.app')
@section('title', 'Tambah Stok Keluar')
@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #eef0ff;
        margin: 0;
        padding: 30px 20px;
    }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .card {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        width: 100%;
        box-sizing: border-box;
        margin-top: 40px;
    }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header-row h1 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
        box-sizing: border-box;
    }

    .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
        box-sizing: border-box;
        background-color: white;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
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

    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Form Tambah Stok Keluar</h1>
        </div>

        @if ($errors->any())
            <div style="color: red; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stok-keluar.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    {{-- Produk --}}
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

                    {{-- Varian --}}
                    <div class="form-group">
                        <label for="varian_id">Varian</label>
                        <select name="varian_id" id="varian_id" class="form-select">
                            <option value="">-- Pilih Varian --</option>
                        </select>
                    </div>

                    {{-- Detail --}}
                    <div class="form-group">
                        <label for="detail_id">Detail</label>
                        <select name="detail_id" id="detail_id" class="form-select">
                            <option value="">-- Pilih Detail --</option>
                        </select>
                    </div>

                    {{-- Gudang --}}
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

                <div class="col-md-6">
                    {{-- Rak --}}
                    <div class="form-group">
                        <label for="rak">Rak</label>
                        <select name="rak" id="rak" class="form-select">
                            <option value="">-- Pilih Rak --</option>
                        </select>
                    </div>

                    {{-- Customer --}}
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

                    {{-- Kuantitas --}}
                    <div class="form-group">
                        <label for="kuantitas">Jumlah Keluar <span style="color: red;">*</span></label>
                        <input type="number" name="kuantitas" id="kuantitas" class="form-control" min="1" required value="{{ old('kuantitas') }}">
                    </div>

                    {{-- Catatan --}}
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="2" class="form-control">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Simpan Stok Keluar</button>
                <a href="{{ route('stok-keluar.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const produks = @json($produks);

    $(function () {
        // Saat pilih produk, load varian
        $('#produk_id').on('change', function () {
            const produkId = $(this).val();
            $('#varian_id').html('<option value="">-- Pilih Varian --</option>');
            $('#detail_id').html('<option value="">-- Pilih Detail --</option>');

            if (!produkId) return;

            const produk = produks.find(p => p.id == produkId);
            if (!produk) return;

            produk.varian.forEach(varian => {
                const selected = varian.id == '{{ old('varian_id') }}' ? 'selected' : '';
                $('#varian_id').append(`<option value="${varian.id}" ${selected}>${varian.text}</option>`);
            });

            // Trigger varian change kalau ada old varian_id
            if ('{{ old('varian_id') }}') {
                $('#varian_id').trigger('change');
            }
        });

        // Saat pilih varian, load detail
        $('#varian_id').on('change', function () {
            const varianId = $(this).val();
            $('#detail_id').html('<option value="">-- Pilih Detail --</option>');

            if (!varianId) return;

            const produkId = $('#produk_id').val();
            const produk = produks.find(p => p.id == produkId);
            if (!produk) return;

            const varian = produk.varian.find(v => v.id == varianId);
            if (!varian) return;

            varian.detail.forEach(detail => {
                const selected = detail.id == '{{ old('detail_id') }}' ? 'selected' : '';
                $('#detail_id').append(`<option value="${detail.id}" ${selected}>${detail.text}</option>`);
            });
        });

        // Load rak berdasarkan gudang via ajax
        $('#gudang_id').on('change', function () {
            const gudangId = $(this).val();
            $('#rak').html('<option value="">-- Pilih Rak --</option>');
            if (!gudangId) return;

            $.ajax({
                url: `/api/gudang/${gudangId}/rak`,
                type: 'GET',
                success: function (data) {
                    if (!data.length) {
                        $('#rak').append('<option value="">Tidak ada rak tersedia</option>');
                    } else {
                        data.forEach(rak => {
                            const selected = rak.kode_rak == '{{ old('rak') }}' ? 'selected' : '';
                            $('#rak').append(`<option value="${rak.kode_rak}" ${selected}>${rak.nama_rak} (${rak.kode_rak})</option>`);
                        });
                    }
                },
                error: function () {
                    $('#rak').append('<option value="">Gagal memuat rak</option>');
                }
            });
        });

        // Trigger load jika ada old value supaya form edit bisa
        @if(old('produk_id'))
            $('#produk_id').trigger('change');
        @endif

        @if(old('gudang_id'))
            $('#gudang_id').trigger('change');
        @endif
    });
</script>

@endsection
