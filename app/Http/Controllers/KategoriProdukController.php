<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriProduk;

class KategoriProdukController extends Controller
{
    public function index()
    {
        $kategoriProduks = KategoriProduk::all();
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

        KategoriProduk::create([
            'id_kategori' => $request->id_kategori,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'jenis_kategori' => $request->jenis_kategori,
        ]);

        return redirect()->route('kategori_produk.index')->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kategoriProduk = KategoriProduk::findOrFail($id);
        return view('kategori_produk.show', compact('kategoriProduk'));
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

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'jenis_kategori' => $request->jenis_kategori,
        ]);

        return redirect()->route('kategori_produk.index')->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategoriProduk = KategoriProduk::findOrFail($id);
        $kategoriProduk->delete();

        return redirect()->route('kategori_produk.index')->with('success', 'Kategori produk berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            KategoriProduk::whereIn('id', $ids)->delete();
            return redirect()->route('kategori_produk.index')->with('success', 'Beberapa kategori produk berhasil dihapus.');
        }

        return redirect()->route('kategori_produk.index')->with('error', 'Tidak ada kategori yang dipilih untuk dihapus.');
    }
}
