<?php

namespace App\Http\Controllers;

use App\Models\StokOpname;
use App\Models\User;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\GudangRak;
use App\Models\ProdukVarian;
use App\Models\ProdukDetail;
use App\Models\TransaksiRiwayat;
use App\Models\TransaksiItemRiwayat;
use App\Models\AktivitasRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StokOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StokOpname::with(['produk', 'gudang', 'user'])
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

        if ($request->filled('tanggal_opname')) {
            $query->whereDate('created_at', $request->tanggal_opname);
        }

        $opnames = $query->paginate(20);

        return view('stok-opname.index', compact('opnames'));
    }

    public function create()
    {
        $gudangs = Gudang::aktif()->get();
        $produks = Produk::with(['varian.detail'])->get();
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();

        return view('stok-opname.create', compact('gudangs', 'produks', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'varian_id' => 'nullable|exists:produk_varian,id',
            'detail_id' => 'nullable|exists:produk_varian_detail,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'rak' => 'nullable|string|max:50',
            'stok_sistem' => 'required|integer|min:0',
            'stok_fisik' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $opname = StokOpname::create([
                    'user_id' => Auth::id(),
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'] ?? null,
                    'stok_sistem' => $validated['stok_sistem'],
                    'stok_fisik' => $validated['stok_fisik'],
                    'catatan' => $validated['catatan'],
                    'status' => Auth::user()->can('approve-stok-opname') ? 'approved' : 'pending',
                ]);

                // Record transaction history
                $transaksi = TransaksiRiwayat::create([
                    'user_id' => Auth::id(),
                    'jenis_transaksi' => 'stok_opname',
                    'transaksi_id' => $opname->id,
                    'kode_transaksi' => 'SO-' . date('Ymd') . '-' . str_pad($opname->id, 5, '0', STR_PAD_LEFT),
                    'tanggal_transaksi' => now(),
                    'total_item' => 1, // Single item for stock opname
                    'total_nilai' => 0, // No monetary value for stock opname
                    'keterangan' => 'Stok opname produk ' . $opname->produk->nama_produk,
                ]);

                // Record transaction items
                TransaksiItemRiwayat::create([
                    'transaksi_id' => $transaksi->id,
                    'jenis_transaksi' => 'stok_opname',
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'kuantitas' => $validated['stok_fisik'],
                    'harga_satuan' => 0, // No price for stock opname
                    'subtotal' => 0, // No subtotal for stock opname
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'] ?? null,
                    'keterangan' => 'Sistem: ' . $validated['stok_sistem'] . ', Fisik: ' . $validated['stok_fisik'] . ($validated['catatan'] ? ' - ' . $validated['catatan'] : ''),
                ]);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'stok_opname',
                    'subjek_id' => $opname->id,
                    'deskripsi' => 'Membuat stok opname baru',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                if ($opname->status === 'approved') {
                    $this->updateStockAfterOpname($opname);
                }
            });

            return redirect()->route('stok-opname.index')
                ->with('success', 'Data stok opname berhasil dicatat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan stok opname: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $opname = StokOpname::with(['produk', 'varian', 'detail', 'gudang', 'user', 'approver'])->findOrFail($id);
        
        // Get related transaction history
        $transaksi = TransaksiRiwayat::with('items')
            ->where('jenis_transaksi', 'stok_opname')
            ->where('transaksi_id', $id)
            ->first();

        // Get related activities
        $aktivitas = AktivitasRiwayat::with('user')
            ->where('subjek_tipe', 'stok_opname')
            ->where('subjek_id', $id)
            ->latest()
            ->get();

        return view('stok-opname.show', compact('opname', 'transaksi', 'aktivitas'));
    }

    public function edit($id)
    {
        $opname = StokOpname::findOrFail($id);
        $gudangs = Gudang::aktif()->get();
        $produks = Produk::with(['varian.detail'])->get();
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();

        return view('stok-opname.edit', compact('opname', 'gudangs', 'produks', 'users'));
    }

    public function update(Request $request, $id)
    {
        $opname = StokOpname::findOrFail($id);

        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'varian_id' => 'nullable|exists:produk_varian,id',
            'detail_id' => 'nullable|exists:produk_varian_detail,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'rak' => 'nullable|string|max:50',
            'stok_sistem' => 'required|integer|min:0',
            'stok_fisik' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($opname, $validated) {
                $opname->update([
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'] ?? null,
                    'stok_sistem' => $validated['stok_sistem'],
                    'stok_fisik' => $validated['stok_fisik'],
                    'catatan' => $validated['catatan'],
                ]);

                // Update transaction history if exists
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'stok_opname')
                    ->where('transaksi_id', $opname->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Stok opname produk ' . $opname->produk->nama_produk . ' (UPDATED)'
                    ]);

                    // Update transaction items
                    $item = TransaksiItemRiwayat::where('transaksi_id', $transaksi->id)
                        ->where('jenis_transaksi', 'stok_opname')
                        ->first();

                    if ($item) {
                        $item->update([
                            'produk_id' => $validated['produk_id'],
                            'varian_id' => $validated['varian_id'],
                            'detail_id' => $validated['detail_id'],
                            'kuantitas' => $validated['stok_fisik'],
                            'gudang_id' => $validated['gudang_id'],
                            'rak' => $validated['rak'] ?? null,
                            'keterangan' => 'Sistem: ' . $validated['stok_sistem'] . ', Fisik: ' . $validated['stok_fisik'] . ($validated['catatan'] ? ' - ' . $validated['catatan'] : ''),
                        ]);
                    }
                }

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'update',
                    'subjek_tipe' => 'stok_opname',
                    'subjek_id' => $opname->id,
                    'deskripsi' => 'Memperbarui data stok opname',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('stok-opname.index')
                ->with('success', 'Data stok opname berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui stok opname: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $opname = StokOpname::findOrFail($id);

        try {
            DB::transaction(function () use ($opname) {
                // Delete related transaction history
                TransaksiRiwayat::where('jenis_transaksi', 'stok_opname')
                    ->where('transaksi_id', $opname->id)
                    ->delete();

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'delete',
                    'subjek_tipe' => 'stok_opname',
                    'subjek_id' => $opname->id,
                    'deskripsi' => 'Menghapus data stok opname',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $opname->delete();
            });

            return redirect()->route('stok-opname.index')
                ->with('success', 'Data stok opname berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus stok opname: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $opname = StokOpname::findOrFail($id);

        if ($opname->status !== 'pending') {
            return back()->with('error', 'Hanya data stok opname dengan status "pending" yang bisa disetujui.');
        }

        try {
            DB::transaction(function () use ($opname) {
                $opname->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                $this->updateStockAfterOpname($opname);

                // Update transaction status in history
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'stok_opname')
                    ->where('transaksi_id', $opname->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Stok opname produk ' . $opname->produk->nama_produk . ' (APPROVED)'
                    ]);
                }

                // Record approval activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'approve',
                    'subjek_tipe' => 'stok_opname',
                    'subjek_id' => $opname->id,
                    'deskripsi' => 'Menyetujui stok opname',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Stok opname telah disetujui dan stok diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui stok opname: ' . $e->getMessage());
        }
    }

    protected function updateStockAfterOpname(StokOpname $opname)
    {
        $selisih = $opname->selisih;

        if ($selisih == 0) return;

        if ($opname->detail_id) {
            $opname->detail->update(['stok' => $opname->stok_fisik]);
        } elseif ($opname->varian_id) {
            $detail = $opname->varian->detail->first();
            if ($detail) {
                $detail->update(['stok' => $opname->stok_fisik]);
            }
        } else {
            $detail = $opname->produk->varian->first()?->detail->first();
            if ($detail) {
                $detail->update(['stok' => $opname->stok_fisik]);
            }
        }
    }

    public function exportPdf($id)
    {
        $opname = StokOpname::with(['produk', 'varian', 'detail', 'gudang', 'user', 'approver'])->findOrFail($id);
        
        // Get related transaction history for PDF
        $transaksi = TransaksiRiwayat::with('items')
            ->where('jenis_transaksi', 'stok_opname')
            ->where('transaksi_id', $id)
            ->first();

        $pdf = Pdf::loadView('stok-opname.pdf', compact('opname', 'transaksi'));
        return $pdf->download('stok-opname-' . $opname->id . '.pdf');
    }
}