@extends('layouts.app')
@section('title', 'Detail Gudang')
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
        margin-bottom: 20px;
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

    .info-group {
        margin-bottom: 15px;
    }

    .info-group label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #777;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-active {
        background-color: #e6f7ee;
        color: #28a745;
    }

    .badge-inactive {
        background-color: #fdecea;
        color: #dc3545;
    }

    .badge-primary {
        background-color: #e6f0ff;
        color: #5C48EE;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        margin: 25px 0 15px 0;
        color: #333;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th, .table td {
        padding: 10px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
        font-size: 12px;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #555;
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

    .btn-sm {
        padding: 5px 10px;
        font-size: 11px;
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
            <h1>Detail Gudang</h1>
            <div>
                <a href="{{ route('gudang.edit', $gudang->id) }}" class="btn btn-primary">Edit Gudang</a>
                <a href="{{ route('gudang.index') }}" class="btn btn-outline">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-group">
                    <label>Kode Gudang</label>
                    <div class="info-value">{{ $gudang->kode }}</div>
                </div>

                <div class="info-group">
                    <label>Nama Gudang</label>
                    <div class="info-value">{{ $gudang->nama }}</div>
                </div>

                <div class="info-group">
                    <label>Alamat</label>
                    <div class="info-value">{{ $gudang->alamat }}</div>
                </div>

                <div class="info-group">
                    <label>Telepon</label>
                    <div class="info-value">{{ $gudang->telepon }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-group">
                    <label>Email</label>
                    <div class="info-value">{{ $gudang->email ?? '-' }}</div>
                </div>

               <div class="info-value">
                    {{ optional($gudang->user)->name ?? '-' }} ({{ optional($gudang->user)->email ?? '-' }})
                </div>

                <div class="info-group">
                    <label>Jenis Gudang</label>
                    <div class="info-value">
                        <span class="badge badge-primary">{{ ucfirst($gudang->jenis) }}</span>
                    </div>
                </div>

                <div class="info-group">
                    <label>Status</label>
                    <div class="info-value">
                        <span class="badge {{ $gudang->aktif ? 'badge-active' : 'badge-inactive' }}">
                            {{ $gudang->aktif ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="header-row">
            <h1 class="section-title">Daftar Rak</h1>
            <a href="{{ route('gudang.rak.create', $gudang->id) }}" class="btn btn-primary btn-sm">Tambah Rak</a>
        </div>

        @if($gudang->rak->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Rak</th>
                        <th>Nama Rak</th>
                        <th>Deskripsi</th>
                        <th>Kapasitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gudang->rak as $rak)
                        <tr>
                            <td>{{ $rak->kode_rak }}</td>
                            <td>{{ $rak->nama_rak }}</td>
                            <td>{{ $rak->deskripsi ?? '-' }}</td>
                            <td>{{ $rak->kapasitas ?? 'Unlimited' }}</td>
                            <td>
                                <a href="{{ route('gudang.rak.edit', ['gudangId' => $gudang->id, 'rakId' => $rak->id]) }}" class="btn btn-outline btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada rak yang terdaftar untuk gudang ini.</p>
        @endif
    </div>

    <div class="card">
        <div class="header-row">
            <h1 class="section-title">Stok Masuk Terakhir</h1>
            <a href="{{ route('gudang.laporan-stok', $gudang->id) }}" class="btn btn-primary btn-sm">Lihat Laporan Stok</a>
        </div>

        @if($gudang->stokMasuk->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Warna</th>
                        <th>Rak</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gudang->stokMasuk as $stok)
                        <tr>
                            <td>{{ $stok->created_at->format('d/m/Y') }}</td>
                            <td>{{ $stok->produk->nama }}</td>
                            <td>{{ $stok->varian->nama ?? '-' }}</td>
                            <td>{{ $stok->warna->nama ?? '-' }}</td>
                            <td>{{ $stok->rak }}</td>
                            <td>{{ $stok->kuantitas }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada stok masuk yang tercatat untuk gudang ini.</p>
        @endif
    </div>

    <div class="card">
        <div class="header-row">
            <h1 class="section-title">Ringkasan Stok</h1>
        </div>

        @if($stokSummary->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Warna</th>
                        <th>Total Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stokSummary as $stok)
                        <tr>
                            <td>{{ $stok->produk->nama }}</td>
                            <td>{{ $stok->varian->nama ?? '-' }}</td>
                            <td>{{ $stok->warna->nama ?? '-' }}</td>
                            <td>{{ $stok->total_stok }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada stok yang tersedia di gudang ini.</p>
        @endif
    </div>
</div>

@endsection