<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class GudangRak extends Model
{
    use HasFactory;

    protected $table = 'gudang_rak';
    protected $fillable = [
        'gudang_id',
        'kode_rak',
        'nama_rak',
        'deskripsi',
        'kapasitas'
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class, 'rak', 'kode_rak');
    }
}