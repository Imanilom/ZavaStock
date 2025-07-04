<?php

namespace App\Http\Controllers;

use App\Models\StokOpname;
use App\Models\User;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\GudangRak;
use App\Models\ProdukVarian;
use App\Models\ProdukDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StokOpnameController extends Controller
{
    public function index()
    {
        $opnames = StokOpname::with(['produk', 'gudang', 'user'])
            ->latest()
            ->paginate(20);

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

        DB::transaction(function () use ($validated) {
            StokOpname::create([
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
        });

        return redirect()->route('stok-opname.index')
            ->with('success', 'Data stok opname berhasil dicatat');
    }

    public function show($id)
    {
        $opname = StokOpname::with(['produk', 'varian', 'detail', 'gudang', 'user'])->findOrFail($id);
        return view('stok-opname.show', compact('opname'));
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

        return redirect()->route('stok-opname.index')
            ->with('success', 'Data stok opname berhasil diperbarui');
    }

    public function destroy($id)
    {
        $opname = StokOpname::findOrFail($id);
        $opname->delete();

        return redirect()->route('stok-opname.index')
            ->with('success', 'Data stok opname berhasil dihapus');
    }

    public function approve($id)
    {
        $opname = StokOpname::findOrFail($id);

        if ($opname->status !== 'pending') {
            return back()->with('error', 'Hanya data stok opname dengan status "pending" yang bisa disetujui.');
        }

        $opname->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $this->updateStockAfterOpname($opname);

        return back()->with('success', 'Stok opname telah disetujui dan stok diperbarui.');
    }

    protected function updateStockAfterOpname(StokOpname $opname)
    {
        $selisih = $opname->selisih;

        if ($selisih == 0) return;

        if ($opname->detail_id) {
            $opname->detail->increment('stok', $selisih);
        } elseif ($opname->varian_id) {
            $detail = $opname->varian->detail->first();
            if ($detail) {
                $detail->increment('stok', $selisih);
            }
        } else {
            $detail = $opname->produk->varian->first()?->detail->first();
            if ($detail) {
                $detail->increment('stok', $selisih);
            }
        }
    }

    public function exportPdf($id)
    {
        $opname = StokOpname::with(['produk', 'varian', 'detail', 'gudang', 'user', 'approver'])->findOrFail($id);

        $pdf = Pdf::loadView('stok-opname.pdf', compact('opname'));
        return $pdf->download('stok-opname-' . $opname->id . '.pdf');
    }
}