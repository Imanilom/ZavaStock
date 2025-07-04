<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukHilang extends Model
{
    use HasFactory;
    
    protected $table = 'produk_hilang';
    protected $guarded = [];
    protected $casts = [
        'tanggal_kejadian' => 'date',
        'tanggal_lapor' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Status constants
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_REPORTED = 'REPORTED';
    const STATUS_VERIFIED = 'VERIFIED';
    const STATUS_REJECTED = 'REJECTED';

      public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function keterangan()
    {
        return $this->belongsTo(KeteranganProduk::class, 'keterangan_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function varian()
    {
        return $this->belongsTo(ProdukVarian::class, 'varian_id')->withTrashed();
    }

    public function detail()
    {
        return $this->belongsTo(ProdukDetail::class, 'detail_id')->withTrashed();
    }
    
    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeReported($query)
    {
        return $query->where('status', self::STATUS_REPORTED);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopePendingVerification($query)
    {
        return $query->whereHas('keterangan', function($q) {
            $q->where('butuh_verifikasi', true);
        })->where('status', self::STATUS_REPORTED);
    }

    // Status checks
    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isReported()
    {
        return $this->status === self::STATUS_REPORTED;
    }

    public function isVerified()
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function needsVerification()
    {
        return $this->keterangan->butuh_verifikasi && !$this->isVerified();
    }

    public function getStatusLabelAttribute()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_REPORTED => 'Dilaporkan',
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_REJECTED => 'Ditolak'
        ][$this->status] ?? $this->status;
    }
}