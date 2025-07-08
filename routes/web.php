<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukHilangController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');  // Make sure this line has ->name('home')

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// Rute untuk customer
Route::middleware(['auth'])->group(function () {
    // routes/web.php
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk - Customer bisa CRUD
    Route::prefix('produk')->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/{id}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    });

    // Produk Hilang - Customer bisa CRUD
    Route::prefix('produk-hilang')->group(function () {
        Route::get('/', [ProdukHilangController::class, 'index'])->name('produk-hilang.index');
        Route::get('/create', [ProdukHilangController::class, 'create'])->name('produk-hilang.create');
        Route::post('/', [ProdukHilangController::class, 'store'])->name('produk-hilang.store');
        Route::get('/{id}', [ProdukHilangController::class, 'show'])->name('produk-hilang.show');
        Route::get('/{id}/edit', [ProdukHilangController::class, 'edit'])->name('produk-hilang.edit');
        Route::put('/{id}', [ProdukHilangController::class, 'update'])->name('produk-hilang.update');
        Route::delete('/{id}', [ProdukHilangController::class, 'destroy'])->name('produk-hilang.destroy');
        Route::get('/search', [ProdukHilangController::class, 'searchProduk'])->name('produk-hilang.search');
    });
});

// Rute khusus admin
Route::middleware(['auth', 'admin'])->group(function () {

    // Hanya admin yang bisa approve/reject
    Route::prefix('produk-hilang')->group(function () {
        Route::put('/{id}/verify', [ProdukHilangController::class, 'verify'])->name('produk-hilang.verify');
        Route::put('/{id}/reject', [ProdukHilangController::class, 'reject'])->name('produk-hilang.reject');
    });

    Route::prefix('stok-masuk')->group(function () {
        Route::put('/{id}/approve', [StokMasukController::class, 'approve'])->name('stok-masuk.approve');
        Route::put('/{id}/reject', [StokMasukController::class, 'reject'])->name('stok-masuk.reject');
    });

    Route::prefix('stok-keluar')->group(function () {
        Route::post('/{id}/approve', [StokKeluarController::class, 'approve'])->name('stok-keluar.approve');
        Route::post('/{id}/reject', [StokKeluarController::class, 'reject'])->name('stok-keluar.reject');
    });

    Route::prefix('stok-opname')->group(function () {
        Route::post('/{id}/approve', [StokOpnameController::class, 'approve'])->name('stok-opname.approve');
    });

    // Manajemen Admin - Hanya admin
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/{id}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
        Route::delete('/delete/multiple', [AdminController::class, 'deleteMultiple'])->name('admin.delete.multiple');
        Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
    });

    // Manajemen Customer - Hanya admin
    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/{id}', [CustomerController::class, 'show'])->name('customer.show');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
        Route::post('/customer/delete/multiple', [CustomerController::class, 'deleteMultiple'])->name('customer.delete.multiple');
    });

    // Manajemen Supplier - Hanya admin
    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/{id}', [SupplierController::class, 'show'])->name('supplier.show');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
        Route::delete('/delete/multiple', [SupplierController::class, 'deleteMultiple'])->name('supplier.delete.multiple');
        Route::get('/export', [SupplierController::class, 'export'])->name('supplier.export');
    });

    // Gudang - Hanya admin
    Route::prefix('gudang')->group(function () {
        Route::get('/', [GudangController::class, 'index'])->name('gudang.index');
        Route::get('/create', [GudangController::class, 'create'])->name('gudang.create');
        Route::post('/', [GudangController::class, 'store'])->name('gudang.store');
        Route::get('/{id}', [GudangController::class, 'show'])->name('gudang.show');
        Route::get('/{id}/edit', [GudangController::class, 'edit'])->name('gudang.edit');
        Route::put('/{id}', [GudangController::class, 'update'])->name('gudang.update');
        Route::delete('/{id}', [GudangController::class, 'destroy'])->name('gudang.destroy');
        Route::get('/{id}/laporan-stok', [GudangController::class, 'laporanStok'])->name('gudang.laporan-stok');
        // Rak Routes
        Route::get('/{gudangId}/rak/create', [GudangController::class, 'createRak'])->name('gudang.rak.create');
        Route::post('/{gudangId}/rak', [GudangController::class, 'storeRak'])->name('gudang.rak.store');
        Route::get('/{gudangId}/rak/{rakId}/edit', [GudangController::class, 'editRak'])->name('gudang.rak.edit');
        Route::put('/{gudangId}/rak/{rakId}', [GudangController::class, 'updateRak'])->name('gudang.rak.update');
        Route::delete('/{gudangId}/rak/{rakId}', [GudangController::class, 'destroyRak'])->name('gudang.rak.destroy');
    });
    
    // API Routes
    Route::get('api/gudang/{gudangId}/rak', [GudangController::class, 'getRak'])->name('api.gudang.rak');
    
    // Kategori Produk - Hanya admin
    Route::prefix('kategori-produk')->group(function () {
        Route::get('/', [KategoriProdukController::class, 'index'])->name('kategori_produk.index');
        Route::get('/create', [KategoriProdukController::class, 'create'])->name('kategori_produk.create');
        Route::post('/', [KategoriProdukController::class, 'store'])->name('kategori_produk.store');
        Route::get('/{id}', [KategoriProdukController::class, 'show'])->name('kategori_produk.show');
        Route::get('/{id}/edit', [KategoriProdukController::class, 'edit'])->name('kategori_produk.edit');
        Route::put('/{id}', [KategoriProdukController::class, 'update'])->name('kategori_produk.update');
        Route::delete('/{id}', [KategoriProdukController::class, 'destroy'])->name('kategori_produk.destroy');
        Route::delete('/delete/multiple', [KategoriProdukController::class, 'deleteMultiple'])->name('kategori_produk.delete.multiple');
    });

    // Stok Masuk - Hanya admin
    Route::prefix('stok-masuk')->group(function () {
        Route::get('/', [StokMasukController::class, 'index'])->name('stok-masuk.index');
        Route::get('/create', [StokMasukController::class, 'create'])->name('stok-masuk.create');
        Route::post('', [StokMasukController::class, 'store'])->name('stok-masuk.store');
        Route::get('/{id}', [StokMasukController::class, 'show'])->name('stok-masuk.show');
        Route::get('/search', [StokMasukController::class, 'searchProduk'])->name('stok-masuk.search');
        Route::get('/rak/{gudangId}', [StokMasukController::class, 'getRakByGudang'])->name('stok-masuk.rak');
    });

    // Stok Keluar - Hanya admin
    Route::prefix('stok-keluar')->group(function () {
        Route::get('/', [StokKeluarController::class, 'index'])->name('stok-keluar.index');
        Route::get('/create', [StokKeluarController::class, 'create'])->name('stok-keluar.create');
        Route::post('/', [StokKeluarController::class, 'store'])->name('stok-keluar.store');
        Route::get('/{id}', [StokKeluarController::class, 'show'])->name('stok-keluar.show');
        Route::get('/{id}/edit', [StokKeluarController::class, 'edit'])->name('stok-keluar.edit');
        Route::put('/{id}', [StokKeluarController::class, 'update'])->name('stok-keluar.update');
        Route::delete('/{id}', [StokKeluarController::class, 'destroy'])->name('stok-keluar.destroy');
    });

    // Stok Opname - Hanya admin
    Route::prefix('stok-opname')->group(function () {
        Route::get('/', [StokOpnameController::class, 'index'])->name('stok-opname.index');
        Route::get('/create', [StokOpnameController::class, 'create'])->name('stok-opname.create');
        Route::post('/', [StokOpnameController::class, 'store'])->name('stok-opname.store');
        Route::get('/{id}', [StokOpnameController::class, 'show'])->name('stok-opname.show');
        Route::get('/{id}/edit', [StokOpnameController::class, 'edit'])->name('stok-opname.edit');
        Route::put('/{id}', [StokOpnameController::class, 'update'])->name('stok-opname.update');
        Route::delete('/{id}', [StokOpnameController::class, 'destroy'])->name('stok-opname.destroy');
        Route::get('/{id}/export', [StokOpnameController::class, 'exportPdf'])->name('stok-opname.export');
    });
});