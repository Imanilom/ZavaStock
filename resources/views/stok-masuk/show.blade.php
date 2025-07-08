@extends('layouts.app')
@section('title', 'Detail Stok Masuk')

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

    .detail-container {
        max-width: 800px;
        margin: 20px auto;
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
        background-color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .card-body {
        padding: 25px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-section {
        margin-bottom: 25px;
    }

    .detail-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }

    .detail-row {
        display: flex;
        margin-bottom: 12px;
    }

    .detail-label {
        width: 150px;
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--secondary-color);
    }

    .detail-value {
        font-size: 0.875rem;
        color: var(--dark-color);
        flex: 1;
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

    .btn-success {
        background-color: var(--success-color);
        color: white;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .currency {
        font-family: monospace;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-label {
            width: 120px;
        }
    }
</style>

<div class="detail-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Detail Stok Masuk</h2>
            <span class="status-badge status-{{ $stokMasuk->status }}">
                {{ $stokMasuk->status }}
            </span>
        </div>
        
        <div class="card-body">
            <div class="detail-section">
                <h3 class="detail-section-title">Informasi Transaksi</h3>
                <div class="detail-grid">
                    <div class="detail-row">
                        <div class="detail-label">No Transaksi</div>
                        <div class="detail-value">{{ $stokMasuk->no_transaksi }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Tanggal Masuk</div>
                        <div class="detail-value">{{ $stokMasuk->tanggal_masuk->format('d M Y') }}</div>
                    </div>
                    
                    @if ($stokMasuk->tanggal_expired)
                    <div class="detail-row">
                        <div class="detail-label">Tanggal Expired</div>
                        <div class="detail-value">{{ $stokMasuk->tanggal_expired->format('d M Y') }}</div>
                    </div>
                    @endif
                    
                    <div class="detail-row">
                        <div class="detail-label">No. Batch</div>
                        <div class="detail-value">{{ $stokMasuk->no_batch ?? '-' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Dibuat Oleh</div>
                        <div class="detail-value">{{ $stokMasuk->user->name ?? '-' }}</div>
                    </div>
                    
                    @if ($stokMasuk->approved_by)
                    <div class="detail-row">
                        <div class="detail-label">Disetujui Oleh</div>
                        <div class="detail-value">
                            {{ $stokMasuk->approver->name ?? '-' }}<br>
                            <small>{{ $stokMasuk->approved_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="detail-section">
                <h3 class="detail-section-title">Informasi Produk</h3>
                <div class="detail-grid">
                    <div class="detail-row">
                        <div class="detail-label">Produk</div>
                        <div class="detail-value">
                            {{ $stokMasuk->produk->nama_produk ?? '-' }}<br>
                            <small>SKU: {{ $stokMasuk->produk->sku ?? '-' }}</small>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Varian</div>
                        <div class="detail-value">{{ $stokMasuk->varian->varian ?? '-' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Detail/Warna</div>
                        <div class="detail-value">{{ $stokMasuk->detail->detail ?? '-' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3 class="detail-section-title">Informasi Penyimpanan</h3>
                <div class="detail-grid">
                    <div class="detail-row">
                        <div class="detail-label">Gudang</div>
                        <div class="detail-value">
                            {{ $stokMasuk->gudang->nama ?? '-' }}<br>
                            <small>Kode: {{ $stokMasuk->gudang->kode ?? '-' }}</small>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Rak</div>
                        <div class="detail-value">{{ $stokMasuk->rak ?? '-' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3 class="detail-section-title">Informasi Supplier & Harga</h3>
                <div class="detail-grid">
                    <div class="detail-row">
                        <div class="detail-label">Supplier</div>
                        <div class="detail-value">{{ $stokMasuk->supplier->nama ?? '-' }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Kuantitas</div>
                        <div class="detail-value">{{ $stokMasuk->kuantitas }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Harga Satuan</div>
                        <div class="detail-value" class="currency">Rp {{ number_format($stokMasuk->harga_satuan, 0, ',', '.') }}</div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Total Harga</div>
                        <div class="detail-value" class="currency">Rp {{ number_format($stokMasuk->total_harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h3 class="detail-section-title">Catatan</h3>
                <div class="detail-row">
                    <div class="detail-value">{{ $stokMasuk->catatan ?? '-' }}</div>
                </div>
            </div>
            
            @if ($stokMasuk->status === 'pending')
            <div class="action-buttons">
                <form action="{{ route('stok-masuk.approve', $stokMasuk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui stok masuk ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </form>

                <form action="{{ route('stok-masuk.reject', $stokMasuk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak stok masuk ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    
    <a href="{{ route('stok-masuk.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>
@endsection