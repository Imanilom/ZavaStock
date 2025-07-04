<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
