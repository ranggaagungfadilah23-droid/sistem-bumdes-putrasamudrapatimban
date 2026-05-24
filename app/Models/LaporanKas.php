<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKas extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit agar Laravel tidak mencari tabel 'laporan_kas_s'
    protected $table = 'laporan_kas';

    // Mengizinkan semua kolom diisi (kecuali ID)
    protected $guarded = ['id'];

    // Jika kamu ingin menggunakan $fillable secara spesifik, gunakan ini dan hapus $guarded:
    // protected $fillable = [
    //     'dikirim_oleh', 'bulan_aktif', 'total_kas_masuk',
    //     'total_omzet', 'total_mitra', 'catatan', 'status', 'dikirim_at'
    // ];
}
