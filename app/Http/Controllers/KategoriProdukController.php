<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use App\Models\AktivitasRiwayat;
use Illuminate\Support\Facades\DB;

class KategoriProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriProduk::query();

        // Add search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kategori', 'like', '%'.$search.'%')
                  ->orWhere('id_kategori', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kategori', 'like', '%'.$search.'%');
            });
        }

        $kategoriProduks = $query->paginate(10);
        return view('kategori_produk.index', compact('kategoriProduks'));
    }

    public function create()
    {
        return view('kategori_produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|string|max:10|unique:kategori_produks,id_kategori',
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'jenis_kategori' => 'required|in:Makanan,Minuman,Alat',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $kategori = KategoriProduk::create([
                    'id_kategori' => $request->id_kategori,
                    'nama_kategori' => $request->nama_kategori,
                    'deskripsi' => $request->deskripsi,
                    'jenis_kategori' => $request->jenis_kategori,
                ]);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'kategori_produk',
                    'subjek_id' => $kategori->id,
                    'deskripsi' => 'Menambahkan kategori produk baru: ' . $kategori->nama_kategori,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('kategori_produk.index')->with('success', 'Kategori produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan kategori produk: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $kategoriProduk = KategoriProduk::findOrFail($id);
        
        // Get related activities
        $aktivitas = AktivitasRiwayat::with('user')
            ->where('subjek_tipe', 'kategori_produk')
            ->where('subjek_id', $id)
            ->latest()
            ->get();

        return view('kategori_produk.show', compact('kategoriProduk', 'aktivitas'));
    }

    public function edit($id)
    {
        $kategoriProduk = KategoriProduk::findOrFail($id);
        return view('kategori_produk.edit', compact('kategoriProduk'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriProduk::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'jenis_kategori' => 'required|in:Makanan,Minuman,Alat',
        ]);

        try {
            DB::transaction(function () use ($request, $kategori) {
                $oldName = $kategori->nama_kategori;
                
                $kategori->update([
                    'nama_kategori' => $request->nama_kategori,
                    'deskripsi' => $request->deskripsi,
                    'jenis_kategori' => $request->jenis_kategori,
                ]);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'update',
                    'subjek_tipe' => 'kategori_produk',
                    'subjek_id' => $kategori->id,
                    'deskripsi' => 'Memperbarui kategori produk: ' . $oldName . ' menjadi ' . $kategori->nama_kategori,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            });

            return redirect()->route('kategori_produk.index')->with('success', 'Kategori produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui kategori produk: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $kategoriProduk = KategoriProduk::findOrFail($id);

        try {
            DB::transaction(function () use ($kategoriProduk) {
                // Record activity before deletion
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'delete',
                    'subjek_tipe' => 'kategori_produk',
                    'subjek_id' => $kategoriProduk->id,
                    'deskripsi' => 'Menghapus kategori produk: ' . $kategoriProduk->nama_kategori,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $kategoriProduk->delete();
            });

            return redirect()->route('kategori_produk.index')->with('success', 'Kategori produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kategori produk: ' . $e->getMessage());
        }
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            try {
                DB::transaction(function () use ($ids) {
                    $kategoris = KategoriProduk::whereIn('id', $ids)->get();
                    
                    foreach ($kategoris as $kategori) {
                        // Record activity for each deletion
                        AktivitasRiwayat::create([
                            'user_id' => auth()->id(),
                            'tipe_aktivitas' => 'delete',
                            'subjek_tipe' => 'kategori_produk',
                            'subjek_id' => $kategori->id,
                            'deskripsi' => 'Menghapus kategori produk (multiple): ' . $kategori->nama_kategori,
                            'ip_address' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ]);
                    }
                    
                    KategoriProduk::whereIn('id', $ids)->delete();
                });

                return redirect()->route('kategori_produk.index')->with('success', 'Beberapa kategori produk berhasil dihapus.');
            } catch (\Exception $e) {
                return redirect()->route('kategori_produk.index')->with('error', 'Gagal menghapus beberapa kategori: ' . $e->getMessage());
            }
        }

        return redirect()->route('kategori_produk.index')->with('error', 'Tidak ada kategori yang dipilih untuk dihapus.');
    }
}