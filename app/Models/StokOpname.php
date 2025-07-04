<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokOpname extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'produk_id',
        'varian_id',
        'detail_id',
        'gudang_id',
        'rak',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'catatan',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsTo(ProdukDetail::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // Hitung selisih otomatis
    public function getSelisihAttribute()
    {
        return $this->stok_fisik - $this->stok_sistem;
    }
}