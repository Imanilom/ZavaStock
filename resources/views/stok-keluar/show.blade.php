@extends('layouts.app')
@section('title', 'Detail Stok Keluar')
@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #eef0ff;
        margin: 0;
        padding: 30px 20px;
    }

    .main-container {
        max-width: 700px;
        margin: 0 auto;
        width: 100%;
    }

    .card {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
        margin-top: 40px;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        margin-bottom: 12px;
    }

    .detail-label {
        width: 150px;
        font-weight: 600;
        color: #555;
        font-size: 14px;
    }

    .detail-value {
        font-size: 14px;
        color: #333;
        flex: 1;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .badge-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        display: inline-block;
        margin-right: 10px;
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

    .actions {
        margin-top: 20px;
    }

    a.btn-back {
        display: inline-block;
        margin-bottom: 20px;
        color: #5C48EE;
        text-decoration: none;
        font-weight: 600;
    }
</style>

<div class="main-container">
    <a href="{{ route('stok-keluar.index') }}" class="btn-back">&larr; Kembali ke Daftar Stok Keluar</a>
    <div class="card">
        <h1>Detail Stok Keluar #{{ $stokKeluar->id }}</h1>

        <div class="detail-row">
            <div class="detail-label">Tanggal</div>
            <div class="detail-value">{{ $stokKeluar->created_at->format('d/m/Y H:i') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Produk</div>
            <div class="detail-value">{{ $stokKeluar->produk->nama_produk ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Varian</div>
            <div class="detail-value">{{ optional($stokKeluar->varian)->varian ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Detail</div>
            <div class="detail-value">{{ optional($stokKeluar->detail)->detail ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Gudang</div>
            <div class="detail-value">{{ optional($stokKeluar->gudang)->nama ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Rak</div>
            <div class="detail-value">{{ $stokKeluar->rak ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Penerima (Customer)</div>
            <div class="detail-value">{{ optional($stokKeluar->customer)->nama ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Jumlah Keluar</div>
            <div class="detail-value">{{ number_format($stokKeluar->kuantitas) }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Catatan</div>
            <div class="detail-value">{{ $stokKeluar->catatan ?? '-' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Status</div>
            <div class="detail-value">
                @if($stokKeluar->status == 'pending')
                    <span class="badge badge-pending">Pending</span>
                @elseif($stokKeluar->status == 'approved')
                    <span class="badge badge-approved">Approved</span>
                @else
                    <span class="badge badge-rejected">Rejected</span>
                @endif
            </div>
        </div>

       @if($stokKeluar->status == 'pending' && auth()->user()->role === 'admin')

        <div class="actions">
            <form action="{{ route('stok-keluar.approve', $stokKeluar->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn btn-primary">Approve</button>
            </form>
            <form action="{{ route('stok-keluar.reject', $stokKeluar->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn btn-outline">Reject</button>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection
