<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiRiwayat;
use App\Models\AktivitasRiwayat;
use App\Models\TransaksiItemRiwayat;
use Illuminate\Database\Eloquent\Builder;

class RiwayatController extends Controller
{
    // Menampilkan daftar riwayat transaksi
    public function indexTransaksi()
    {
        $riwayats = TransaksiRiwayat::with([
                'user',
                'items' => function($query) {
                    $query->with(['produk', 'varian', 'gudang']);
                }
            ])
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('riwayat.transaksi.index', compact('riwayats'));
    }

    // Menampilkan detail riwayat transaksi
    public function showTransaksi($id)
    {
        $riwayat = TransaksiRiwayat::with([
                'user',
                'items' => function($query) {
                    $query->with([
                        'produk' => function($q) {
                            $q->withTrashed(); // Include soft-deleted products
                        },
                        'varian' => function($q) {
                            $q->withTrashed(); // Include soft-deleted variants
                        },
                        'gudang' => function($q) {
                            $q->withTrashed(); // Include soft-deleted warehouses
                        }
                    ]);
                }
            ])
            ->findOrFail($id);

        // Calculate totals if not already stored
        if (empty($riwayat->total_item)) {
            $riwayat->total_item = $riwayat->items->sum('jumlah');
        }

        if (empty($riwayat->total_nilai)) {
            $riwayat->total_nilai = $riwayat->items->sum(function($item) {
                return $item->jumlah * $item->harga_satuan;
            });
        }

        return view('riwayat.transaksi.show', compact('riwayat'));
    }

    // Menampilkan daftar riwayat aktivitas
    public function indexAktivitas()
    {
        $aktivitas = AktivitasRiwayat::with('user')
            ->latest()
            ->paginate(10);

        return view('riwayat.aktivitas.index', compact('aktivitas'));
    }

    // Filter riwayat transaksi berdasarkan tanggal atau jenis
    public function filterTransaksi(Request $request)
    {
        $query = TransaksiRiwayat::with([
            'user',
            'items' => function($query) {
                $query->with(['produk', 'varian', 'gudang']);
            }
        ]);

        // Filter by transaction type
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_transaksi', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function(Builder $q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhereHas('user', function(Builder $userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $riwayats = $query->withCount('items')
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('riwayat.transaksi.index', compact('riwayats'));
    }

    // Add these methods to your existing RiwayatController

/**
 * Show product transaction history
 */
public function produkTransaksi($produkId)
{
    $produk = Produk::with(['varian', 'kategori'])
        ->findOrFail($produkId);
    
    $transaksiItems = $produk->transaksiItems()
        ->with(['transaksi.user', 'varian', 'gudang'])
        ->latest()
        ->paginate(10);

    return view('riwayat.produk.index', compact('produk', 'transaksiItems'));
}

/**
 * Show product variant transaction history
 */
public function varianTransaksi($varianId)
{
    $varian = ProdukVarian::with(['produk', 'detail'])
        ->findOrFail($varianId);
    
    $transaksiItems = TransaksiItemRiwayat::where('varian_id', $varianId)
        ->with(['transaksi.user', 'produk', 'gudang'])
        ->latest()
        ->paginate(10);

    return view('riwayat.varian.index', compact('varian', 'transaksiItems'));
}
}