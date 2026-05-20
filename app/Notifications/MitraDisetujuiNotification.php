<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MitraDisetujuiNotification extends Notification
{
    use Queueable;

    /**
     * Tentukan channel pengiriman (Email)
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Format isi email
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Ambil nama usaha dari relasi mitra, jika tidak ada pakai default '-'
        $namaUsaha = $notifiable->mitra->nama_usaha ?? '-';

        return (new MailMessage)
            ->subject('Selamat! Pendaftaran Mitra BUMDes Disetujui')
            ->greeting('Selamat, ' . $notifiable->name . '!')
            ->line('Kabar gembira! Berkas pendaftaran untuk usaha "' . $namaUsaha . '" telah kami tinjau dan dinyatakan MEMENUHI SYARAT.')
            ->line('Akun Mitra Anda sekarang sudah AKTIF.')
            ->line('Anda sudah bisa login ke sistem BUMDes dan mulai mengelola produk atau layanan Anda.')
            ->action('Login ke Dashboard Mitra', url('/login'))
            ->line('Selamat bergabung dan semoga sukses bersama BUMDes Putra Samudra Patimban!')
            ->salutation('Salam hangat, Admin BUMDes Patimban');
    }
}
