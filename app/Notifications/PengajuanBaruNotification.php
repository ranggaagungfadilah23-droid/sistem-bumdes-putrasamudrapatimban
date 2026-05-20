<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PengajuanBaruNotification extends Notification
{
    use Queueable;

    public $pemohon; // Menyimpan data user yang mendaftar

    public function __construct($pemohon)
    {
        $this->pemohon = $pemohon;
    }

    // Gunakan 2 channel: Email dan Database (Lonceng)
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // 1. FORMAT UNTUK EMAIL ADMIN
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengajuan Mitra Baru: ' . $this->pemohon->nama_usaha)
            ->greeting('Halo Admin BUMDes!')
            ->line('Ada pengajuan mitra baru yang menunggu untuk ditinjau.')
            ->line('Nama Pemohon: ' . $this->pemohon->name)
            ->line('Nama Usaha: ' . $this->pemohon->nama_usaha)
            ->action('Tinjau Sekarang', url('/admin/pengajuan'))
            ->line('Harap segera lakukan verifikasi dokumen KTP & SKU.')
            ->salutation('Salam hangat, Admin BUMDes Patimban');
    }

    // 2. FORMAT UNTUK LONCENG DASHBOARD (DATABASE)
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Mitra Baru',
            'message' => $this->pemohon->name . ' telah mengajukan pendaftaran.',
            'url' => '/admin/pengajuan', // Link saat notif diklik
            'icon' => 'fas fa-file-signature',
        ];
    }

}
