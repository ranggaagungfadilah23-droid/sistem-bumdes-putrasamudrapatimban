<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'mitra_id',
        'bintang',
        'pesan',
        'balasan_mitra',
        'dibalas_at',
    ];

    protected $casts = [
        'dibalas_at' => 'datetime',
        'bintang'    => 'integer',
    ];

    // ===== RELASI =====

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'invoice_number', 'invoice_number');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    // ===== HELPER =====

    /**
     * Label bintang untuk ditampilkan
     */
    public function getLabelBintangAttribute(): string
    {
        return match($this->bintang) {
            1 => 'Mengecewakan',
            2 => 'Kurang Memuaskan',
            3 => 'Cukup Baik',
            4 => 'Bagus',
            5 => 'Luar Biasa',
            default => '-',
        };
    }

    /**
     * Render bintang sebagai string ★★★☆☆
     */
    public function getBintangStringAttribute(): string
    {
        return str_repeat('★', $this->bintang) . str_repeat('☆', 5 - $this->bintang);
    }

    
}
