<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI ---

    public function mitra() {
        return $this->hasOne(Mitra::class, 'user_id', 'id');
    }

    // Cukup satu fungsi pelanggan() saja
   public function pelanggan()
{
    return $this->hasOne(Pelanggan::class, 'user_id');
}

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    public function jasas()
    {
        return $this->hasMany(Jasa::class);
    }
}
