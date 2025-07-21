<?php

namespace App\Http\Controllers;

use App\Models\StokKeluar;
use App\Models\User;
use App\Models\Produk;
use App\Models\ProdukVarian;
use App\Models\ProdukDetail;
use App\Models\Gudang;
use App\Models\GudangRak;
use App\Models\Customer;
use App\Models\TransaksiRiwayat;
use App\Models\TransaksiItemRiwayat;
use App\Models\AktivitasRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = StokKeluar::with(['produk', 'gudang', 'user', 'customer'])
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

        if ($request->filled('tanggal_keluar')) {
            $query->whereDate('created_at', $request->tanggal_keluar);
        }

        $stokKeluar = $query->paginate(20);

        return view('stok-keluar.index', compact('stokKeluar'));
    }

    public function create()
    {
        $gudangs = Gudang::aktif()->get();
        $customers = Customer::orderBy('nama')->get();
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();
      
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

        return view('stok-keluar.create', compact('gudangs', 'customers', 'users', 'produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'varian_id' => 'nullable|exists:produk_varian,id',
            'detail_id' => 'nullable|exists:produk_varian_detail,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'rak' => 'nullable|string|max:50',
            'customer_id' => 'nullable|exists:customers,id',
            'kuantitas' => 'required|integer|min:1',
            'harga_satuan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $stokKeluar = StokKeluar::create([
                    'user_id' => Auth::id(),
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'] ?? null,
                    'customer_id' => $validated['customer_id'] ?? null,
                    'kuantitas' => $validated['kuantitas'],
                    'harga_satuan' => $validated['harga_satuan'] ?? 0,
                    'catatan' => $validated['catatan'],
                    'status' => Auth::user()->can('approve-stok-keluar') 
                        ? 'approved' 
                        : 'pending',
                ]);

                // Record transaction history
                $transaksi = TransaksiRiwayat::create([
                    'user_id' => Auth::id(),
                    'jenis_transaksi' => 'stok_keluar',
                    'transaksi_id' => $stokKeluar->id,
                    'kode_transaksi' => 'SK-' . date('Ymd') . '-' . str_pad($stokKeluar->id, 5, '0', STR_PAD_LEFT),
                    'tanggal_transaksi' => now(),
                    'total_item' => $validated['kuantitas'],
                    'total_nilai' => $validated['kuantitas'] * ($validated['harga_satuan'] ?? 0),
                    'keterangan' => 'Stok keluar produk ' . $stokKeluar->produk->nama_produk,
                ]);

                // Record transaction items
                TransaksiItemRiwayat::create([
                    'transaksi_id' => $transaksi->id,
                    'jenis_transaksi' => 'stok_keluar',
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'kuantitas' => $validated['kuantitas'],
                    'harga_satuan' => $validated['harga_satuan'] ?? 0,
                    'subtotal' => $validated['kuantitas'] * ($validated['harga_satuan'] ?? 0),
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'] ?? null,
                    'keterangan' => $validated['catatan'] ?? 'Stok keluar produk',
                ]);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'stok_keluar',
                    'subjek_id' => $stokKeluar->id,
                    'deskripsi' => 'Menambahkan stok keluar baru',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                if ($stokKeluar->status === 'approved') {
                    $this->updateStockAfterKeluar($stokKeluar);
                }
            });

            return redirect()->route('stok-keluar.index')
                ->with('success', 'Data stok keluar berhasil dicatat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan stok keluar: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $stokKeluar = StokKeluar::with(['produk', 'varian', 'detail', 'gudang', 'user', 'customer'])->findOrFail($id);
        
        // Get related transaction history
        $transaksi = TransaksiRiwayat::with('items')
            ->where('jenis_transaksi', 'stok_keluar')
            ->where('transaksi_id', $id)
            ->first();

        // Get related activities
        $aktivitas = AktivitasRiwayat::with('user')
            ->where('subjek_tipe', 'stok_keluar')
            ->where('subjek_id', $id)
            ->latest()
            ->get();

        return view('stok-keluar.show', compact('stokKeluar', 'transaksi', 'aktivitas'));
    }

    public function edit($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $gudangs = Gudang::aktif()->get();
        $customers = Customer::orderBy('nama')->get();
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();
        $produks = Produk::with(['varian.detail'])->get();

        return view('stok-keluar.edit', compact('stokKeluar', 'gudangs', 'customers', 'users', 'produks'));
    }

    public function update(Request $request, $id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'varian_id' => 'nullable|exists:produk_varian,id',
            'detail_id' => 'nullable|exists:produk_varian_detail,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'rak' => 'nullable|string|max:50',
            'customer_id' => 'nullable|exists:customers,id',
            'kuantitas' => 'required|integer|min:1',
            'harga_satuan' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($stokKeluar, $validated) {
                $stokKeluar->update([
                    'produk_id' => $validated['produk_id'],
                    'varian_id' => $validated['varian_id'],
                    'detail_id' => $validated['detail_id'],
                    'gudang_id' => $validated['gudang_id'],
                    'rak' => $validated['rak'] ?? null,
                    'customer_id' => $validated['customer_id'] ?? null,
                    'kuantitas' => $validated['kuantitas'],
                    'harga_satuan' => $validated['harga_satuan'] ?? 0,
                    'catatan' => $validated['catatan'],
                ]);

                // Update transaction history if exists
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'stok_keluar')
                    ->where('transaksi_id', $stokKeluar->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'total_item' => $validated['kuantitas'],
                        'total_nilai' => $validated['kuantitas'] * ($validated['harga_satuan'] ?? 0),
                        'keterangan' => 'Stok keluar produk ' . $stokKeluar->produk->nama_produk . ' (UPDATED)',
                    ]);

                    // Update transaction items
                    $item = TransaksiItemRiwayat::where('transaksi_id', $transaksi->id)
                        ->where('jenis_transaksi', 'stok_keluar')
                        ->first();

                    if ($item) {
                        $item->update([
                            'produk_id' => $validated['produk_id'],
                            'varian_id' => $validated['varian_id'],
                            'detail_id' => $validated['detail_id'],
                            'kuantitas' => $validated['kuantitas'],
                            'harga_satuan' => $validated['harga_satuan'] ?? 0,
                            'subtotal' => $validated['kuantitas'] * ($validated['harga_satuan'] ?? 0),
                            'gudang_id' => $validated['gudang_id'],
                            'rak' => $validated['rak'] ?? null,
                            'keterangan' => $validated['catatan'] ?? 'Stok keluar produk',
                        ]);
                    }
                }

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'update',
                    'subjek_tipe' => 'stok_keluar',
                    'subjek_id' => $stokKeluar->id,
                    'deskripsi' => 'Memperbarui data stok keluar',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('stok-keluar.index')
                ->with('success', 'Data stok keluar berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui stok keluar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        try {
            DB::transaction(function () use ($stokKeluar) {
                // Delete related transaction history
                TransaksiRiwayat::where('jenis_transaksi', 'stok_keluar')
                    ->where('transaksi_id', $stokKeluar->id)
                    ->delete();

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'delete',
                    'subjek_tipe' => 'stok_keluar',
                    'subjek_id' => $stokKeluar->id,
                    'deskripsi' => 'Menghapus data stok keluar',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $stokKeluar->delete();
            });

            return redirect()->route('stok-keluar.index')
                ->with('success', 'Data stok keluar berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus stok keluar: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        if ($stokKeluar->status !== 'pending') {
            return back()->with('error', 'Hanya stok keluar dengan status pending yang bisa diapprove');
        }

        try {
            DB::transaction(function () use ($stokKeluar) {
                $stokKeluar->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                $this->updateStockAfterKeluar($stokKeluar);

                // Update transaction status in history
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'stok_keluar')
                    ->where('transaksi_id', $stokKeluar->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Stok keluar produk ' . $stokKeluar->produk->nama_produk . ' (APPROVED)'
                    ]);
                }

                // Record approval activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'approve',
                    'subjek_tipe' => 'stok_keluar',
                    'subjek_id' => $stokKeluar->id,
                    'deskripsi' => 'Menyetujui stok keluar',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Stok keluar telah diapprove dan stok dikurangi');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal approve stok keluar: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        try {
            DB::transaction(function () use ($stokKeluar) {
                $stokKeluar->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // Update transaction status in history
                $transaksi = TransaksiRiwayat::where('jenis_transaksi', 'stok_keluar')
                    ->where('transaksi_id', $stokKeluar->id)
                    ->first();

                if ($transaksi) {
                    $transaksi->update([
                        'keterangan' => 'Stok keluar produk ' . $stokKeluar->produk->nama_produk . ' (REJECTED)'
                    ]);
                }

                // Record rejection activity
                AktivitasRiwayat::create([
                    'user_id' => Auth::id(),
                    'tipe_aktivitas' => 'reject',
                    'subjek_tipe' => 'stok_keluar',
                    'subjek_id' => $stokKeluar->id,
                    'deskripsi' => 'Menolak stok keluar',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return back()->with('success', 'Stok keluar telah ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak stok keluar: ' . $e->getMessage());
        }
    }

    protected function updateStockAfterKeluar(StokKeluar $stokKeluar)
    {
        if ($stokKeluar->detail_id) {
            $stokKeluar->detail->decrement('stok', $stokKeluar->kuantitas);
        } elseif ($stokKeluar->varian_id) {
            $detail = $stokKeluar->varian->detail->first();
            if ($detail) {
                $detail->decrement('stok', $stokKeluar->kuantitas);
            }
        } else {
            $detail = $stokKeluar->produk->varian->first()?->detail->first();
            if ($detail) {
                $detail->decrement('stok', $stokKeluar->kuantitas);
            }
        }
    }
}