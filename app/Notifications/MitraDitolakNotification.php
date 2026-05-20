<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MitraDitolakNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Informasi Pendaftaran Mitra BUMDes')
            ->greeting('Halo, ' . $notifiable->name . '.')
            ->line('Terima kasih telah mengajukan pendaftaran Mitra BUMDes untuk usaha "' . $notifiable->nama_usaha . '".')
            ->line('Setelah melakukan peninjauan terhadap berkas dan data yang Anda kirimkan, mohon maaf kami belum dapat menyetujui pendaftaran Anda saat ini.')
            ->line('Hal ini mungkin disebabkan oleh berkas (KTP/SKU) yang buram, tidak valid, atau data yang tidak sesuai.')
            ->line('Jika Anda merasa ini adalah kesalahan, silakan hubungi tim Admin BUMDes Patimban atau lakukan pendaftaran ulang dalam 30 hari dengan berkas yang lebih jelas.')
            ->line('Terima kasih atas partisipasi Anda.')
            ->salutation('Salam hangat, Admin BUMDes Patimban');
    }
}
