@extends('layouts.app')
@section('title', 'Daftar Stok Opname')
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

</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Daftar Stok Opname</h1>
            <a href="{{ route('stok-opname.create') }}" class="btn btn-primary">Tambah Opname</a>
        </div>

        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Gudang</th>
                    <th>Rak</th>
                    <th>Stok Sistem</th>
                    <th>Stok Fisik</th>
                    <th>Selisih</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($opnames as $index => $opname)
                <tr>
                    <td>{{ ($opnames->currentPage() - 1) * $opnames->perPage() + $index + 1 }}</td>
                    <td>{{ $opname->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $opname->produk->nama_produk ?? '-' }}</td>
                    <td>{{ $opname->gudang->nama ?? '-' }}</td>
                    <td>{{ $opname->rak ?? '-' }}</td>
                    <td>{{ number_format($opname->stok_sistem) }}</td>
                    <td>{{ number_format($opname->stok_fisik) }}</td>
                    <td>{{ $opname->selisih }}</td>
                    <td>
                        @if($opname->status == 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($opname->status == 'approved')
                            <span class="badge badge-approved">Approved</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('stok-opname.show', $opname->id) }}" class="btn btn-outline btn-sm">Detail</a>
                        @if($opname->status == 'pending' && auth()->user()->can('approve-stok-opname'))
                        <form action="{{ route('stok-opname.approve', $opname->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Belum ada data stok opname.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            {{ $opnames->links() }}
        </div>
    </div>
</div>

@endsection
