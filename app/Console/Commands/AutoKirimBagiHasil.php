<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mitra;
use App\Models\Bagihasil;
use App\Models\Pendapatan; // Sesuaikan dengan model tempat kamu menghitung omzet

class AutoKirimBagiHasil extends Command
{
    // Nama perintah untuk dijalankan
    protected $signature = 'bagihasil:autokirim';
    protected $description = 'Otomatis mengirim laporan bagi hasil mitra ke admin di akhir bulan';

    public function handle()
    {
        // 1. Ambil semua mitra
        $mitras = Mitra::all();
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        foreach ($mitras as $mitra) {
            // 2. Hitung omzet mitra HANYA untuk bulan ini
            $omzet = Pendapatan::where('mitra_id', $mitra->id)
                ->whereMonth('created_at', $bulanIni)
                ->whereYear('created_at', $tahunIni)
                ->sum('total_diterima');

            if ($omzet > 0) {
                // 3. Tentukan persen default (karena otomatis, tidak ada input dari Request)
                $persenBumdes = 10; // GANTI dengan persen potongan BUMDes yang berlaku
                $persenMitra  = 100 - $persenBumdes;

                // 4. Update jika sudah ada draf bulan ini, atau Create baru
                Bagihasil::updateOrCreate(
                    [
                        'mitra_id' => $mitra->id,
                        // PENTING: Cari berdasarkan bulan dan tahun agar data bulan lalu tidak tertimpa
                        'tanggal'  => now()->startOfMonth()
                    ],
                    [
                        'total_omzet'    => $omzet,
                        'persen_bumdes'  => $persenBumdes,
                        'persen_mitra'   => $persenMitra,
                        'nominal_bumdes' => $omzet * ($persenBumdes / 100),
                        'nominal_mitra'  => $omzet * ($persenMitra / 100),
                        'status'         => 'PENDING', // Masuk ke admin
                        'tanggal'        => now(),
                    ]
                );
            }
        }

        $this->info('Laporan bagi hasil otomatis berhasil dikirim ke Admin!');
    }
}
