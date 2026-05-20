<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Tambahkan ShouldQueue jika ingin pengiriman email berjalan di background
class StatusMitraNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Kita harus menangkap data user di constructor agar bisa dipakai di toMail
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Gunakan channel 'mail'
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Format Isi Email
     */
    public function toMail($notifiable): MailMessage
    {
        // Ambil status atau role dari model user
        $status = $this->user->status;
        $role = $this->user->role;

        // Logika pesan berdasarkan kondisi akun
        $pesan = match(true) {
            $role === 'mitra' && $status === 'approved' => 'Selamat! Akun Anda telah disetujui. Sekarang Anda sudah bisa mulai berjualan dan mengakses fitur Dashboard Mitra.',
            $status === 'pending'  => 'Pendaftaran Anda sebagai Mitra BUMDes Patimban telah kami terima. Saat ini data Anda sedang dalam proses review oleh tim kami.',
            $status === 'rejected' => 'Mohon maaf, setelah melakukan peninjauan dokumen, pendaftaran Anda belum dapat kami setujui untuk saat ini.',
            default               => 'Ada pembaruan mengenai status pendaftaran akun Anda di sistem BUMDes.'
        };

        return (new MailMessage)
            ->subject('Update Status Pendaftaran BUMDes Patimban')
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line($pesan)
            ->action('Cek Status Akun Sekarang', url('/dashboard'))
            ->line('Jika Anda memiliki pertanyaan, silakan hubungi kami melalui nomor WhatsApp resmi BUMDes.')
            ->salutation('Salam hangat, Admin BUMDes Patimban');
    }

    /**
     * Jika ingin menyimpan notifikasi ke database juga (opsional)
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'status' => $this->user->status,
        ];
    }
}
