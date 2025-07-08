<!DOCTYPE html>
<html>
<head>
    <title>Stok Opname #{{ $opname->id }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #5C48EE;
            margin-bottom: 25px;
        }
        
        .header h2 {
            color: #5C48EE;
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .info-box {
            width: 48%;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        
        .info-box:last-child {
            margin-right: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            vertical-align: top;
        }
        
        th {
            background-color: #eee;
            width: 30%;
            font-weight: bold;
        }
        
        td {
            width: 70%;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-style: italic;
            color: #666;
            text-align: center;
        }
        
        @media print {
            body {
                font-size: 11px;
            }
            
            .info-box {
                page-break-inside: avoid;
            }
            
            table {
                page-break-inside: auto;
            }
            
            th, td {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>STOK OPNAME #{{ $opname->id }}</h2>
        <p style="color: #5C48EE; margin: 5px 0;">Laporan Hasil Perhitungan Stok Fisik</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <span class="info-label">Tanggal:</span>
            {{ $opname->created_at->format('d/m/Y H:i') }}
        </div>
        
        <div class="info-box">
            <span class="info-label">Pencatat:</span>
            {{ $opname->user->name }}
        </div>
        
        <div class="info-box">
            <span class="info-label">Status:</span>
            <span class="status-badge status-{{ strtolower($opname->status) }}">
                {{ strtoupper($opname->status) }}
            </span>
        </div>
        
        @if($opname->isApproved())
            <div class="info-box">
                <span class="info-label">Disetujui oleh:</span>
                {{ $opname->approver->name ?? '-' }}
            </div>
            
            <div class="info-box">
                <span class="info-label">Tanggal Persetujuan:</span>
                {{ $opname->approved_at->format('d/m/Y H:i') }}
            </div>
        @endif
    </div>

    <table>
        <tr>
            <th>Produk</th>
            <td>{{ $opname->produk->nama_produk ?? '-' }}</td>
        </tr>
        <tr>
            <th>Varian</th>
            <td>{{ $opname->varian->varian ?? '-' }}</td>
        </tr>
        <tr>
            <th>Detail</th>
            <td>{{ $opname->detail->detail ?? '-' }}</td>
        </tr>
        <tr>
            <th>Gudang</th>
            <td>{{ $opname->gudang->nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Rak</th>
            <td>{{ $opname->rak ?? '-' }}</td>
        </tr>
        <tr>
            <th>Stok Sistem</th>
            <td>{{ number_format($opname->stok_sistem) }}</td>
        </tr>
        <tr>
            <th>Stok Fisik</th>
            <td>{{ number_format($opname->stok_fisik) }}</td>
        </tr>
        <tr>
            <th>Selisih</th>
            <td>
                {{ $opname->selisih }}
                @if($opname->selisih != 0)
                    ({{ $opname->selisih > 0 ? '+' : '' }}{{ $opname->selisih_percent }}%)
                @endif
            </td>
        </tr>
        <tr>
            <th>Catatan</th>
            <td style="white-space: pre-line;">{{ $opname->catatan ?: '-' }}</td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dicetak pada tanggal {{ date('d/m/Y H:i') }}.
        Silakan verifikasi informasi di atas untuk keperluan audit stok.
    </div>
</body>
</html>