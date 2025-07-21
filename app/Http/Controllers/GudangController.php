<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\GudangRak;
use App\Models\StokMasuk;
use App\Models\User;
use App\Models\TransaksiRiwayat;
use App\Models\AktivitasRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::withCount(['rak', 'stokMasuk', 'user'])
            ->latest()
            ->paginate(10);

        return view('gudang.index', compact('gudangs'));
    }

    public function create()
    {
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

        try {
            DB::transaction(function () use ($validated) {
                $gudang = Gudang::create($validated);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'gudang',
                    'subjek_id' => $gudang->id,
                    'deskripsi' => 'Menambahkan gudang baru: ' . $gudang->nama,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('gudang.index')
                ->with('success', 'Gudang berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan gudang: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $gudang = Gudang::with([
            'user',
            'rak',
            'stokMasuk' => function($query) {
                $query->with([
                    'produk', 
                    'varian.produk', 
                    'detail.varian.produk'
                ])
                ->latest()
                ->take(10);
            }
        ])->findOrFail($id);

        // Get related activities
        $rakIds = $gudang->rak->pluck('id');
        $aktivitas = AktivitasRiwayat::with('user')
            ->where(function($query) use ($id, $rakIds) {
                $query->where(function($q) use ($id) {
                    $q->where('subjek_tipe', 'gudang')
                      ->where('subjek_id', $id);
                })
                ->orWhere(function($q) use ($rakIds) {
                    $q->where('subjek_tipe', 'gudang_rak')
                      ->whereIn('subjek_id', $rakIds);
                });
            })
            ->latest()
            ->get();

        $stokSummary = StokMasuk::select([
                'produk_id',
                'varian_id',
                'detail_id',
                'rak',
                DB::raw('SUM(kuantitas) as total_stok')
            ])
            ->where('gudang_id', $id)
            ->where('status', 'approved')
            ->groupBy('produk_id', 'varian_id', 'detail_id', 'rak')
            ->with([
                'produk',
                'varian.produk',
                'detail.varian.produk'
            ])
            ->get();

        return view('gudang.show', compact('gudang', 'stokSummary', 'aktivitas'));
    }

    public function edit($id)
    {
        $gudang = Gudang::findOrFail($id);
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();
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

        try {
            DB::transaction(function () use ($gudang, $validated) {
                $gudang->update($validated);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'update',
                    'subjek_tipe' => 'gudang',
                    'subjek_id' => $gudang->id,
                    'deskripsi' => 'Memperbarui data gudang: ' . $gudang->nama,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('gudang.index', $id)
                ->with('success', 'Data gudang berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui gudang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $gudang = Gudang::findOrFail($id);

        if ($gudang->stokMasuk()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus gudang yang memiliki catatan stok');
        }

        try {
            DB::transaction(function () use ($gudang) {
                // Record activity before deletion
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'delete',
                    'subjek_tipe' => 'gudang',
                    'subjek_id' => $gudang->id,
                    'deskripsi' => 'Menghapus gudang: ' . $gudang->nama,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $gudang->delete();
            });

            return redirect()->route('gudang.index')
                ->with('success', 'Gudang berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus gudang: ' . $e->getMessage());
        }
    }

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

        try {
            DB::transaction(function () use ($validated, $gudangId) {
                $rak = GudangRak::create($validated);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'gudang_rak',
                    'subjek_id' => $rak->id,
                    'deskripsi' => 'Menambahkan rak baru: ' . $rak->nama_rak . ' di gudang ' . $rak->gudang->nama,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('gudang.show', $gudangId)
                ->with('success', 'Rak berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan rak: ' . $e->getMessage());
        }
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

        try {
            DB::transaction(function () use ($rak, $validated) {
                $rak->update($validated);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'update',
                    'subjek_tipe' => 'gudang_rak',
                    'subjek_id' => $rak->id,
                    'deskripsi' => 'Memperbarui data rak: ' . $rak->nama_rak,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('gudang.show', $gudangId)
                ->with('success', 'Data rak berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui rak: ' . $e->getMessage());
        }
    }

    public function destroyRak($gudangId, $rakId)
    {
        $rak = GudangRak::where('gudang_id', $gudangId)->findOrFail($rakId);

        if ($rak->stokMasuk()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus rak yang memiliki catatan stok');
        }

        try {
            DB::transaction(function () use ($rak) {
                // Record activity before deletion
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'delete',
                    'subjek_tipe' => 'gudang_rak',
                    'subjek_id' => $rak->id,
                    'deskripsi' => 'Menghapus rak: ' . $rak->nama_rak,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $rak->delete();
            });

            return back()->with('success', 'Rak berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus rak: ' . $e->getMessage());
        }
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