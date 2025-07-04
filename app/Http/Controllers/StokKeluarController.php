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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokKeluar = StokKeluar::with(['produk', 'gudang', 'user', 'customer'])
            ->latest()
            ->paginate(20);

        return view('stok-keluar.index', compact('stokKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
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
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated) {
            StokKeluar::create([
                'user_id' => Auth::id(),
                'produk_id' => $validated['produk_id'],
                'varian_id' => $validated['varian_id'],
                'detail_id' => $validated['detail_id'],
                'gudang_id' => $validated['gudang_id'],
                'rak' => $validated['rak'] ?? null,
                'customer_id' => $validated['customer_id'] ?? null,
                'kuantitas' => $validated['kuantitas'],
                'catatan' => $validated['catatan'],
                'status' => Auth::user()->can('approve-stok-keluar') 
                    ? 'approved' 
                    : 'pending',
            ]);
        });

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Data stok keluar berhasil dicatat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stokKeluar = StokKeluar::with(['produk', 'varian', 'detail', 'gudang', 'user', 'customer'])->findOrFail($id);
        return view('stok-keluar.show', compact('stokKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $gudangs = Gudang::aktif()->get();
        $customers = Customer::orderBy('nama')->get();
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();
        $produks = Produk::with(['varian.detail'])->get();

        return view('stok-keluar.edit', compact('stokKeluar', 'gudangs', 'customers', 'users', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
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
            'catatan' => 'nullable|string|max:500',
        ]);

        $stokKeluar->update([
            'produk_id' => $validated['produk_id'],
            'varian_id' => $validated['varian_id'],
            'detail_id' => $validated['detail_id'],
            'gudang_id' => $validated['gudang_id'],
            'rak' => $validated['rak'] ?? null,
            'customer_id' => $validated['customer_id'] ?? null,
            'kuantitas' => $validated['kuantitas'],
            'catatan' => $validated['catatan'],
        ]);

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Data stok keluar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $stokKeluar->delete();

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Data stok keluar berhasil dihapus');
    }

    /**
     * Approve pending stok keluar
     */
    public function approve($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        if ($stokKeluar->status !== 'pending') {
            return back()->with('error', 'Hanya stok keluar dengan status pending yang bisa diapprove');
        }

        try {
            $stokKeluar->approve(auth()->user());
            return back()->with('success', 'Stok keluar telah diapprove dan stok dikurangi');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal approve stok keluar: ' . $e->getMessage());
        }
    }


    /**
     * Reject stok keluar
     */
    public function reject($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        $stokKeluar->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Stok keluar telah ditolak.');
    }

    /**
     * Helper to reduce stock after approval
     */
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