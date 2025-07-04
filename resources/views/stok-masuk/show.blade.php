@extends('layouts.app')
@section('title', 'Detail Stok Masuk')

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

    .detail-row {
        display: flex;
        margin-bottom: 10px;
    }

    .label {
        width: 180px;
        font-weight: 600;
        font-size: 13px;
        color: #333;
    }

    .value {
        font-size: 13px;
        color: #444;
    }

    .btn {
        margin-top: 30px;
        padding: 8px 14px;
        font-size: 13px;
        border-radius: 6px;
        text-decoration: none;
    }

    .btn-back {
        background-color: #5C48EE;
        color: white;
        border: none;
    }
        .btn-approve {
        background-color: #28a745;
        color: white;
        margin-right: 10px;
    }

    .btn-reject {
        background-color: #dc3545;
        color: white;
    }

    .action-buttons {
        margin-top: 30px;
        display: flex;
        gap: 10px;
    }

</style>

<div class="main-container">
    <div class="card">
        <h1>Detail Stok Masuk</h1>

        <div class="detail-row">
            <div class="label">No Transaksi:</div>
            <div class="value">{{ $stokMasuk->no_transaksi }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Tanggal Masuk:</div>
            <div class="value">{{ $stokMasuk->tanggal_masuk->format('d M Y') }}</div>
        </div>

        @if ($stokMasuk->tanggal_expired)
        <div class="detail-row">
            <div class="label">Tanggal Expired:</div>
            <div class="value">{{ $stokMasuk->tanggal_expired->format('d M Y') }}</div>
        </div>
        @endif

        <div class="detail-row">
            <div class="label">Produk:</div>
            <div class="value">{{ $stokMasuk->produk->nama_produk ?? '-' }} ({{ $stokMasuk->produk->sku ?? '-' }})</div>
        </div>

        <div class="detail-row">
            <div class="label">Varian:</div>
            <div class="value">{{ $stokMasuk->varian->varian ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Detail / Warna:</div>
            <div class="value">{{ $stokMasuk->detail->detail ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Gudang:</div>
            <div class="value">{{ $stokMasuk->gudang->nama ?? '-' }} ({{ $stokMasuk->gudang->kode ?? '-' }})</div>
        </div>

        <div class="detail-row">
            <div class="label">Rak:</div>
            <div class="value">{{ $stokMasuk->rak ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Supplier:</div>
            <div class="value">{{ $stokMasuk->supplier->nama ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Kuantitas:</div>
            <div class="value">{{ $stokMasuk->kuantitas }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Harga Satuan:</div>
            <div class="value">Rp {{ number_format($stokMasuk->harga_satuan, 0, ',', '.') }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Total Harga:</div>
            <div class="value">Rp {{ number_format($stokMasuk->total_harga, 0, ',', '.') }}</div>
        </div>

        <div class="detail-row">
            <div class="label">No. Batch:</div>
            <div class="value">{{ $stokMasuk->no_batch ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Catatan:</div>
            <div class="value">{{ $stokMasuk->catatan ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="label">Status:</div>
            <div class="value">
                <span style="color: {{ $stokMasuk->status === 'approved' ? 'green' : ($stokMasuk->status === 'pending' ? 'orange' : 'red') }}">
                    {{ ucfirst($stokMasuk->status) }}
                </span>
            </div>
        </div>

        <div class="detail-row">
            <div class="label">Dibuat Oleh:</div>
            <div class="value">{{ $stokMasuk->user->name ?? '-' }}</div>
        </div>

        
        @if ($stokMasuk->status === 'pending')
            <div class="action-buttons">
                <form action="{{ route('stok-masuk.approve', $stokMasuk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui stok masuk ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-approve">✓ Approve</button>
                </form>

                <form action="{{ route('stok-masuk.reject', $stokMasuk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak stok masuk ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-reject">✗ Reject</button>
                </form>
            </div>
        @endif
        @if ($stokMasuk->approved_by)
                <div class="detail-row">
                    <div class="label">Disetujui Oleh:</div>
                    <div class="value">{{ $stokMasuk->approver->name ?? '-' }} pada {{ $stokMasuk->approved_at->format('d M Y H:i') }}</div>
                </div>
                @endif
    </div>

      

        <a href="{{ route('stok-masuk.index') }}" class="btn btn-back">← Kembali</a>
    </div>
</div>
@endsection
