@extends('layouts.app')
@section('title', 'Laporan Stok Gudang')
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

    .info-group {
        margin-bottom: 15px;
    }

    .info-group label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #777;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-primary {
        background-color: #e6f0ff;
        color: #5C48EE;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        margin: 25px 0 15px 0;
        color: #333;
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

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .stok-kosong {
        color: #dc3545;
        font-weight: 600;
    }

    .stok-ada {
        color: #28a745;
        font-weight: 600;
    }

    .stok-hampir-habis {
        color: #ffc107;
        font-weight: 600;
    }
</style>

<div class="main-container">
    <div class="card">
        <div class="header-row">
            <h1>Laporan Stok Gudang: {{ $gudang->nama }}</h1>
            <div>
                <a href="{{ route('gudang.show', $gudang->id) }}" class="btn btn-outline">Kembali ke Detail Gudang</a>
            </div>
        </div>

        <div class="info-group">
            <label>Kode Gudang</label>
            <div class="info-value">{{ $gudang->kode }}</div>
        </div>

        <div class="info-group">
            <label>Alamat</label>
            <div class="info-value">{{ $gudang->alamat }}</div>
        </div>

        <div class="info-group">
            <label>Total Rak</label>
            <div class="info-value">{{ $gudang->rak_count }}</div>
        </div>
    </div>

    @foreach($stok as $rak => $items)
    <div class="card">
        <div class="header-row">
            <h2 class="section-title">Rak: {{ $rak ?: 'Tidak Tertentu' }}</h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Varian</th>
                    <th>Warna</th>
                    <th class="text-right">Stok Tersedia</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->varian->nama ?? '-' }}</td>
                    <td>{{ $item->warna->nama ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->total_stok) }}</td>
                    <td>
                        @if($item->total_stok <= 0)
                            <span class="stok-kosong">Stok Kosong</span>
                        @elseif($item->total_stok <= 10)
                            <span class="stok-hampir-habis">Hampir Habis</span>
                        @else
                            <span class="stok-ada">Tersedia</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada stok di rak ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="card">
        <div class="header-row">
            <h2 class="section-title">Ringkasan Stok</h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Status Stok</th>
                    <th class="text-right">Jumlah Produk</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalKosong = 0;
                    $totalHampirHabis = 0;
                    $totalTersedia = 0;
                    
                    foreach($stok as $items) {
                        foreach($items as $item) {
                            if($item->total_stok <= 0) {
                                $totalKosong++;
                            } elseif($item->total_stok <= 10) {
                                $totalHampirHabis++;
                            } else {
                                $totalTersedia++;
                            }
                        }
                    }
                @endphp
                <tr>
                    <td><span class="stok-kosong">Stok Kosong</span></td>
                    <td class="text-right">{{ number_format($totalKosong) }}</td>
                </tr>
                <tr>
                    <td><span class="stok-hampir-habis">Hampir Habis</span></td>
                    <td class="text-right">{{ number_format($totalHampirHabis) }}</td>
                </tr>
                <tr>
                    <td><span class="stok-ada">Tersedia</span></td>
                    <td class="text-right">{{ number_format($totalTersedia) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Produk</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalKosong + $totalHampirHabis + $totalTersedia) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection