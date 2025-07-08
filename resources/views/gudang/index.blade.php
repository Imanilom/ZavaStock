@extends('layouts.app')

@section('title', 'Manajemen Gudang')
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

    .warehouse-container {
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

    .search-box {
        flex: 1;
        max-width: 400px;
        position: relative;
        margin-bottom: 20px;
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

    .table-responsive {
        overflow-x: auto;
    }

    .warehouse-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .warehouse-table thead {
        background-color: #f8f9fa;
    }

    .warehouse-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
    }

    .warehouse-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .warehouse-table tr:hover {
        background-color: rgba(92, 72, 238, 0.03);
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

    .btn-view {
        color: var(--info-color);
        background-color: rgba(23, 162, 184, 0.1);
    }

    .btn-view:hover {
        background-color: rgba(23, 162, 184, 0.2);
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

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-primary {
        background-color: #eef0ff;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .badge-secondary {
        background-color: #f8f9fa;
        color: var(--secondary-color);
        border: 1px solid #e0e0e0;
    }

    .badge-success {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
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
        justify-content: center;
        margin-top: 20px;
    }

    .address-text {
        font-size: 0.75rem;
        color: var(--secondary-color);
        margin-top: 4px;
    }

    @media (max-width: 768px) {
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

<div class="warehouse-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Manajemen Gudang</h2>
            <div class="action-buttons">
                <a href="{{ route('gudang.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Gudang
                </a>
            
            </div>
        </div>
        
        <div class="card-body">
            <!-- Search Box -->
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="search" class="search-input" placeholder="Cari gudang..." id="searchInput">
            </div>

            <div class="table-responsive">
                <table class="warehouse-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Kode</th>
                            <th>Nama Gudang</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Jumlah Rak</th>
                            <th>Penanggung Jawab</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gudangs as $index => $gudang)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $gudang->kode }}</td>
                                <td>
                                    <strong>{{ $gudang->nama }}</strong>
                                    <div class="address-text">{{ $gudang->alamat }}</div>
                                </td>
                                <td>
                                    @if($gudang->jenis == 'utama')
                                        <span class="badge badge-primary">Utama</span>
                                    @elseif($gudang->jenis == 'cabang')
                                        <span class="badge badge-secondary">Cabang</span>
                                    @elseif($gudang->jenis == 'retur')
                                        <span class="badge badge-warning">Retur</span>
                                    @else
                                        <span class="badge badge-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>
                                    @if($gudang->aktif)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $gudang->rak_count }}</td>
                                <td>    {{ optional($gudang->user)->name ?? '-' }} ({{ optional($gudang->user)->email ?? '-' }})</td>
                                <td class="action-cell">
                                    <a href="{{ route('gudang.show', $gudang->id) }}" class="btn-action btn-view" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('gudang.edit', $gudang->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('gudang.destroy', $gudang->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus gudang ini?')">
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
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        <h4 class="empty-text">Belum ada data gudang</h4>
                                        <a href="{{ route('gudang.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Gudang Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($gudangs->hasPages())
                <div class="pagination-container">
                    {{ $gudangs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
   
    // Live search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.warehouse-table tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            
            const kode = row.cells[1].textContent.toLowerCase();
            const nama = row.cells[2].textContent.toLowerCase();
            const penanggungJawab = row.cells[6].textContent.toLowerCase();
            
            if (kode.includes(searchValue) || nama.includes(searchValue) || penanggungJawab.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection