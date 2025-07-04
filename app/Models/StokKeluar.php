<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StokKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'produk_id',
        'varian_id',
        'detail_id',
        'gudang_id',
        'rak',
        'customer_id',
        'kuantitas',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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

    /**
     * Approve stok keluar dan update stok produk terkait
     */
    public function approve($approver)
    {
        DB::transaction(function () use ($approver) {
            // Update status dan data approval
            $this->update([
                'status' => 'approved',
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            // Kurangi stok sesuai detail, varian, atau produk
            $this->decreaseStock();
        });
    }

    /**
     * Kurangi stok produk/varian/detail sesuai stok keluar
     */
    protected function decreaseStock()
    {
        $qty = $this->kuantitas;

        if ($this->detail_id) {
            $detail = ProdukDetail::find($this->detail_id);
            if ($detail && $detail->stok >= $qty) {
                $detail->decrement('stok', $qty);
            } else {
                throw new \Exception('Stok detail tidak cukup');
            }
        } elseif ($this->varian_id) {
            $varian = ProdukVarian::find($this->varian_id);
            if ($varian) {
                // Misal kurangi stok detail pertama varian jika ada
                $detail = $varian->detail->first();
                if ($detail && $detail->stok >= $qty) {
                    $detail->decrement('stok', $qty);
                } else {
                    throw new \Exception('Stok varian tidak cukup');
                }
            }
        } else {
            $produk = Produk::find($this->produk_id);
            if ($produk) {
                // Misal kurangi stok detail pertama varian pertama produk jika ada
                $varian = $produk->varian->first();
                if ($varian) {
                    $detail = $varian->detail->first();
                    if ($detail && $detail->stok >= $qty) {
                        $detail->decrement('stok', $qty);
                    } else {
                        throw new \Exception('Stok produk tidak cukup');
                    }
                }
            }
        }
    }

}