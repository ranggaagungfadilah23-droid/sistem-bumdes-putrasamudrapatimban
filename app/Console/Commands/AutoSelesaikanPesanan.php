<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use Carbon\Carbon;

class AutoSelesaikanPesanan extends Command
{
    protected $signature = 'pesanan:auto-selesai';
    protected $description = 'Otomatis selesaikan pesanan yang sudah dikirim lebih dari 3 hari';

    public function handle()
    {
        Transaksi::where('status_pengiriman', 'dikirim')
            ->where('dikirim_at', '<=', Carbon::now()->subDays(3))
            ->update([
                'status_pengiriman' => 'selesai',
                'status_pembayaran' => 'Lunas',
            ]);

        $this->info('Pesanan auto-selesai berhasil dijalankan.');
    }
}
