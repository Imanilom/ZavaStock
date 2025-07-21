@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h4>
    </div>
    
    <div class="card-body">
        <!-- Form Filter -->
        <div class="mb-4">
            <form method="GET" action="{{ route('riwayat.filterTransaksi') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Transaksi</label>
                        <select name="jenis_transaksi" class="form-control">
                            <option value="">-- Semua Jenis --</option>
                            <option value="stok_masuk" {{ request('jenis_transaksi') == 'stok_masuk' ? 'selected' : '' }}>Stok Masuk</option>
                            <option value="stok_keluar" {{ request('jenis_transaksi') == 'stok_keluar' ? 'selected' : '' }}>Stok Keluar</option>
                            <option value="stok_opname" {{ request('jenis_transaksi') == 'stok_opname' ? 'selected' : '' }}>Stok Opname</option>
                            <option value="produk_hilang" {{ request('jenis_transaksi') == 'produk_hilang' ? 'selected' : '' }}>Produk Hilang</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kode</th>
                        <th>User</th>
                        <th>Total Item</th>
                        <th>Total Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $r)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal_transaksi)->format('d M Y H:i') }}</td>
                        <td>
                            <span class="badge 
                                @if($r->jenis_transaksi == 'stok_masuk') badge-success
                                @elseif($r->jenis_transaksi == 'stok_keluar') badge-danger
                                @elseif($r->jenis_transaksi == 'stok_opname') badge-warning
                                @else badge-info
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $r->jenis_transaksi)) }}
                            </span>
                        </td>
                        <td>{{ $r->kode_transaksi }}</td>
                        <td>{{ $r->user?->name ?? '-' }}</td>
                        <td class="text-right">{{ number_format($r->total_item) }}</td>
                        <td class="text-right">Rp {{ number_format($r->total_nilai, 2) }}</td>
                        <td>
                            <a href="{{ route('riwayat.transaksi.show', $r->id) }}" 
                               class="btn btn-sm btn-circle btn-primary"
                               data-toggle="tooltip" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-database fa-2x text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Tidak ada data ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $riwayats->links() }}
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
    
    .table {
        font-size: 0.9rem;
    }
    
    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .form-control {
        border-radius: 0.35rem;
        border: 1px solid #d1d3e2;
    }
    
    .form-control:focus {
        border-color: #5954eb;
        box-shadow: 0 0 0 0.2rem rgba(89, 84, 235, 0.25);
    }
</style>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection