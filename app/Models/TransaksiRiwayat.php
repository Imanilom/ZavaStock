<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiRiwayat extends Model
{
    protected $table = 'transaksi_riwayats';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransaksiItemRiwayat::class, 'transaksi_id', 'id')
                    ->where('jenis_transaksi', $this->jenis_transaksi);
    }
}