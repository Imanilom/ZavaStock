<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Produk;
use App\Models\ProdukVarian;
use App\Models\ProdukDetail;
use App\Models\Supplier;
use App\Models\Gudang;
use App\Models\GudangRak;
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
            $stokMasuk->approve(auth()->user());
            return back()->with('success', 'Stok masuk telah diapprove dan stok diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal approve stok: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        try {
            $stokMasuk->reject(auth()->user());
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

        return view('stok-masuk.show', compact('stokMasuk'));
    }
}
