<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\ProdukHilang;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\KategoriProduk;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    // DashboardController.php
public function index()
{
    // Summary cards data
    $produkCount = Produk::count();
    $kategoriCount = KategoriProduk::count();
    $customerCount = Customer::count();
    $supplierCount = Supplier::count();

    // Monthly stock movement data
    $stokMasukSummary = StokMasuk::select(
        DB::raw("DATE_FORMAT(tanggal_masuk, '%Y-%m') as bulan"),
        DB::raw("SUM(kuantitas) as total")
    )
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->get();

    $stokKeluarSummary = StokKeluar::select(
        DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
        DB::raw("SUM(kuantitas) as total")
    )
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->get();

    $produkHilang = ProdukHilang::select(
        DB::raw("DATE_FORMAT(tanggal_lapor, '%Y-%m') as bulan"),
        DB::raw("SUM(jumlah_hilang) as total")
    )
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->get();

    // Top products by stock (join with product_variant_details)
    $topProducts = DB::table('produks')
        ->join('produk_varian', 'produks.id', '=', 'produk_varian.produk_id')
        ->join('produk_varian_detail', 'produk_varian.id', '=', 'produk_varian_detail.varian_id')
        ->select(
            'produks.nama_produk',
            'produk_varian.varian',
            DB::raw('SUM(produk_varian_detail.stok) as total_stok')
        )
        ->groupBy('produks.id', 'produk_varian.id', 'produks.nama_produk', 'produk_varian.varian')
        ->orderBy('total_stok', 'desc')
        ->take(5)
        ->get();

    // Category distribution data
    $categoryDistribution = KategoriProduk::withCount('produk')
        ->orderBy('produk_count', 'desc')
        ->get();

    // Recent stock movements
    $recentStockIns = StokMasuk::with(['produk', 'varian'])
        ->orderBy('tanggal_masuk', 'desc')
        ->take(5)
        ->get();

    $recentStockOuts = StokKeluar::with(['produk', 'varian'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    return view('dashboard.index', compact(
        'produkCount',
        'kategoriCount',
        'customerCount',
        'supplierCount',
        'stokMasukSummary',
        'stokKeluarSummary',
        'produkHilang',
        'topProducts',
        'categoryDistribution',
        'recentStockIns',
        'recentStockOuts'
    ));
}
}