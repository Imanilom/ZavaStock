@extends('layouts.app')
@section('title', 'Daftar Stok Keluar')
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
        max-width: 1400px;
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
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.75rem;
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
    
    .date-input {
        padding: 10px 15px;
        border-radius: var(--border-radius);
        border: 1px solid #e0e0e0;
        font-size: 0.875rem;
        background-color: #f8f9fa;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .stok-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    
    .stok-table thead {
        background-color: #f8f9fa;
    }
    
    .stok-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
    }
    
    .stok-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }
    
    .stok-table tr:hover {
        background-color: rgba(92, 72, 238, 0.03);
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-center {
        text-align: center;
    }
    
    .action-cell {
        display: flex;
        gap: 10px;
    }
    
    .btn-action {
        min-width: 80px;
        padding: 6px 12px;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: var(--transition);
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
    }
</style>

<div class="stok-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Daftar Stok Keluar</h2>
            <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Stok Keluar
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('stok-keluar.index') }}" class="search-filter-container">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" 
                           class="search-input" 
                           placeholder="Cari produk..." 
                           name="search"
                           value="{{ request('search') }}"
                           id="searchInput">
                </div>
                <div class="filter-group">
                    <select class="filter-select" name="status" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <input type="date" 
                           class="date-input" 
                           name="tanggal_keluar" 
                           value="{{ request('tanggal_keluar') }}"
                           id="dateFilter">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if(request()->has('search') || request()->has('status') || request()->has('tanggal_keluar'))
                        <a href="{{ route('stok-keluar.index') }}" class="btn btn-outline">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="stok-table">
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
                        @forelse($stokKeluar as $index => $item)
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
                                <td class="action-cell">
                                    <a href="{{ route('stok-keluar.show', $item->id) }}" class="btn btn-outline btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    
                                    @if($item->status == 'pending' && auth()->user()->can('approve-stok-keluar'))
                                        <form action="{{ route('stok-keluar.approve', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary btn-sm" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('stok-keluar.reject', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline btn-sm" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <h4 class="empty-text">Belum ada data stok keluar</h4>
                                        <a href="{{ route('stok-keluar.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Stok Keluar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($stokKeluar->hasPages())
                <div class="pagination-container">
                    {{ $stokKeluar->appends([
                        'search' => request('search'),
                        'status' => request('status'),
                        'tanggal_keluar' => request('tanggal_keluar')
                    ])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Client-side search functionality (optional)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.stok-table tbody tr');
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            const productName = row.cells[2].textContent.toLowerCase();
            const variant = row.cells[3].textContent.toLowerCase();
            const warehouse = row.cells[4].textContent.toLowerCase();
            if (productName.includes(searchValue) || variant.includes(searchValue) || warehouse.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Date filter change handler
    document.getElementById('dateFilter').addEventListener('change', function() {
        this.form.submit();
    });
    
    // Status filter change handler
    document.getElementById('statusFilter').addEventListener('change', function() {
        this.form.submit();
    });
</script>

@endsection