<!DOCTYPE html>
<html>
<head>
    <title>Stok Opname #{{ $opname->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Stok Opname #{{ $opname->id }}</h2>
    <p><strong>Tanggal:</strong> {{ $opname->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>User:</strong> {{ $opname->user->name }}</p>
    <p><strong>Status:</strong> {{ strtoupper($opname->status) }}</p>
    @if($opname->isApproved())
        <p><strong>Approved by:</strong> {{ $opname->approver->name ?? '-' }}</p>
        <p><strong>Approved at:</strong> {{ $opname->approved_at->format('d/m/Y H:i') }}</p>
    @endif

    <table>
        <tr><th>Produk</th><td>{{ $opname->produk->nama_produk ?? '-' }}</td></tr>
        <tr><th>Varian</th><td>{{ $opname->varian->varian ?? '-' }}</td></tr>
        <tr><th>Detail</th><td>{{ $opname->detail->detail ?? '-' }}</td></tr>
        <tr><th>Gudang</th><td>{{ $opname->gudang->nama ?? '-' }}</td></tr>
        <tr><th>Rak</th><td>{{ $opname->rak ?? '-' }}</td></tr>
        <tr><th>Stok Sistem</th><td>{{ $opname->stok_sistem }}</td></tr>
        <tr><th>Stok Fisik</th><td>{{ $opname->stok_fisik }}</td></tr>
        <tr><th>Selisih</th><td>{{ $opname->selisih }}</td></tr>
        <tr><th>Catatan</th><td>{{ $opname->catatan }}</td></tr>
    </table>
</body>
</html>
