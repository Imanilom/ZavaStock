<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Produk;
use App\Models\ProdukVarian;
use App\Models\ProdukDetail;
use App\Models\Supplier;
use App\Models\Gudang;
use App\Models\GudangRak;
use App\Models\TransaksiRiwayat;
use App\Models\TransaksiItemRiwayat;
use App\Models\AktivitasRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = StokMasuk::with(['produk', 'supplier', 'gudang', 'user'])
            ->latest();

        if ($request->filled('search')) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('nama_produk', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_masuk')) {
            $query->whereDate('tanggal_masuk', $request->tanggal_masuk);
        }

        $stokMasuk = $query->paginate(20);

        return view('stok-masuk.index', compact('stokMasuk'));
    }

    public function create()
    {
        $gudangs = Gudang::aktif()->get();
        $suppliers = Supplier::orderBy('nama')->get();

        $produks = Produk::with('varian.detail')->orderBy('nama_produk')->get()->map(function ($produk) {
            return [
                'id' => $produk->id,
                'text' => "{$produk->sku} - {$produk->nama_produk}",
                'varian' => $produk->varian->map(function ($varian) use ($produk) {
                    return [
                        'id' => $varian->id,
                        'text' => $varian->varian,
                        'detail' => $varian->detail->map(function ($detail) use ($varian, $produk) {
                            return [
                                'id' => $detail->id,
                                'text' => "{$varian->varian} - {$detail->detail} (Stok: {$detail->stok})",
                                'stok' => $detail->stok,
                                'varian_id' => $varian->id,
                                'sku' => $produk->sku,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return view('stok-masuk.create', compact('gudangs', 'suppliers', 'produks'));
    }

    public function searchProduk(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2']);

        $produks = Produk::with('varian.detail')
            ->where(function ($query) use ($request) {
                $query->where('nama_produk', 'like', "%{$request->q}%")
                      ->orWhere('sku', 'like', "%{$request->q}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($produk) {
                return [
                    'id' => $produk->id,
                    'text' => "{$produk->sku} - {$produk->nama_produk}",
                    'varian' => $produk->varian->map(function ($varian) use ($produk) {
                        return [
                            'id' => $varian->id,
                            'text' => $varian->varian,
                            'detail' => $varian->detail->map(function ($detail) use ($varian, $produk) {
                                return [
                                    'id' => $detail->id,
                                    'text' => "{$varian->varian} - {$detail->detail} (Stok: {$detail->stok})",
                                    'stok' => $detail->stok,
                                    'varian_id' => $varian->id,
                                    'sku' => $produk->sku,
                                ];
                            }),
                        ];
                    }),
                ];
            });

        return response()->json($produks);
    }

    public function getRakByGudang($gudangId)
    {
        $rak = GudangRak::where('gudang_id', $gudangId)->get()->map(function ($rak) {
            return [
                'id' => $rak->kode_rak,
                'text' => $rak->kode_rak . ' - ' . $rak->nama_rak
            ];
        });

        return response()->json($rak);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'varian_id' => 'nullable|exists:produk_varian,id',
            'detail_id' => 'nullable|exists:produk_varian_detail,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'rak' => 'required|string|max:20',
            'kuantitas' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'nullable|date|after_or_equal:tanggal_masuk',
            'no_batch' => 'nullable|string|max:50',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $stokMasuk = StokMasuk::create([
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'supplier_id' => $validated['supplier_id'],
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'],
                    'kuantitas' => $validated['kuantitas'],
                    'harga_satuan' => $validated['harga_satuan'],
                    'tanggal_masuk' => $validated['tanggal_masuk'],
                    'tanggal_expired' => $validated['tanggal_expired'],
                    'no_batch' => $validated['no_batch'],
                    'catatan' => $validated['catatan'],
                    'status' => auth()->user()->can('approve-stok-masuk')
                        ? StokMasuk::STATUS_APPROVED
                        : StokMasuk::STATUS_PENDING,
                    'user_id' => auth()->id(),
                ]);

                // Record transaction history
                $transaksi = TransaksiRiwayat::create([
                    'user_id' => auth()->id(),
                    'jenis_transaksi' => 'stok_masuk',
                    'transaksi_id' => $stokMasuk->id,
                    'kode_transaksi' => 'SM-' . date('Ymd') . '-' . str_pad($stokMasuk->id, 5, '0', STR_PAD_LEFT),
                    'tanggal_transaksi' => now(),
                    'total_item' => $validated['kuantitas'],
                    'total_nilai' => $validated['kuantitas'] * $validated['harga_satuan'],
                    'keterangan' => 'Stok masuk produk ' . $stokMasuk->produk->nama_produk,
                ]);

                // Record transaction items
                TransaksiItemRiwayat::create([
                    'transaksi_id' => $transaksi->id,
                    'jenis_transaksi' => 'stok_masuk',
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'kuantitas' => $validated['kuantitas'],
                    'harga_satuan' => $validated['harga_satuan'],
                    'subtotal' => $validated['kuantitas'] * $validated['harga_satuan'],
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'],
                    'keterangan' => $validated['catatan'] ?? 'Stok masuk produk',
                ]);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'stok_masuk',
                    'subjek_id' => $stokMasuk->id,
                    'deskripsi' => 'Menambahkan stok masuk baru',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                if ($stokMasuk->isApproved()) {
                    $stokMasuk->approve(auth()->user());
                }
            });

            return redirect()->route('stok-masuk.index')->with('success', 'Stok masuk berhasil dicatat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan stok masuk: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        if ($stokMasuk->status !== StokMasuk::STATUS_PENDING) {
            return back()->with('error', 'Hanya stok masuk dengan status pending yang bisa diapprove');
        }

        try {
            DB::transaction(function () use ($stokMasuk) {
                $stokMasuk->approve(auth()->user());

                // Update transaction status in history
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'stok_masuk')
                    ->where('transaksi_id', $stokMasuk->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Stok masuk produk ' . $stokMasuk->produk->nama_produk . ' (APPROVED)'
                    ]);
                }

                // Record approval activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'approve',
                    'subjek_tipe' => 'stok_masuk',
                    'subjek_id' => $stokMasuk->id,
                    'deskripsi' => 'Menyetujui stok masuk',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Stok masuk telah diapprove dan stok diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal approve stok: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        try {
            DB::transaction(function () use ($stokMasuk) {
                $stokMasuk->reject(auth()->user());

                // Update transaction status in history
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'stok_masuk')
                    ->where('transaksi_id', $stokMasuk->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Stok masuk produk ' . $stokMasuk->produk->nama_produk . ' (REJECTED)'
                    ]);
                }

                // Record rejection activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'reject',
                    'subjek_tipe' => 'stok_masuk',
                    'subjek_id' => $stokMasuk->id,
                    'deskripsi' => 'Menolak stok masuk',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Stok masuk telah ditolak');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak stok: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $stokMasuk = StokMasuk::with([
            'produk',
            'varian',
            'detail',
            'supplier',
            'gudang',
            'user',
            'approver'
        ])->findOrFail($id);

        // Get related transaction history
        $transaksi = TransaksiRiwayat::with('items')
            ->where('jenis_transaksi', 'stok_masuk')
            ->where('transaksi_id', $id)
            ->first();

        // Get related activities
        $aktivitas = AktivitasRiwayat::with('user')
            ->where('subjek_tipe', 'stok_masuk')
            ->where('subjek_id', $id)
            ->latest()
            ->get();

        return view('stok-masuk.show', compact('stokMasuk', 'transaksi', 'aktivitas'));
    }
}