<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukDetail;
use App\Models\ProdukVarian;
use App\Models\ProdukHilang;
use App\Models\KeteranganProduk;
use App\Models\TransaksiRiwayat;
use App\Models\TransaksiItemRiwayat;
use App\Models\AktivitasRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukHilangController extends Controller
{
    public function index(Request $request)
    {
        $query = ProdukHilang::with(['produk', 'keterangan', 'user', 'verifier'])
            ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('produk', function($q) use ($search) {
                    $q->where('nama_produk', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%');
                })
                ->orWhereHas('keterangan', function($q) use ($search) {
                    $q->where('nama', 'like', '%'.$search.'%');
                });
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('tanggal_kejadian')) {
            $query->whereDate('tanggal_kejadian', $request->tanggal_kejadian);
        }

        $reports = $query->paginate(10);

        return view('produk-hilang.index', compact('reports'));
    }

    public function create()
    {
        $keterangans = KeteranganProduk::orderBy('nama')->get();
        
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

        try {
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

                // Record transaction history
                $transaksi = TransaksiRiwayat::create([
                    'user_id' => auth()->id(),
                    'jenis_transaksi' => 'produk_hilang',
                    'transaksi_id' => $report->id,
                    'kode_transaksi' => 'PH-' . date('Ymd') . '-' . str_pad($report->id, 5, '0', STR_PAD_LEFT),
                    'tanggal_transaksi' => now(),
                    'total_item' => $validated['jumlah_hilang'],
                    'total_nilai' => 0, // No monetary value for lost products
                    'keterangan' => 'Produk hilang - ' . $report->keterangan->nama,
                ]);

                // Record transaction items
                TransaksiItemRiwayat::create([
                    'transaksi_id' => $transaksi->id,
                    'jenis_transaksi' => 'produk_hilang',
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'kuantitas' => $validated['jumlah_hilang'],
                    'harga_satuan' => 0, // No price for lost products
                    'subtotal' => 0, // No subtotal for lost products
                    'keterangan' => $validated['catatan_tambahan'] ?? 'Produk hilang',
                ]);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'produk_hilang',
                    'subjek_id' => $report->id,
                    'deskripsi' => 'Melaporkan produk hilang',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                // Update stock if verified immediately
                if (!$report->needsVerification()) {
                    $this->updateStock($report);
                }
            });

            return redirect()->route('produk-hilang.index')
                ->with('success', 'Laporan produk hilang berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $report = ProdukHilang::with(['produk', 'varian', 'detail', 'keterangan', 'user', 'verifier'])
            ->findOrFail($id);

        // Get related transaction history
        $transaksi = TransaksiRiwayat::with('items')
            ->where('jenis_transaksi', 'produk_hilang')
            ->where('transaksi_id', $id)
            ->first();

        // Get related activities
        $aktivitas = AktivitasRiwayat::with('user')
            ->where('subjek_tipe', 'produk_hilang')
            ->where('subjek_id', $id)
            ->latest()
            ->get();

        return view('produk-hilang.show', compact('report', 'transaksi', 'aktivitas'));
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

        try {
            DB::transaction(function () use ($report) {
                $report->update([
                    'status' => ProdukHilang::STATUS_VERIFIED,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                $this->updateStock($report);

                // Update transaction status in history
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'produk_hilang')
                    ->where('transaksi_id', $report->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Produk hilang - ' . $report->keterangan->nama . ' (VERIFIED)'
                    ]);
                }

                // Record verification activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'approve',
                    'subjek_tipe' => 'produk_hilang',
                    'subjek_id' => $report->id,
                    'deskripsi' => 'Memverifikasi laporan produk hilang',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Laporan telah diverifikasi dan stok diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memverifikasi laporan: ' . $e->getMessage());
        }
    }

    public function reject($id, Request $request)
    {
    
        $report = ProdukHilang::findOrFail($id);
        
        try {
            DB::transaction(function () use ($report, $request) {
                $report->update([
                    'status' => ProdukHilang::STATUS_REJECTED,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                // Update transaction status in history
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'produk_hilang')
                    ->where('transaksi_id', $report->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Produk hilang - ' . $report->keterangan->nama . ' (REJECTED)'
                    ]);
                }

                // Record rejection activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'reject',
                    'subjek_tipe' => 'produk_hilang',
                    'subjek_id' => $report->id,
                    'deskripsi' => 'Menolak laporan produk hilang: ' . $request->alasan_penolakan,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Laporan telah ditolak');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak laporan: ' . $e->getMessage());
        }
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