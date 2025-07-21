<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customers';
    
    // ✅ Eksplisit definisikan fillable fields
    protected $fillable = [
        'user_id',
        'nama',
        'telepon',
        'alamat',
        'catatan',
        'email',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Accessor untuk mendapatkan email dari relasi user
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }
}