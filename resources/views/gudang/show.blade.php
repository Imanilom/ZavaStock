@extends('layouts.app')
@section('title', 'Detail Gudang')
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
        --sidebar-width: 250px; /* Added sidebar width variable */
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--primary-light);
        margin: 0;
    }

    .gudang-container {
        width: calc(100% - var(--sidebar-width)); /* Adjust for sidebar */
        margin-left: var(--sidebar-width); /* Push content right */
        padding: 20px;
        box-sizing: border-box;
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
        font-size: 1.25rem;
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
        padding: 8px 15px;
        font-size: 0.75rem;
    }

    .info-group {
        margin-bottom: 15px;
    }

    .info-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 5px;
        color: var(--secondary-color);
    }

    .info-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dark-color);
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-active {
        background-color: #e6f7ee;
        color: var(--success-color);
    }

    .badge-inactive {
        background-color: #fdecea;
        color: var(--danger-color);
    }

    .badge-primary {
        background-color: #e6f0ff;
        color: var(--primary-color);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        color: var(--dark-color);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .gudang-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .gudang-table thead {
        background-color: #f8f9fa;
    }

    .gudang-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
    }

    .gudang-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .gudang-table tr:hover {
        background-color: rgba(92, 72, 238, 0.03);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .col-md-6 {
        width: 50%;
        padding: 0 10px;
        box-sizing: border-box;
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

    @media (max-width: 768px) {
        .gudang-container {
            width: 100%;
            margin-left: 0;
            padding: 15px;
        }
        
        .col-md-6 {
            width: 100%;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="gudang-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Detail Gudang</h2>
            <div>
                <a href="{{ route('gudang.edit', $gudang->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Gudang
                </a>
                <a href="{{ route('gudang.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-group">
                        <label>Kode Gudang</label>
                        <div class="info-value">{{ $gudang->kode }}</div>
                    </div>

                    <div class="info-group">
                        <label>Nama Gudang</label>
                        <div class="info-value">{{ $gudang->nama }}</div>
                    </div>

                    <div class="info-group">
                        <label>Alamat</label>
                        <div class="info-value">{{ $gudang->alamat }}</div>
                    </div>

                    <div class="info-group">
                        <label>Telepon</label>
                        <div class="info-value">{{ $gudang->telepon }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-group">
                        <label>Email</label>
                        <div class="info-value">{{ $gudang->email ?? '-' }}</div>
                    </div>

                    <div class="info-group">
                        <label>Penanggung Jawab</label>
                        <div class="info-value">
                            {{ optional($gudang->user)->name ?? '-' }} ({{ optional($gudang->user)->email ?? '-' }})
                        </div>
                    </div>

                    <div class="info-group">
                        <label>Jenis Gudang</label>
                        <div class="info-value">
                            <span class="badge badge-primary">{{ ucfirst($gudang->jenis) }}</span>
                        </div>
                    </div>

                    <div class="info-group">
                        <label>Status</label>
                        <div class="info-value">
                            <span class="badge {{ $gudang->aktif ? 'badge-active' : 'badge-inactive' }}">
                                {{ $gudang->aktif ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title section-title">Daftar Rak</h2>
            <a href="{{ route('gudang.rak.create', $gudang->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Rak
            </a>
        </div>

        <div class="card-body">
            @if($gudang->rak->count() > 0)
                <div class="table-responsive">
                    <table class="gudang-table">
                        <thead>
                            <tr>
                                <th>Kode Rak</th>
                                <th>Nama Rak</th>
                                <th>Deskripsi</th>
                                <th>Kapasitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gudang->rak as $rak)
                                <tr>
                                    <td>{{ $rak->kode_rak }}</td>
                                    <td>{{ $rak->nama_rak }}</td>
                                    <td>{{ $rak->deskripsi ?? '-' }}</td>
                                    <td>{{ $rak->kapasitas ?? 'Unlimited' }}</td>
                                    <td>
                                        <a href="{{ route('gudang.rak.edit', ['gudangId' => $gudang->id, 'rakId' => $rak->id]) }}" 
                                           class="btn btn-outline btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h4 class="empty-text">Tidak ada rak yang terdaftar untuk gudang ini</h4>
                    <a href="{{ route('gudang.rak.create', $gudang->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Rak Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>


<div class="card">
    <div class="card-header">
        <h2 class="card-title section-title">Stok Masuk Terakhir</h2>
        <a href="{{ route('gudang.laporan-stok', $gudang->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-file-alt"></i> Lihat Laporan Stok
        </a>
    </div>

    <div class="card-body">
        @if($gudang->stokMasuk->count() > 0)
            <div class="table-responsive">
                <table class="gudang-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th>Detail</th>
                            <th>Rak</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($gudang->stokMasuk as $stok)
                            <tr>
                                <td>{{ $stok->created_at->format('d/m/Y') }}</td>
                                <td>{{ $stok->produk->nama_produk ?? $stok->varian->produk->nama_produk ?? 'N/A' }}</td>
                                <td>{{ $stok->varian->varian ?? ($stok->produk->varian->first()->varian ?? 'N/A') }}</td>
                                <td>{{ $stok->detail->detail ?? ($stok->varian->detail->first()->detail ?? 'N/A') }}</td>
                                <td>{{ $stok->rak }}</td>
                                <td>{{ $stok->kuantitas }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <h4 class="empty-text">Tidak ada stok masuk yang tercatat untuk gudang ini</h4>
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title section-title">Ringkasan Stok</h2>
    </div>

    <div class="card-body">
        @if($stokSummary->count() > 0)
            <div class="table-responsive">
                <table class="gudang-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th>Detail</th>
                            <th>Total Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokSummary as $stok)
                            <tr>
                                <td>{{ $stok->produk->nama_produk }}</td>
                                <td>{{ $stok->varian->varian ?? '-' }}</td>
                                <td>{{ $stok->detail->detail ?? '-' }}</td>
                                <td>{{ $stok->total_stok }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <h4 class="empty-text">Tidak ada stok yang tersedia di gudang ini</h4>
            </div>
        @endif
    </div>
</div>


    <div class="card">
        <div class="card-header">
            <h2 class="card-title section-title">Ringkasan Stok</h2>
        </div>

        <div class="card-body">
            @if($stokSummary->count() > 0)
                <div class="table-responsive">
                    <table class="gudang-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Varian</th>
                                <th>Detail</th>
                                <th>Total Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stokSummary as $stok)
                                <tr>
                                    <td>{{ $stok->produk->nama_produk }}</td>
                                    <td>{{ $stok->varian->varian ?? '-' }}</td>
                                     <td>{{ $stok->detail->detail ?? '-' }}</td>
                                    <td>{{ $stok->total_stok }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h4 class="empty-text">Tidak ada stok yang tersedia di gudang ini</h4>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection