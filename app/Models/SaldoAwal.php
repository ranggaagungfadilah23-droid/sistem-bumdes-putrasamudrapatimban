<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
    protected $table = 'saldo_awal';

    protected $fillable = [
        'bulan',
        'tahun',
        'saldo_awal',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'saldo_awal' => 'integer',
        'bulan'      => 'integer',
        'tahun'      => 'integer',
    ];

    // Ambil saldo awal untuk bulan+tahun tertentu
    public static function getNominal(int $bulan, int $tahun): int
    {
        return (int) static::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->value('saldo_awal');
    }
}
