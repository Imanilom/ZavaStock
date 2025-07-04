@extends('layouts.app')
@section('title', 'Daftar Stok Keluar')
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

    .search-filter input,
    .search-filter select {
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
            <h1>Daftar Stok Keluar</h1>
            <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary">Tambah Stok Keluar</a>
        </div>

        <!-- Filter Search (optional functionality, bisa dikembangkan) -->
        <div class="search-filter">
            <input type="text" name="search" placeholder="Cari produk..." class="form-control">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <input type="date" name="tanggal_keluar" class="form-control">
            <button type="button" class="btn btn-primary">Filter</button>
        </div>

        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Varian</th>
                    <th>Gudang</th>
                    <th>Rak</th>
                    <th>Penerima</th>
                    <th class="text-right">Qty</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stokKeluar as $index => $item)
                <tr>
                    <td>{{ ($stokKeluar->currentPage() - 1) * $stokKeluar->perPage() + $index + 1 }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->produk->nama_produk ?? '-' }}</td>
                    <td>
                        {{ optional($item->varian)->varian ?? '-' }}
                        @if(optional($item->warna)->warna)
                            - {{ optional($item->warna)->warna }}
                        @endif
                    </td>
                    <td>{{ optional($item->gudang)->nama ?? '-' }}</td>
                    <td>{{ $item->rak ?? '-' }}</td>
                    <td>{{ optional($item->customer)->nama ?? '-' }}</td>
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
                        <a href="{{ route('stok-keluar.show', $item->id) }}" class="btn btn-outline btn-sm">Detail</a>

                        @if($item->status == 'pending' && auth()->user()->can('approve-stok-keluar'))
                            <form action="{{ route('stok-keluar.approve', $item->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('stok-keluar.reject', $item->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm">Reject</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            {{ $stokKeluar->links() }}
        </div>
    </div>
</div>

@endsection
