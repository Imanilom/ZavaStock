@extends('layouts.app')
@section('title', 'Detail Stok Opname')

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
        margin-top: 40px;
        margin-bottom: 20px;
    }

    .card h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 30px;
    }

    .col-label {
        width: 150px;
        font-weight: 600;
        color: #555;
    }

    .col-value {
        flex: 1;
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
        margin-right: 8px;
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

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .actions {
        margin-top: 20px;
    }
</style>

<div class="main-container">
    <div class="card">
        <h2>Detail Stok Opname</h2>

        <div class="row">
            <div class="col-label">Tanggal</div>
            <div class="col-value">{{ $opname->created_at->format('d/m/Y H:i') }}</div>
        </div>

        <div class="row">
            <div class="col-label">Produk</div>
            <div class="col-value">{{ $opname->produk->nama_produk ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="col-label">Varian</div>
            <div class="col-value">{{ $opname->varian->varian ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="col-label">Detail</div>
            <div class="col-value">{{ $opname->detail->detail ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="col-label">Gudang</div>
            <div class="col-value">{{ $opname->gudang->nama ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="col-label">Rak</div>
            <div class="col-value">{{ $opname->rak ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="col-label">Stok Sistem</div>
            <div class="col-value">{{ $opname->stok_sistem }}</div>
        </div>

        <div class="row">
            <div class="col-label">Stok Fisik</div>
            <div class="col-value">{{ $opname->stok_fisik }}</div>
        </div>

        <div class="row">
            <div class="col-label">Selisih</div>
            <div class="col-value">{{ $opname->selisih }}</div>
        </div>

        <div class="row">
            <div class="col-label">Status</div>
            <div class="col-value">
                @if($opname->status === 'approved')
                    <span class="badge badge-approved">Approved</span>
                @else
                    <span class="badge badge-pending">Pending</span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-label">Pencatat</div>
            <div class="col-value">{{ $opname->user->name ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="col-label">Catatan</div>
            <div class="col-value">{{ $opname->catatan ?? '-' }}</div>
        </div>

        <div class="actions">
            @if($opname->status == 'pending' && auth()->user()->role === 'admin')
                <form action="{{ route('stok-opname.approve', $opname->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                </form>
            @endif

            <a href="{{ route('stok-opname.export', $opname->id) }}" class="btn btn-outline btn-sm">Download PDF</a>
        </div>
    </div>
</div>
@endsection
