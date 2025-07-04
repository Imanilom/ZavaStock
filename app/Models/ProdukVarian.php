<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
