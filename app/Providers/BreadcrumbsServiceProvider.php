<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Home
        Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
            $trail->push('Home', route('home'));
        });

        // Dashboard
        Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
            $trail->parent('home');
            $trail->push('Dashboard', route('dashboard'));
        });

        // Produk
        Breadcrumbs::for('produk.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Produk', route('produk.index'));
        });

        Breadcrumbs::for('produk.create', function (BreadcrumbTrail $trail) {
            $trail->parent('produk.index');
            $trail->push('Tambah Produk', route('produk.create'));
        });

        Breadcrumbs::for('produk.edit', function (BreadcrumbTrail $trail, $produk) {
            $trail->parent('produk.index');
            $trail->push('Edit Produk', route('produk.edit', $produk));
        });

        // Produk Hilang
        Breadcrumbs::for('produk-hilang.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Produk Hilang', route('produk-hilang.index'));
        });

        Breadcrumbs::for('produk-hilang.create', function (BreadcrumbTrail $trail) {
            $trail->parent('produk-hilang.index');
            $trail->push('Lapor Produk Hilang', route('produk-hilang.create'));
        });

        Breadcrumbs::for('produk-hilang.edit', function (BreadcrumbTrail $trail, $produkHilang) {
            $trail->parent('produk-hilang.index');
            $trail->push('Edit Laporan', route('produk-hilang.edit', $produkHilang));
        });

        Breadcrumbs::for('produk-hilang.show', function (BreadcrumbTrail $trail, $produkHilang) {
            $trail->parent('produk-hilang.index');
            $trail->push('Detail Laporan', route('produk-hilang.show', $produkHilang));
        });

        // Admin Management
        Breadcrumbs::for('admin.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Admin Management', route('admin.index'));
        });

        // Customer Management
        Breadcrumbs::for('customer.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Customer Management', route('customer.index'));
        });

        // Supplier Management
        Breadcrumbs::for('supplier.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Supplier Management', route('supplier.index'));
        });

        // Gudang Management
        Breadcrumbs::for('gudang.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Gudang Management', route('gudang.index'));
        });

        // Kategori Produk
        Breadcrumbs::for('kategori_produk.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Kategori Produk', route('kategori_produk.index'));
        });

        // Stok Masuk
        Breadcrumbs::for('stok-masuk.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Stok Masuk', route('stok-masuk.index'));
        });

        // Stok Keluar
        Breadcrumbs::for('stok-keluar.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Stok Keluar', route('stok-keluar.index'));
        });

        // Stok Opname
        Breadcrumbs::for('stok-opname.index', function (BreadcrumbTrail $trail) {
            $trail->parent('dashboard');
            $trail->push('Stok Opname', route('stok-opname.index'));
        });
    }
}