<?php

namespace App\Notifications;

use App\Models\Ulasan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UlasanMasukNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ulasan $ulasan) {}

    /**
     * Channel pengiriman notifikasi.
     * - 'database' → tersimpan di tabel notifications (bisa ditampilkan di UI mitra)
     * - 'mail'     → dikirim ke email mitra
     * Hapus salah satu jika tidak dibutuhkan.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    // ===== DATABASE NOTIFICATION =====

    public function toDatabase(object $notifiable): array
    {
        return [
            'judul'          => 'Ulasan Baru Masuk ⭐',
            'invoice_number' => $this->ulasan->invoice_number,
            'bintang'        => $this->ulasan->bintang,
            'bintang_string' => $this->ulasan->bintang_string,
            'pesan'          => $this->ulasan->pesan ?? '(Tidak ada komentar)',
            'customer_nama'  => $this->ulasan->customer->name ?? 'Customer',
            'url'            => route('mitra.ulasan.index'), // sesuaikan dengan route mitra kamu
        ];
    }

    // ===== EMAIL NOTIFICATION =====

    public function toMail(object $notifiable): MailMessage
    {
        $bintang = str_repeat('★', $this->ulasan->bintang) . str_repeat('☆', 5 - $this->ulasan->bintang);

        return (new MailMessage)
            ->subject('⭐ Ulasan Baru untuk Pesanan #' . $this->ulasan->invoice_number)
            ->greeting('Halo, ' . ($notifiable->name ?? 'Mitra') . '!')
            ->line('Kamu mendapatkan ulasan baru dari customer.')
            ->line('**Pesanan:** #' . $this->ulasan->invoice_number)
            ->line('**Penilaian:** ' . $bintang . ' (' . $this->ulasan->label_bintang . ')')
            ->line('**Komentar:** ' . ($this->ulasan->pesan ?? '(Tidak ada komentar)'))
            ->action('Lihat Ulasan', route('mitra.ulasan.index'))
            ->line('Terima kasih telah memberikan layanan terbaik!');
    }
}
