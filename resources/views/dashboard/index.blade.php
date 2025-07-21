@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <h2 class="mb-4 fw-bold">📊 Dashboard Ringkasan</h2>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Produk</h6>
                    <h3>{{ $produkCount }}</h3>
                    <i class="bi bi-box-seam fs-2 float-end"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Total Kategori</h6>
                    <h3>{{ $kategoriCount }}</h3>
                    <i class="bi bi-tags fs-2 float-end"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Total Customer</h6>
                    <h3>{{ $customerCount }}</h3>
                    <i class="bi bi-people fs-2 float-end"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Total Supplier</h6>
                    <h3>{{ $supplierCount }}</h3>
                    <i class="bi bi-truck fs-2 float-end"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movement Chart -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 fw-semibold">📈 Grafik Perpindahan Stok</h5>
            <canvas id="stokChart" height="100"></canvas>
        </div>
    </div>

    <div class="row g-4">
        <!-- Top Products Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-semibold">🏆 Produk dengan Stok Terbanyak</h5>
                    <canvas id="topProductsChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-semibold">📊 Distribusi Produk per Kategori</h5>
                    <canvas id="categoryChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mt-4 g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-semibold">🔄 Stok Masuk Terakhir</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Tanggal</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentStockIns as $stock)
                                <tr>
                                    <td>{{ $stock->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                                    <td>{{ $stock->tanggal_masuk->format('d M Y') }}</td>
                                    <td>{{ $stock->kuantitas }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-semibold">🔄 Stok Keluar Terakhir</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Tanggal</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentStockOuts as $stock)
                                <tr>
                                    <td>{{ $stock->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                                    <td>{{ $stock->created_at->format('d M Y') }}</td>
                                    <td>{{ $stock->kuantitas }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Stock Movement Chart
    const stokCtx = document.getElementById('stokChart').getContext('2d');
    const stokChart = new Chart(stokCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($stokMasukSummary->pluck('bulan')) !!},
            datasets: [
                {
                    label: 'Stok Masuk',
                    data: {!! json_encode($stokMasukSummary->pluck('total')) !!},
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,128,0,0.1)',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Stok Keluar',
                    data: {!! json_encode($stokKeluarSummary->pluck('total')) !!},
                    borderColor: 'red',
                    backgroundColor: 'rgba(255,0,0,0.1)',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Produk Hilang',
                    data: {!! json_encode($produkHilang->pluck('total')) !!},
                    borderColor: 'orange',
                    backgroundColor: 'rgba(255,165,0,0.1)',
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

   const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    const topProductsChart = new Chart(topProductsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProducts->map(function($item) { 
                return $item->nama_produk . ' - ' . $item->varian; 
            })) !!},
            datasets: [{
                label: 'Stok Tersedia',
                data: {!! json_encode($topProducts->pluck('total_stok')) !!},
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryDistribution->pluck('nama_kategori')) !!},
            datasets: [{
                data: {!! json_encode($categoryDistribution->pluck('produk_count')) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>
@endsection