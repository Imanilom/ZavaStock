<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVarian;
use App\Models\ProdukDetail;
use App\Models\KategoriProduk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index(Request $request)
{
    // Start building the query with eager loading
    $query = Produk::with(['varian.detail']) // Only load necessary relationships
                  ->withCount(['varian as total_varian'])
                  ->latest();

    // Apply search filter if present
    if ($request->has('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('nama_produk', 'like', '%'.$searchTerm.'%')
              ->orWhere('sku', 'like', '%'.$searchTerm.'%')
              ->orWhere('kategori', 'like', '%'.$searchTerm.'%');
        });
    }

    // Apply category filter if present
    if ($request->filled('category')) {
        $query->where('kategori', $request->category);
    }

    // Apply status filter if present
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Paginate the results
    $produks = $query->paginate(10);

    // Calculate total stock for each product
    $produks->each(function ($produk) {
        $produk->total_stok = $produk->varian->sum(function ($varian) {
            return $varian->detail->sum('stok');
        });
    });

    // Get distinct categories for filter dropdown
    $categories = Produk::distinct()->pluck('kategori')->filter();

    return view('produk.index', [
        'produks' => $produks,
        'categories' => $categories,
        'search' => $request->search,
        'selectedCategory' => $request->category,
        'selectedStatus' => $request->status
    ]);
}
    public function create()
    {
        $kategoriProduks = KategoriProduk::pluck('nama_kategori', 'nama_kategori');
        $suppliers = Supplier::all();
        return view('produk.create', compact('kategoriProduks', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:produks',
            'nama_produk' => 'required',
            'kategori' => 'required',
            'foto' => 'nullable|image|max:2048',
            'varian.*.nama' => 'required',
            'varian.*.harga_jual' => 'required|numeric',
            'varian.*.harga_beli' => 'required|numeric',
            'varian.*.detail.*.nama' => 'required',
            'varian.*.detail.*.stok' => 'required|numeric',
        ]);

        // Upload foto produk
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('produk', 'public');
        }

        // Buat produk utama
        $produk = Produk::create([
            'user_id' => auth()->id(),
            'sku' => $request->sku,
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'bahan' => $request->bahan,
            'status' => $request->status ?? 'AKTIF',
            'deskripsi' => $request->deskripsi,
            'foto' => $foto
        ]);

        // Tambahkan varian produk
        foreach ($request->varian as $varianData) {
            $varian = ProdukVarian::create([
                'produk_id' => $produk->id,
                'varian' => $varianData['nama'],
                'harga_jual' => $varianData['harga_jual'],
                'harga_beli' => $varianData['harga_beli'],
                'diskon' => $varianData['diskon'] ?? null,
                'satuan' => $varianData['satuan'] ?? null,
                'panjang' => $varianData['panjang'] ?? null,
                'lebar' => $varianData['lebar'] ?? null,
                'tinggi' => $varianData['tinggi'] ?? null,
                'berat' => $varianData['berat'] ?? null,
            ]);

            // Pastikan detail ada dan berupa array
            if (isset($varianData['detail']) && is_array($varianData['detail'])) {
                foreach ($varianData['detail'] as $detailData) {
                    ProdukDetail::create([
                        'kode_detail' => Str::random(10),
                        'varian_id' => $varian->id,
                        'detail' => $detailData['nama'],
                        'stok' => $detailData['stok'],
                    ]);
                }
            }
        }

        // Sync suppliers
        if ($request->supplier_ids) {
            $produk->suppliers()->sync($request->supplier_ids);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = Produk::with(['varian.detail', 'suppliers'])->findOrFail($id);
        $kategoriProduks = KategoriProduk::pluck('nama_kategori', 'nama_kategori');
        $suppliers = Supplier::all();
        
        return view('produk.edit', compact('produk', 'kategoriProduks', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::with('varian.detail')->findOrFail($id);

        $request->validate([
            'sku' => 'required|unique:produks,sku,' . $produk->id,
            'nama_produk' => 'required',
            'kategori' => 'required',
            'foto' => 'nullable|image|max:2048',
            'varian.*.nama' => 'required',
            'varian.*.harga_jual' => 'required|numeric',
            'varian.*.harga_beli' => 'required|numeric',
            'varian.*.detail.*.nama' => 'required',
            'varian.*.detail.*.stok' => 'required|numeric',
        ]);

        // Update foto produk jika ada
        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $foto = $request->file('foto')->store('produk', 'public');
            $produk->foto = $foto;
        }

        // Update data produk utama
        $produk->update([
            'sku' => $request->sku,
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'bahan' => $request->bahan,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        // Proses varian dan detail
        $existingVarianIds = [];
        $existingDetailIds = [];

        foreach ($request->varian as $varianData) {
            // Update atau buat varian baru
            $varian = ProdukVarian::updateOrCreate(
                [
                    'id' => $varianData['id'] ?? null,
                    'produk_id' => $produk->id
                ],
                [
                    'varian' => $varianData['nama'],
                    'harga_jual' => $varianData['harga_jual'],
                    'harga_beli' => $varianData['harga_beli'],
                    'diskon' => $varianData['diskon'] ?? null,
                    'satuan' => $varianData['satuan'] ?? null,
                    'panjang' => $varianData['panjang'] ?? null,
                    'lebar' => $varianData['lebar'] ?? null,
                    'tinggi' => $varianData['tinggi'] ?? null,
                    'berat' => $varianData['berat'] ?? null,
                ]
            );

            $existingVarianIds[] = $varian->id;

            // Proses detail untuk varian ini
            if (isset($varianData['detail']) && is_array($varianData['detail'])) {
                foreach ($varianData['detail'] as $detailData) {
                    $detail = ProdukDetail::updateOrCreate(
                        [
                            'id' => $detailData['id'] ?? null,
                            'varian_id' => $varian->id
                        ],
                        [
                            'kode_detail' => $detailData['kode_detail'] ?? Str::random(10),
                            'detail' => $detailData['nama'],
                            'stok' => $detailData['stok'],
                        ]
                    );
                    $existingDetailIds[] = $detail->id;
                }
            }
        }

        // Hapus varian yang tidak ada di request
        ProdukVarian::where('produk_id', $produk->id)
            ->whereNotIn('id', $existingVarianIds)
            ->delete();

        // Hapus detail yang tidak ada di request
        ProdukDetail::whereIn('varian_id', $existingVarianIds)
            ->whereNotIn('id', $existingDetailIds)
            ->delete();

        // Sync supplier
        $produk->suppliers()->sync($request->supplier_ids ?? []);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        
        // Hapus foto produk
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }
        
        // Hapus semua varian dan detail terkait
        $produk->varian()->delete();
        $produk->delete();
        
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    // API untuk mendapatkan data varian berdasarkan produk
    public function getVarian($produkId)
    {
        $varian = ProdukVarian::where('produk_id', $produkId)->get();
        return response()->json($varian);
    }

    // API untuk mendapatkan data detail berdasarkan varian
    public function getdetail($varianId)
    {
        $detail = Produkdetail::where('varian_id', $varianId)->get();
        return response()->json($detail);
    }
}