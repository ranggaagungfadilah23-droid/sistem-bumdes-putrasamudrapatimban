<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LaporanKasDikirim extends Notification
{
    public function __construct(
        protected string $bulanAktif,
        protected float  $totalKas,
        protected ?string $catatan = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul'       => 'Laporan Kas Masuk — ' . $this->bulanAktif,
            'pesan'       => 'Admin telah mengirimkan laporan kas BUMDes bulan ' . $this->bulanAktif
                           . '. Total kas masuk: Rp ' . number_format($this->totalKas, 0, ',', '.'),
            'catatan'     => $this->catatan,
            'total_kas'   => $this->totalKas,
            'bulan'       => $this->bulanAktif,
            'url'         => '/kepala-bumdes/monitoring-keuangan',
        ];
    }
}
