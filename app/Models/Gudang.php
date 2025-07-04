<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gudang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gudangs';
    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon',
        'email',
        'user_id',
        'jenis',
        'aktif'
    ];

    public function rak()
    {
        return $this->hasMany(GudangRak::class);
    }

    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeUtama($query)
    {
        return $query->where('jenis', 'utama');
    }

    public function PenanggungJawab()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

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