<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_wa',
        'gender',
        'alamat_lengkap',
    ];

    // Pelanggan itu MILIK User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
