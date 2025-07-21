@extends('layouts.app')

@section('title', 'Detail Riwayat Transaksi')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Detail Riwayat Transaksi
        </h4>
        <a href="{{ route('riwayat.transaksi.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="card-body">
        <!-- Transaction Summary -->
        <div class="transaction-summary mb-5 p-4 bg-light rounded">
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item mb-3">
                        <span class="detail-label font-weight-bold">Kode Transaksi:</span>
                        <span class="detail-value badge badge-primary">{{ $riwayat->kode_transaksi }}</span>
                    </div>
                    <div class="detail-item mb-3">
                        <span class="detail-label font-weight-bold">Jenis Transaksi:</span>
                        <span class="detail-value">
                            <span class="badge 
                                @if($riwayat->jenis_transaksi == 'stok_masuk') badge-success
                                @elseif($riwayat->jenis_transaksi == 'stok_keluar') badge-danger
                                @elseif($riwayat->jenis_transaksi == 'produk_hilang') badge-warning
                                @else badge-info
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $riwayat->jenis_transaksi)) }}
                            </span>
                        </span>
                    </div>
                    <div class="detail-item mb-3">
                        <span class="detail-label font-weight-bold">Tanggal:</span>
                        <span class="detail-value">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ \Carbon\Carbon::parse($riwayat->tanggal_transaksi)->format('d M Y H:i') }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-item mb-3">
                        <span class="detail-label font-weight-bold">User:</span>
                        <span class="detail-value">
                            @if($riwayat->user)
                                <i class="fas fa-user mr-1"></i>{{ $riwayat->user->name }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="detail-item mb-3">
                        <span class="detail-label font-weight-bold">Total Item:</span>
                        <span class="detail-value">{{ number_format($riwayat->total_item) }}</span>
                    </div>
                    <div class="detail-item mb-3">
                        <span class="detail-label font-weight-bold">Total Nilai:</span>
                        <span class="detail-value text-success font-weight-bold">Rp {{ number_format($riwayat->total_nilai, 2) }}</span>
                    </div>
                </div>
            </div>
            @if($riwayat->keterangan)
            <div class="detail-item">
                <span class="detail-label font-weight-bold">Keterangan:</span>
                <span class="detail-value">{{ $riwayat->keterangan }}</span>
            </div>
            @endif
        </div>

        <!-- Transaction Items -->
        <h5 class="mb-3 font-weight-bold text-gray-800">
            <i class="fas fa-boxes mr-2"></i>Item Transaksi
        </h5>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%">No</th>
                        <th>Produk</th>
                        <th>Variasi</th>
                        <th width="10%">Kuantitas</th>
                        <th width="12%">Harga Satuan</th>
                        <th width="12%">Subtotal</th>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @if($item->produk)
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <i class="fas fa-box text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $item->produk->nama }}</div>
                                        <small class="text-muted">{{ $item->produk->sku ?? '-' }}</small>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->varian && $item->varian->detail)
                                <span class="badge badge-light">
                                    {{ $item->varian->varian }} - {{ $item->varian->detail->detail }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->kuantitas) }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_satuan, 2) }}</td>
                        <td class="text-right font-weight-bold">Rp {{ number_format($item->subtotal, 2) }}</td>
                        <td>
                            @if($item->gudang)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-warehouse mr-2 text-gray-500"></i>
                                    <div>
                                        <div>{{ $item->gudang->nama }}</div>
                                        @if($item->rak)
                                            <small class="text-muted">Rak: {{ $item->rak }}</small>
                                        @endif
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->keterangan)
                                <small class="text-muted">{{ $item->keterangan }}</small>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-database fa-2x text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Tidak ada item transaksi ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($riwayat->items->count() > 0)
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="3" class="text-right">Total:</th>
                        <th class="text-right">{{ number_format($riwayat->items->sum('kuantitas')) }}</th>
                        <th></th>
                        <th class="text-right">Rp {{ number_format($riwayat->items->sum('subtotal'), 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        background-color: #f8f9fc;
    }
    
    .transaction-summary {
        border-left: 4px solid #4e73df;
    }
    
    .detail-item {
        display: flex;
        margin-bottom: 0.5rem;
    }
    
    .detail-label {
        width: 120px;
        color: #5a5c69;
    }
    
    .detail-value {
        flex: 1;
    }
    
    .table {
        font-size: 0.9rem;
    }
    
    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        vertical-align: middle;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }
    
    tfoot th {
        font-weight: 600;
    }
</style>
@endsection