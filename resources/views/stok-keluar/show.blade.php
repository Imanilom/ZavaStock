@extends('layouts.app')
@section('title', 'Detail Stok Keluar')
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
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }
    
    .stok-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 15px;
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
        padding: 20px 25px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }
    
    .card-body {
        padding: 25px;
    }
    
    .detail-row {
        display: flex;
        margin-bottom: 15px;
        padding: 10px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .detail-label {
        width: 180px;
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
    }
    
    .detail-value {
        font-size: 0.875rem;
        color: var(--dark-color);
        display: flex;
        align-items: center;
        flex: 1;
    }
    
    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
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
    
    .badge-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: var(--border-radius);
        font-weight: 500;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
    
    .actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 20px;
    }
    
    .back-link:hover {
        text-decoration: underline;
    }
</style>

<div class="stok-container">
    <a href="{{ route('stok-keluar.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Stok Keluar
    </a>
    
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Detail Stok Keluar #{{ $stokKeluar->id }}</h2>
        </div>
        
        <div class="card-body">
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
                        <span class="badge badge-approved">Disetujui</span>
                    @else
                        <span class="badge badge-rejected">Ditolak</span>
                    @endif
                </div>
            </div>
            
        @if($stokKeluar->status == 'pending' && auth()->user()->role === 'admin')

                <div class="actions">
                    <form action="{{ route('stok-keluar.approve', $stokKeluar->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Setuju
                        </button>
                    </form>
                    
                    <form action="{{ route('stok-keluar.reject', $stokKeluar->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-outline">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection