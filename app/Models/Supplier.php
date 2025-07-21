<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'user_id',
        'nama',
        'telepon',
        'alamat',
        'email',
        'catatan',
        'foto',
    ];
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class, 'vendor_id');
    }
}
