<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItemRiwayat extends Model
{
    protected $table = 'transaksi_item_riwayats';
    protected $guarded = [];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiRiwayat::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function varian()
    {
        return $this->belongsTo(ProdukVarian::class);
    }

    public function detail()
    {
        return $this->belongsTo(ProdukVarianDetail::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
}