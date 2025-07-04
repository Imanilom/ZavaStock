<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;
    protected $table = 'kategori_produks';
    protected $fillable = ['id_kategori', 'nama_kategori', 'deskripsi', 'jenis_kategori'];
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = [];

    public function subKategori()
    {
        return $this->hasMany(SubkategoriMakanan::class, 'id_kategori', 'id_kategori');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori', 'nama_kategori');
    }
}
