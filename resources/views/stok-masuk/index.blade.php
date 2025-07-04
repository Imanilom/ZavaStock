@extends('layouts.app')
@section('title', 'Daftar Stok Masuk')
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

    .badge-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination .page-item.active .page-link {
        background-color: #5C48EE;
        border-color: #5C48EE;
    }

    .pagination .page-link {
        color: #5C48EE;
    }

    .search-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-filter input, .search-filter select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 12px;
    }

    .search-filter button {
        padding: 8px 16px;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Daftar Stok Masuk</h1>
            <a href="{{ route('stok-masuk.create') }}" class="btn btn-primary">Tambah Stok Masuk</a>
        </div>

        <form method="GET" action="{{ route('stok-masuk.index') }}" class="search-filter">
            <input type="text" name="search" placeholder="Cari produk..." class="form-control" 
                   value="{{ request('search') }}">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <input type="date" name="tanggal_masuk" class="form-control" value="{{ request('tanggal_masuk') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('stok-masuk.index') }}" class="btn btn-outline">Reset</a>
        </form>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Gudang</th>
                        <th>Rak</th>
                        <th class="text-right">Qty</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokMasuk as $index => $item)
                    <tr>
                        <td>{{ ($stokMasuk->currentPage() - 1) * $stokMasuk->perPage() + $index + 1 }}</td>
                        <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>
                            @if($item->varian)
                                {{ $item->varian->varian }}
                                @if($item->warna)
                                    - {{ $item->warna->warna }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->gudang->nama }}</td>
                        <td>{{ $item->rak }}</td>
                        <td class="text-right">{{ number_format($item->kuantitas) }}</td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($item->status == 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @else
                                <span class="badge badge-rejected">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('stok-masuk.show', $item->id) }}" class="btn btn-outline btn-sm">Detail</a>
                                @if($item->status == 'pending' && auth()->user()->can('approve-stok-masuk'))
                                <form action="{{ route('stok-masuk.approve', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('stok-masuk.reject', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline btn-sm">Reject</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data stok masuk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stokMasuk->hasPages())
        <div class="pagination">
            {{ $stokMasuk->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection