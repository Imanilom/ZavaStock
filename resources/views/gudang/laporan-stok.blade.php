@extends('layouts.app')
@section('title', 'Laporan Stok Gudang')
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

    .laporan-container {
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

    .btn-success {
        background-color: var(--success-color);
        color: white;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .info-group {
        margin-bottom: 15px;
    }

    .info-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 5px;
        color: var(--secondary-color);
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-color);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        color: var(--dark-color);
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

    .stok-kosong {
        color: var(--danger-color);
        font-weight: 600;
    }

    .stok-ada {
        color: var(--success-color);
        font-weight: 600;
    }

    .stok-hampir-habis {
        color: var(--warning-color);
        font-weight: 600;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-primary {
        background-color: #e6f0ff;
        color: var(--primary-color);
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

    .action-buttons {
        display: flex;
        gap: 10px;
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

<div class="laporan-container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Laporan Stok Gudang: {{ $gudang->nama }}</h1>
            <div class="action-buttons">
                <a href="{{ route('gudang.show', $gudang->id) }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali ke Detail Gudang
                </a>
            </div>
        </div>

        <div class="card-body">
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
    </div>

    @foreach($stok as $rak => $items)
    <div class="card">
        <div class="card-header">
            <h2 class="section-title">Rak: {{ $rak ?: 'Tidak Tertentu' }}</h2>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="stok-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
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
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <h4 class="empty-text">Tidak ada stok di rak ini</h4>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    <div class="card">
        <div class="card-header">
            <h2 class="section-title">Ringkasan Stok</h2>
            <div class="action-buttons">
                <button id="exportPdf" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="stok-table" id="summaryTable">
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
    </div>
</div>

<!-- Include jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('exportPdf').addEventListener('click', function() {
            // Initialize jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Add title
            doc.setFontSize(18);
            doc.text('Laporan Ringkasan Stok Gudang', 14, 15);
            
            // Add warehouse info
            doc.setFontSize(12);
            doc.text(`Gudang: ${document.querySelector('.card-title').textContent.replace('Laporan Stok Gudang: ', '')}`, 14, 25);
            doc.text(`Kode: ${document.querySelectorAll('.info-value')[0].textContent}`, 14, 30);
            doc.text(`Alamat: ${document.querySelectorAll('.info-value')[1].textContent}`, 14, 35);
            
            // Add date
            const today = new Date();
            doc.text(`Tanggal: ${today.toLocaleDateString()}`, 14, 40);
            
            // Add summary table
            doc.autoTable({
                startY: 45,
                head: [['Status Stok', 'Jumlah Produk']],
                body: [
                    ['Stok Kosong', document.querySelectorAll('#summaryTable tbody tr td:nth-child(2)')[0].textContent],
                    ['Hampir Habis', document.querySelectorAll('#summaryTable tbody tr td:nth-child(2)')[1].textContent],
                    ['Tersedia', document.querySelectorAll('#summaryTable tbody tr td:nth-child(2)')[2].textContent],
                    ['Total Produk', document.querySelectorAll('#summaryTable tbody tr td:nth-child(2)')[3].textContent]
                ],
                styles: {
                    cellPadding: 5,
                    fontSize: 10,
                    valign: 'middle'
                },
                headStyles: {
                    fillColor: [92, 72, 238],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                columnStyles: {
                    0: { cellWidth: 'auto', fontStyle: 'bold' },
                    1: { cellWidth: 'auto', halign: 'right' }
                },
                didDrawCell: (data) => {
                    if (data.section === 'body' && data.column.index === 0) {
                        if (data.row.index === 0) {
                            doc.setTextColor(220, 53, 69); // Red for empty stock
                        } else if (data.row.index === 1) {
                            doc.setTextColor(255, 193, 7); // Yellow for low stock
                        } else if (data.row.index === 2) {
                            doc.setTextColor(40, 167, 69); // Green for available stock
                        } else if (data.row.index === 3) {
                            doc.setTextColor(0, 0, 0); // Black for total
                        }
                    } else {
                        doc.setTextColor(0, 0, 0); // Default black
                    }
                }
            });
            
            // Save the PDF
            doc.save(`Laporan_Stok_${document.querySelectorAll('.info-value')[0].textContent}_${today.toISOString().split('T')[0]}.pdf`);
        });
    });
</script>

@endsection