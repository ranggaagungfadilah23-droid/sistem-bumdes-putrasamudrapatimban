<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapPengeluaran extends Model
{
    protected $table = 'rekap_pengeluaran';

    protected $fillable = [
        'tipe_periode',
        'minggu_ke',
        'bulan',
        'tahun',
        'kategori',
        'keterangan',
        'detail_item',
        'total_pengeluaran',
        'tanggal',
        'created_by',
    ];

    protected $casts = [
        'detail_item'       => 'array',
        'total_pengeluaran' => 'integer',
        'minggu_ke'         => 'integer',
        'bulan'             => 'integer',
        'tahun'             => 'integer',
        'tanggal'           => 'date',
    ];

    // Total pengeluaran bulan tertentu
    public static function totalBulan(int $bulan, int $tahun): int
    {
        return (int) static::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('total_pengeluaran');
    }

    // Total pengeluaran mingguan tertentu
    public static function totalMinggu(int $minggu, int $bulan, int $tahun): int
    {
        return (int) static::where('tipe_periode', 'mingguan')
            ->where('minggu_ke', $minggu)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->sum('total_pengeluaran');
    }
}
