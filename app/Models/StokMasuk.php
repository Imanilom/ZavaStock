<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_masuk';
    protected $fillable = [
        'no_transaksi',
        'user_id',
        'produk_id',
        'varian_id',
        'detail_id',
        'supplier_id',
        'gudang_id',
        'kuantitas',
        'harga_satuan',
        'total_harga',
        'tanggal_masuk',
        'tanggal_expired',
        'no_batch',
        'rak',
        'status',
        'catatan',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_expired' => 'date',
        'approved_at' => 'datetime',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->no_transaksi = self::generateTransactionNumber();
            $model->user_id = auth()->id();
        });

        static::saving(function ($model) {
            $model->total_harga = $model->harga_satuan * $model->kuantitas;
        });
    }

    protected static function generateTransactionNumber()
    {
        $prefix = 'SM/' . date('Ymd') . '/';
        $last = self::where('no_transaksi', 'like', $prefix . '%')->orderBy('no_transaksi', 'desc')->first();
        $number = $last ? (int)str_replace($prefix, '', $last->no_transaksi) + 1 : 1;
        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function approve(User $approver)
    {
        DB::transaction(function () use ($approver) {
            $this->update([
                'status' => self::STATUS_APPROVED,
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            $this->updateStock();
        });
    }

    public function reject(User $approver)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    protected function updateStock()
    {
        if ($this->detail_id) {
            // Update specific color stock
            $detail = ProdukDetail::find($this->detail_id);
            $detail->increment('stok', $this->kuantitas);
        } elseif ($this->varian_id) {
            // Update variant stock
            $varian = ProdukVarian::find($this->varian_id);
            if ($varian->detail->isNotEmpty()) {
                $varian->detail->first()->increment('stok', $this->kuantitas);
            }
        } else {
            // Update product stock
            $produk = Produk::find($this->produk_id);
            if ($produk->varian->isNotEmpty() && $produk->varian->first()->detail->isNotEmpty()) {
                $produk->varian->first()->detail->first()->increment('stok', $this->kuantitas);
            }
        }
    }
}