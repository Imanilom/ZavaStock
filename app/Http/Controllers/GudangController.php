<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\GudangRak;
use App\Models\StokMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::withCount(['rak', 'stokMasuk'])
            ->latest()
            ->paginate(10);

        return view('gudang.index', compact('gudangs'));
    }

    public function create()
    {
        // Ambil semua user dengan role tertentu (misalnya admin/user)
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();
        return view('gudang.create', compact('users'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'kode' => 'required|unique:gudangs,kode|max:20',
            'nama' => 'required|max:100',
            'alamat' => 'required',
            'telepon' => 'required|max:20',
            'email' => 'nullable|email',
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:utama,cabang,retur,lainnya',
        ]);
      
        $validated['aktif'] = $request->has('aktif');

        Gudang::create($validated);

        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil ditambahkan');
    }

    public function show($id)
    {
        $gudang = Gudang::with(['user', 'rak', 'stokMasuk' => function($query) {
            $query->latest()->take(10);
        }])->findOrFail($id);

        $stokSummary = StokMasuk::select([
                'produk_id',
                'varian_id',
                'detail_id',
                DB::raw('SUM(kuantitas) as total_stok')
            ])
            ->where('gudang_id', $id)
            ->where('status', 'approved')
            ->groupBy(['produk_id', 'varian_id', 'detail_id'])
            ->with(['produk', 'varian', 'detail'])
            ->get();

        return view('gudang.show', compact('gudang', 'stokSummary'));
    }

    public function edit($id)
    {
        $gudang = Gudang::findOrFail($id);
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get(); // ambil user
        return view('gudang.edit', compact('gudang', 'users'));
    }

    public function update(Request $request, $id)
    {
        $gudang = Gudang::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|unique:gudangs,kode,' . $id . '|max:20',
            'nama' => 'required|max:100',
            'alamat' => 'required',
            'telepon' => 'required|max:20',
            'email' => 'nullable|email',
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:utama,cabang,retur,lainnya',
        ]);

        $validated['aktif'] = $request->has('aktif');
    
        $gudang->update($validated);

        return redirect()->route('gudang.index', $id)
            ->with('success', 'Data gudang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $gudang = Gudang::findOrFail($id);

        if ($gudang->stokMasuk()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus gudang yang memiliki catatan stok');
        }

        $gudang->delete();

        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil dihapus');
    }

    // Method Rak tetap sama seperti sebelumnya
    public function createRak($gudangId)
    {
        $gudang = Gudang::findOrFail($gudangId);
        return view('gudang.rak.create', compact('gudang'));
    }

    public function storeRak(Request $request, $gudangId)
    {
        $validated = $request->validate([
            'kode_rak' => 'required|max:10|unique:gudang_rak,kode_rak,NULL,id,gudang_id,'.$gudangId,
            'nama_rak' => 'required|max:100',
            'deskripsi' => 'nullable',
            'kapasitas' => 'nullable|integer|min:1',
        ]);

        $validated['gudang_id'] = $gudangId;

        GudangRak::create($validated);

        return redirect()->route('gudang.show', $gudangId)
            ->with('success', 'Rak berhasil ditambahkan');
    }

    public function editRak($gudangId, $rakId)
    {
        $gudang = Gudang::findOrFail($gudangId);
        $rak = GudangRak::where('gudang_id', $gudangId)->findOrFail($rakId);
        return view('gudang.rak.edit', compact('gudang', 'rak'));
    }

    public function updateRak(Request $request, $gudangId, $rakId)
    {
        $rak = GudangRak::where('gudang_id', $gudangId)->findOrFail($rakId);

        $validated = $request->validate([
            'kode_rak' => 'required|max:10|unique:gudang_rak,kode_rak,'.$rakId.',id,gudang_id,'.$gudangId,
            'nama_rak' => 'required|max:100',
            'deskripsi' => 'nullable',
            'kapasitas' => 'nullable|integer|min:1',
        ]);

        $rak->update($validated);

        return redirect()->route('gudang.show', $gudangId)
            ->with('success', 'Data rak berhasil diperbarui');
    }

    public function destroyRak($gudangId, $rakId)
    {
        $rak = GudangRak::where('gudang_id', $gudangId)->findOrFail($rakId);

        if ($rak->stokMasuk()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus rak yang memiliki catatan stok');
        }

        $rak->delete();

        return back()->with('success', 'Rak berhasil dihapus');
    }

    public function laporanStok($id)
    {
        $gudang = Gudang::findOrFail($id);
        
        $stok = StokMasuk::select([
                'produk_id',
                'varian_id',
                'detail_id',
                'rak',
                DB::raw('SUM(kuantitas) as total_stok')
            ])
            ->where('gudang_id', $id)
            ->where('status', 'approved')
            ->groupBy(['produk_id', 'varian_id', 'detail_id', 'rak'])
            ->with(['produk', 'varian', 'detail'])
            ->get()
            ->groupBy('rak');

        return view('gudang.laporan-stok', compact('gudang', 'stok'));
    }

    public function getRak($gudangId)
    {
        $raks = GudangRak::where('gudang_id', $gudangId)->get();

        $response = $raks->map(function ($rak) {
            return [
                'id' => $rak->id,
                'kode_rak' => $rak->kode_rak,
                'nama_rak' => $rak->nama_rak,
                'kapasitas' => $rak->kapasitas
            ];
        });

        return response()->json($response);
    }

}