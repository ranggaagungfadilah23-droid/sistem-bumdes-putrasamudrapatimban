<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendaftaranSedangDitinjau extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
   public function toMail($notifiable)
{
    return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Pendaftaran Mitra BUMDes: Sedang Ditinjau')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Terima kasih telah melakukan verifikasi email.')
                ->line('Saat ini berkas pendaftaran Anda (KTP & SKU) telah masuk ke sistem kami dan sedang dalam tahap peninjauan oleh Admin.')
                ->line('Proses ini biasanya memakan waktu maksimal 1x24 jam.')
                ->line('Kami akan mengirimkan email kembali segera setelah akun Anda diaktifkan.')
                ->line('Terima kasih atas kesabaran Anda!')
                ->salutation('Salam hangat, Admin BUMDes Patimban');
}


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
