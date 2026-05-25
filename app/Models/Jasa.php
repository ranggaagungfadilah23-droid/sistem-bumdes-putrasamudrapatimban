<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Jasa extends Model
{
    use HasFactory;

    protected $table = 'jasas';

    protected $fillable = [
        'user_id',
        'nama_jasa',
        'harga',
        'satuan',
        'deskripsi',
        'gambar',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
