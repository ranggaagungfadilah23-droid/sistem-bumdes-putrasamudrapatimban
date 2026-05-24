<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusPesananNotification extends Notification
{
    use Queueable;
    protected $invoice;
    protected $status;

    public function __construct($invoice, $status)
    {
        $this->invoice = $invoice;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database']; // Wajib ke database
    }

   public function toDatabase($notifiable)
    {
        $pesan = '';
        $icon = 'fas fa-box';
        $color = 'text-blue-500';
        $bgColor = 'bg-blue-100';

        if ($this->status == 'DIKEMAS' || $this->status == 'Dikemas') {
            $pesan = "Pesanan Anda dengan Invoice <b>{$this->invoice}</b> sedang <b>dikemas</b> oleh Mitra.";
            $icon = 'fas fa-box-open';
            $color = 'text-amber-500';
            $bgColor = 'bg-amber-100';
        } elseif ($this->status == 'DIKIRIM' || $this->status == 'Dikirim') {
            $pesan = "Hore! Pesanan Anda <b>{$this->invoice}</b> sedang <b>dalam perjalanan / dikirim</b>.";
            $icon = 'fas fa-truck';
            $color = 'text-blue-500';
            $bgColor = 'bg-blue-100';
        } elseif ($this->status == 'DITERIMA' || $this->status == 'Diterima') {
            $pesan = "Pesanan <b>{$this->invoice}</b> telah <b>diterima</b>. Menunggu pelunasan/penyelesaian.";
            $icon = 'fas fa-check-double';
            $color = 'text-emerald-500';
            $bgColor = 'bg-emerald-100';
        } elseif ($this->status == 'SELESAI' || $this->status == 'Selesai') {
            $pesan = "Pesanan <b>{$this->invoice}</b> telah <b>Selesai</b>. Terima kasih telah berbelanja!";
            $icon = 'fas fa-check-circle';
            $color = 'text-emerald-600';
            $bgColor = 'bg-emerald-200';
        }

     return [
            'title'      => 'Update Status Pesanan 📦',
            'message'    => $pesan,
            'icon'       => $icon,
            'color'      => $color,
            'bg_color'   => $bgColor,
            'action_url' => route('customer.pesanan') // ✅ Menggunakan nama route dari web.php kamu
        ];
    }
}
