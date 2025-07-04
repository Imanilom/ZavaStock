@extends('layouts.app')
@section('title', 'Laporan Produk Hilang')
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
        text-decoration: none;
    }

    .btn-outline {
        border: 1px solid #5C48EE;
        background-color: white;
        color: #5C48EE;
        text-decoration: none;
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

    .btn-action {
        font-size: 12px;
        margin-right: 8px;
        text-decoration: none;
    }

    .btn-verify {
        color: green;
    }

    .btn-reject {
        color: red;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Laporan Produk Hilang</h1>
            <div>
                <a href="{{ route('produk-hilang.create') }}" class="btn btn-primary">+ Tambah Laporan</a>
            </div>
        </div>

        @if (session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <input type="search" class="search-bar" placeholder="Cari produk, status, atau keterangan...">

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Tanggal Kejadian</th>
                    <th>Dilaporkan Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td>{{ $report->produk->nama_produk ?? '-' }}</td>
                        <td>{{ $report->jumlah_hilang }}</td>
                        <td>{{ $report->keterangan->nama }}</td>
                        <td>{{ $report->status }}</td>
                        <td>{{ $report->tanggal_kejadian->format('d M Y') }}</td>
                        <td>{{ $report->user->name ?? '-' }}</td>
                        <td>
                            @if ($report->status === 'REPORTED')
                                <form action="{{ route('produk-hilang.verify', $report->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-action btn-verify" onclick="return confirm('Verifikasi laporan ini?')">
                                        ✔ Verifikasi
                                    </button>
                                </form>

                                <form action="{{ route('produk-hilang.reject', $report->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-action btn-reject" onclick="return confirm('Tolak laporan ini?')">
                                        ✖ Tolak
                                    </button>
                                </form>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada laporan produk hilang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $reports->links() }}
        </div>
    </div>
</div>

<script>
    document.querySelector('.search-bar').addEventListener('keyup', function () {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const cells = Array.from(row.cells).map(cell => cell.textContent.toLowerCase());
            if (cells.some(text => text.includes(value))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection
