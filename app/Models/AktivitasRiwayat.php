<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasRiwayat extends Model
{
    protected $table = 'aktivitas_riwayats';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}