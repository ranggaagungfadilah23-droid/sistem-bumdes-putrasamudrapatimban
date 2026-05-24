<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PendaftarBaruNotification extends Notification
{
    use Queueable;
    protected $nama_pemilik;
    protected $nama_usaha;

    public function __construct($nama_pemilik, $nama_usaha)
    {
        $this->nama_pemilik = $nama_pemilik;
        $this->nama_usaha = $nama_usaha;
    }

    public function via($notifiable)
    {
        return ['database']; // Wajib ke database agar masuk ke tabel notifications
    }

    public function toDatabase($notifiable)
    {
        // Data ini yang akan dibaca oleh $notif->data['title'] dan ['message'] di Blade kamu
        return [
            'title'   => 'Pendaftar Mitra Baru',
            'message' => "Ada pendaftar baru atas nama <b>{$this->nama_pemilik}</b> dengan usaha <b>{$this->nama_usaha}</b>. Harap segera lakukan verifikasi dokumen SKU dan KTP.",
        ];
    }
}
