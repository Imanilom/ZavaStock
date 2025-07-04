@extends('layouts.app')

@section('title', 'Manajemen Produk')
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
        transition: all 0.4s ease;
        margin-top: 40px;
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
        transition: all 0.3s ease;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        font-size: 10px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #5C48EE;
        color: white;
    }

    .btn-outline {
        border: 1px solid #5C48EE;
        background-color: white;
        color: #5C48EE;
    }

    .search-bar {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        background-color: white;
    }

    table thead {
        background-color: #f9f9f9;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    th {
        font-weight: 600;
        color: #333;
    }

    .btn-edit,
    .btn-delete {
        border: none;
        background: none;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-edit {
        color: #28a745;
    }

    .btn-delete {
        color: #dc3545;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 10px;
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

    .produk-foto {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .collapse-content {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        margin-top: 5px;
    }

    .collapse-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .collapse-toggle:after {
        content: '+';
        font-size: 14px;
    }

    .collapse-toggle.active:after {
        content: '-';
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Manajemen Produk</h1>
            <div class="action-buttons">
                <a href="{{ route('produk.create') }}" class="btn btn-primary">+ Tambah Produk</a>
                <button class="btn btn-outline" onclick="exportData()">Download</button>
            </div>
        </div>

        <input type="search" class="search-bar" placeholder="Cari produk..." id="searchInput">

        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="10%">Foto</th>
                    <th>Nama Produk</th>
                    <th>SKU</th>
                    <th>Kategori</th>
                    <th>Total Stok</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
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
                                <div style="width:50px;height:50px;background:#eee;display:flex;align-items:center;justify-content:center;border-radius:4px;">
                                    <i class="fas fa-image" style="color:#aaa;"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $produk->nama_produk }}</strong>
                            <div class="collapse-toggle" onclick="toggleCollapse(this, 'varian-{{ $produk->id }}')">
                                <small>Tampilkan Varian</small>
                            </div>
                            <div class="collapse-content" id="varian-{{ $produk->id }}" style="display:none;">
                                @foreach($produk->varian as $varian)
                                    <div style="margin-bottom:10px;">
                                        <strong>{{ $varian->varian }}</strong> 
                                        (Harga: Rp {{ number_format($varian->harga_jual, 0, ',', '.') }})
                                        <div style="margin-left:15px;">
                                            @foreach($varian->detail as $detail)
                                                <span class="badge" style="background:#e9ecef;margin-right:5px;margin-bottom:5px;">
                                                    {{ $detail->detail }} ({{ $detail->stok }})
                                                </span>
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
                        <td>
                            <a href="{{ route('produk.edit', $produk->id) }}" class="btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleCollapse(element, targetId) {
        element.classList.toggle('active');
        const target = document.getElementById(targetId);
        if (target.style.display === 'none') {
            target.style.display = 'block';
        } else {
            target.style.display = 'none';
        }
    }

    function exportData() {
        alert('Fitur download akan segera tersedia');
    }

    // Live search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');
        
        rows.forEach(row => {
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