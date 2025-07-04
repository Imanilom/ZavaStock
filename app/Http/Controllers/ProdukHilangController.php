<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukDetail;
use App\Models\ProdukVarian;
use App\Models\ProdukHilang;
use App\Models\KeteranganProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukHilangController extends Controller
{
    public function index()
    {
        $reports = ProdukHilang::with(['produk', 'user', 'keterangan', 'verifier'])
            ->latest()
            ->paginate(20);
            
        return view('produk-hilang.index', compact('reports'));
    }

    public function create()
    {
        $keterangans = KeteranganProduk::orderBy('nama')->get();
        $produks = Produk::with(['varian.detail'])->orderBy('nama_produk')->get();
        
        return view('produk-hilang.create', compact('keterangans', 'produks'));
    }

    public function searchProduk(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $search = $request->input('q');

        $produks = Produk::with(['varian.detail'])
            ->where(function($query) use ($search) {
                $query->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        $results = $produks->map(function ($produk) {
            return [
                'id' => $produk->id,
                'text' => "{$produk->sku} - {$produk->nama_produk}",
                'varian' => $produk->varian->map(function ($varian) {
                    return [
                        'id' => $varian->id,
                        'text' => $varian->varian,
                        'detail' => $varian->detail->map(function ($detail) use ($varian) {
                            return [
                                'id' => $detail->id,
                                'text' => "{$detail->detail} (Stok: {$detail->stok})",
                                'stok' => $detail->stok,
                                'varian' => $varian->varian
                            ];
                        })
                    ];
                })
            ];
        });

        return response()->json([
            'results' => $results
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'varian_id' => 'nullable|exists:produk_varian,id',
            'detail_id' => 'nullable|exists:produk_varian_detail,id',
            'keterangan_id' => 'required|exists:keterangan_produk,id',
            'jumlah_hilang' => 'required|integer|min:1',
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'catatan_tambahan' => 'nullable|string|max:500',
        ]);

        // Validasi stok cukup
        if ($validated['detail_id']) {
            $detail = ProdukDetail::findOrFail($validated['detail_id']);
            if ($detail->stok < $validated['jumlah_hilang']) {
                return back()->withInput()->with('error', 'Stok detail produk tidak mencukupi');
            }
        }

        DB::transaction(function () use ($validated) {
            $report = ProdukHilang::create([
                'user_id' => auth()->id(),
                'produk_id' => $validated['produk_id'],
                'varian_id' => $validated['varian_id'],
                'detail_id' => $validated['detail_id'],
                'keterangan_id' => $validated['keterangan_id'],
                'jumlah_hilang' => $validated['jumlah_hilang'],
                'tanggal_kejadian' => $validated['tanggal_kejadian'],
                'tanggal_lapor' => now(),
                'catatan_tambahan' => $validated['catatan_tambahan'],
                'status' => KeteranganProduk::find($validated['keterangan_id'])->butuh_verifikasi 
                    ? ProdukHilang::STATUS_REPORTED 
                    : ProdukHilang::STATUS_VERIFIED,
            ]);

            // Update stock if verified immediately
            if (!$report->needsVerification()) {
                $this->updateStock($report);
            }
        });

        return redirect()->route('produk-hilang.index')
            ->with('success', 'Laporan produk hilang berhasil disimpan');
    }

    public function verify($id)
    {
        $report = ProdukHilang::findOrFail($id);
        
        if (!$report->needsVerification()) {
            return back()->with('error', 'Laporan ini tidak memerlukan verifikasi');
        }

        // Validasi stok cukup
        if ($report->detail_id) {
            $detail = ProdukDetail::findOrFail($report->detail_id);
            if ($detail->stok < $report->jumlah_hilang) {
                return back()->with('error', 'Stok detail produk tidak mencukupi');
            }
        }

        DB::transaction(function () use ($report) {
            $report->update([
                'status' => ProdukHilang::STATUS_VERIFIED,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $this->updateStock($report);
        });

        return back()->with('success', 'Laporan telah diverifikasi dan stok diperbarui');
    }

    public function reject($id, Request $request)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255'
        ]);

        $report = ProdukHilang::findOrFail($id);
        
        $report->update([
            'status' => ProdukHilang::STATUS_REJECTED,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        return back()->with('success', 'Laporan telah ditolak');
    }

    protected function updateStock(ProdukHilang $report)
    {
        if ($report->detail_id) {
            // Update specific detail stock
            $detail = ProdukDetail::find($report->detail_id);
            $detail->decrement('stok', $report->jumlah_hilang);
        } elseif ($report->varian_id) {
            // Update variant stock (distribute to first detail)
            $varian = ProdukVarian::with('detail')->find($report->varian_id);
            if ($varian->detail->isNotEmpty()) {
                $varian->detail->first()->decrement('stok', $report->jumlah_hilang);
            }
        } else {
            // Update product stock (distribute to first variant's first detail)
            $produk = Produk::with(['varian.detail'])->find($report->produk_id);
            if ($produk->varian->isNotEmpty() && $produk->varian->first()->detail->isNotEmpty()) {
                $produk->varian->first()->detail->first()->decrement('stok', $report->jumlah_hilang);
            }
        }
    }
}