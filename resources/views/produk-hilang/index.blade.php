@extends('layouts.app')
@section('title', 'Laporan Produk Hilang')
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
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .main-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 30px;
        margin-top: 20px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .search-filter-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 15px;
    }

    .search-box {
        flex: 1;
        max-width: 400px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 12px 15px 12px 40px;
        border-radius: var(--border-radius);
        border: 1px solid #e0e0e0;
        font-size: 0.875rem;
        transition: var(--transition);
        background-color: #f8f9fa;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(92, 72, 238, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-color);
    }

    .filter-group {
        display: flex;
        gap: 10px;
    }

    .filter-select {
        padding: 10px 15px;
        border-radius: var(--border-radius);
        border: 1px solid #e0e0e0;
        font-size: 0.875rem;
        background-color: #f8f9fa;
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

    .table-responsive {
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .report-table thead {
        background-color: #f8f9fa;
    }

    .report-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
    }

    .report-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .report-table tr:hover {
        background-color: rgba(92, 72, 238, 0.03);
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-reported {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-verified {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .action-cell {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 8px 12px;
        border-radius: var(--border-radius);
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        border: none;
    }

    .btn-verify {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .btn-verify:hover {
        background-color: rgba(40, 167, 69, 0.2);
    }

    .btn-reject {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    .btn-reject:hover {
        background-color: rgba(220, 53, 69, 0.2);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 15px;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 20px;
    }

    .pagination-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .search-filter-container {
            flex-direction: column;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .filter-group {
            justify-content: flex-end;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Laporan Produk Hilang</h1>
            <div class="action-buttons">
                <a href="{{ route('produk-hilang.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Laporan
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="search-filter-container">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="search" class="search-input" placeholder="Cari produk, status, atau keterangan..." 
                       id="searchInput" value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <select class="filter-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="REPORTED" {{ request('status') == 'REPORTED' ? 'selected' : '' }}>Dilaporkan</option>
                    <option value="VERIFIED" {{ request('status') == 'VERIFIED' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button class="btn btn-primary" id="filterButton">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->has('search') || request()->has('status'))
                    <a href="{{ route('produk-hilang.index') }}" class="btn btn-outline">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Tanggal Kejadian</th>
                        <th>Dilaporkan Oleh</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td>
                                <div>{{ $report->produk->nama_produk ?? '-' }}</div>
                                @if($report->produk)
                                    <small class="text-muted">SKU: {{ $report->produk->sku }}</small>
                                @endif
                            </td>
                            <td>{{ $report->jumlah_hilang }}</td>
                            <td>{{ $report->keterangan->nama ?? '-' }}</td>
                            <td>
                                @if($report->status == 'REPORTED')
                                    <span class="badge badge-reported">{{ $report->status }}</span>
                                @elseif($report->status == 'VERIFIED')
                                    <span class="badge badge-verified">{{ $report->status }}</span>
                                @else
                                    <span class="badge badge-rejected">{{ $report->status }}</span>
                                @endif
                            </td>
                            <td>{{ $report->tanggal_kejadian->format('d M Y') }}</td>
                            <td>{{ $report->user->name ?? '-' }}</td>
                            <td class="action-cell">
                                @if ($report->status === 'REPORTED')
                                    <form action="{{ route('produk-hilang.verify', $report->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action btn-verify" onclick="return confirm('Verifikasi laporan ini?')">
                                            <i class="fas fa-check"></i> Verifikasi
                                        </button>
                                    </form>

                                    <form action="{{ route('produk-hilang.reject', $report->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-action btn-reject" onclick="return confirm('Tolak laporan ini?')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                @else
                                    <span style="color: #999;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <h4 class="empty-text">Belum ada laporan produk hilang</h4>
                                    <a href="{{ route('produk-hilang.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Laporan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="pagination-container">
                {{ $reports->appends([
                    'search' => request('search'),
                    'status' => request('status')
                ])->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Filter functionality
    document.getElementById('filterButton').addEventListener('click', function() {
        const searchValue = document.getElementById('searchInput').value;
        const statusValue = document.getElementById('statusFilter').value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if(searchValue) params.set('search', searchValue);
        else params.delete('search');
        
        if(statusValue) params.set('status', statusValue);
        else params.delete('status');
        
        window.location.href = url.pathname + '?' + params.toString();
    });

    // Initialize filter values from URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        const statusParam = urlParams.get('status');
        
        if(searchParam) {
            document.getElementById('searchInput').value = searchParam;
        }
        
        if(statusParam) {
            document.getElementById('statusFilter').value = statusParam;
        }
    });

    // Enter key in search input triggers filter
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            document.getElementById('filterButton').click();
        }
    });
</script>

@endsection