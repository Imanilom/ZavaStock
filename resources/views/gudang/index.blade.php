@extends('layouts.app')
@section('title', 'Manajemen Gudang')
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

    .badge-primary {
        background-color: #5C48EE;
        color: white;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-danger {
        background-color: #dc3545;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Daftar Gudang</h1>
            <div class="action-buttons">
                <a href="{{ route('gudang.create') }}" class="btn btn-primary">+ Tambah Gudang</a>
                <button class="btn btn-outline" onclick="exportData()">Download</button>
            </div>
        </div>

        <input type="search" class="search-bar" placeholder="Cari gudang..." id="searchInput">

        <table>
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
                            <div class="text-muted small">{{ $gudang->alamat }}</div>
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
                        <td>{{ $gudang->penanggung_jawab }}</td>
                        <td>
                            <a href="{{ route('gudang.show', $gudang->id) }}" class="btn-edit" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('gudang.edit', $gudang->id) }}" class="btn-edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('gudang.destroy', $gudang->id) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus gudang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data gudang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $gudangs->links() }}
        </div>
    </div>
</div>

<script>
    function exportData() {
        alert('Fitur download akan segera tersedia');
    }

    // Live search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');
        
        rows.forEach(row => {
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