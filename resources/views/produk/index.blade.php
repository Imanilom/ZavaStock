@extends('layouts.app')

@section('title', 'Manajemen Produk')
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

    .produk-container {
        max-width: 1400px;
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

    .action-buttons {
        display: flex;
        gap: 10px;
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

    .table-responsive {
        overflow-x: auto;
    }

    .produk-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .produk-table thead {
        background-color: #f8f9fa;
    }

    .produk-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
    }

    .produk-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .produk-table tr:hover {
        background-color: rgba(92, 72, 238, 0.03);
    }

    .produk-foto {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #eee;
    }

    .no-image {
        width: 50px;
        height: 50px;
        background: #f8f9fa;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .action-cell {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-edit {
        color: var(--success-color);
        background-color: rgba(40, 167, 69, 0.1);
    }

    .btn-edit:hover {
        background-color: rgba(40, 167, 69, 0.2);
    }

    .btn-delete {
        color: var(--danger-color);
        background-color: rgba(220, 53, 69, 0.1);
    }

    .btn-delete:hover {
        background-color: rgba(220, 53, 69, 0.2);
    }

    .collapse-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--primary-color);
        font-size: 0.75rem;
        margin-top: 5px;
        transition: var(--transition);
    }

    .collapse-toggle:hover {
        text-decoration: underline;
    }

    .collapse-toggle i {
        transition: transform 0.3s ease;
    }

    .collapse-toggle.active i {
        transform: rotate(180deg);
    }

    .collapse-content {
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
        margin-top: 10px;
        display: none;
    }

    .variant-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #dee2e6;
    }

    .variant-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .variant-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .variant-name {
        font-weight: 600;
        color: var(--dark-color);
    }

    .variant-price {
        color: var(--success-color);
        font-weight: 500;
    }

    .variant-details {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .detail-badge {
        padding: 4px 10px;
        background-color: #e9ecef;
        border-radius: 4px;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .detail-stock {
        color: var(--success-color);
        font-weight: 600;
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
        
        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<div class="produk-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Manajemen Produk</h2>
            <div class="action-buttons">
                <a href="{{ route('produk.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <form method="GET" action="{{ route('produk.index') }}" class="search-filter-container">
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
                    <select class="filter-select" name="category" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    <select class="filter-select" name="status" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if(request()->has('search') || request()->has('category') || request()->has('status'))
                        <a href="{{ route('produk.index') }}" class="btn btn-outline">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="produk-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="8%">Foto</th>
                            <th>Nama Produk</th>
                            <th>SKU</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produks as $index => $produk)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($produk->foto)
                                        <img src="{{ asset('storage/' . $produk->foto) }}" class="produk-foto" alt="Foto Produk">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $produk->nama_produk }}</div>
                                    <div class="collapse-toggle" onclick="toggleCollapse(this, 'varian-{{ $produk->id }}')">
                                        <i class="fas fa-chevron-down"></i>
                                        <span>Tampilkan varian</span>
                                    </div>
                                    <div class="collapse-content" id="varian-{{ $produk->id }}">
                                        @foreach($produk->varian as $varian)
                                            <div class="variant-item">
                                                <div class="variant-header">
                                                    <span class="variant-name">{{ $varian->varian }}</span>
                                                    <span class="variant-price">Rp {{ number_format($varian->harga_jual, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="variant-details">
                                                    @foreach($varian->detail as $detail)
                                                        <div class="detail-badge">
                                                            {{ $detail->detail }}
                                                            <span class="detail-stock">{{ $detail->stok }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ $produk->sku }}</td>
                                <td>{{ $produk->kategori }}</td>
                                <td>{{ $produk->total_stok ?? 0 }}</td>
                                <td>
                                    <span class="badge {{ $produk->status == 'AKTIF' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $produk->status }}
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <h4 class="empty-text">Belum ada data produk</h4>
                                        <a href="{{ route('produk.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Produk Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($produks->hasPages())
                <div class="pagination-container">
                    {{ $produks->appends([
                        'search' => request('search'),
                        'category' => request('category'),
                        'status' => request('status')
                    ])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function toggleCollapse(element, targetId) {
        element.classList.toggle('active');
        const target = document.getElementById(targetId);
        if (target.style.display === 'none' || !target.style.display) {
            target.style.display = 'block';
        } else {
            target.style.display = 'none';
        }
    }

    // Client-side search functionality (optional)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.produk-table tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            
            const productName = row.cells[2].textContent.toLowerCase();
            const sku = row.cells[3].textContent.toLowerCase();
            const category = row.cells[4].textContent.toLowerCase();
            
            if (productName.includes(searchValue) || sku.includes(searchValue) || category.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection