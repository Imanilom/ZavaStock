<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    
    protected $table = 'produks';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori', 'nama_kategori');
    }

    public function varian()
    {
        return $this->hasMany(ProdukVarian::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'produk_supplier');
    }

    // Total stok semua varian dan detail
    public function getTotalStokAttribute()
    {
        return $this->varian->sum(function($varian) {
            return $varian->detail->sum('stok');
        });
    }
}

class ProdukVarian extends Model
{
    use HasFactory;
    
    protected $table = 'produk_varian';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function detail()
    {
        return $this->hasMany(ProdukDetail::class, 'varian_id');
    }

    // Total stok semua detail dalam varian ini
    public function getTotalStokAttribute()
    {
        return $this->detail->sum('stok');
    }
}

class ProdukDetail extends Model
{
    use HasFactory;
    
    protected $table = 'produk_varian_detail';
    protected $guarded = [];

    public function varian()
    {
        return $this->belongsTo(ProdukVarian::class, 'varian_id');
    }
}

class ProdukSupplier extends Model
{
    use HasFactory;
    
    protected $table = 'produk_supplier';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}