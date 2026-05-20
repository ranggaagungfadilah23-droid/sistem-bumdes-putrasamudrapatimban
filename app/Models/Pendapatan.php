<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    protected $fillable = [
        'transaksi_id',
        'mitra_id',
        'total_diterima',
        'keterangan',
        'tanggal_masuk',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}
