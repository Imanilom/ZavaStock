@extends('layouts.app')
@section('title', 'Tambah Stok Opname')
@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #eef0ff;
        margin: 0;
        padding: 30px 20px;
    }

    .main-container {
        max-width: 900px;
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
        margin-bottom: 20px;
    }

    .card h1 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-size: 13px;
        font-weight: 600;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 13px;
        margin-top: 5px;
    }

    .btn {
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
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

</style>

<div class="main-container">
    <div class="card">
        <h1>Tambah Stok Opname</h1>

        <form action="{{ route('stok-opname.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="produk_id">Produk</label>
                <select name="produk_id" id="produk_id" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produks as $produk)
                        <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="varian_id">Varian</label>
                <select name="varian_id" id="varian_id">
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
                <select name="detail_id" id="detail_id">
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
                <label for="gudang_id">Gudang</label>
                <select name="gudang_id" id="gudang_id" required>
                    <option value="">-- Pilih Gudang --</option>
                    @foreach ($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                    @endforeach
                </select>
            </div>

           <div class="form-group">
                <label for="rak">Rak</label>
                <select name="rak" id="rak" class="form-control">
                    <option value="">-- Pilih Rak --</option>
                </select>
            </div>


            <div class="form-group">
                <label for="stok_sistem">Stok Sistem</label>
                <input type="number" name="stok_sistem" id="stok_sistem" required min="0" value="0">
            </div>

            <div class="form-group">
                <label for="stok_fisik">Stok Fisik</label>
                <input type="number" name="stok_fisik" id="stok_fisik" required min="0" value="0">
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>
                <textarea name="catatan" id="catatan" rows="3" placeholder="Catatan tambahan..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Opname</button>
            <a href="{{ route('stok-opname.index') }}" class="btn btn-outline">Kembali</a>
        </form>
    </div>
</div>

<script>
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
