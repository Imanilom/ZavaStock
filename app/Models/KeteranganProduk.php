<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeteranganProduk extends Model
{
    use HasFactory;
    
    protected $table = 'keterangan_produk';
    protected $guarded = [];

    public function produkHilang()
    {
        return $this->hasMany(ProdukHilang::class);
    }

    public function scopeNeedsVerification($query)
    {
        return $query->where('butuh_verifikasi', true);
    }
}