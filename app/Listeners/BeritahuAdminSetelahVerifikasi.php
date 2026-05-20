<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Notifications\PengajuanBaruNotification;
use App\Notifications\PendaftaranSedangDitinjau; // <-- 1. Panggil file buatanmu di sini
use Illuminate\Support\Facades\Notification;

class BeritahuAdminSetelahVerifikasi
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;

        // Pastikan yang verifikasi adalah mitra yang masih pending
        if ($user->role === 'mitra' && $user->status === 'pending') {

            // 2. Kirim email pemberitahuan "Sedang Ditinjau" ke MITRA
            $user->notify(new PendaftaranSedangDitinjau());

            // 3. Kirim notifikasi (Email & Lonceng) ke ADMIN
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new PengajuanBaruNotification($user));
            }
        }
    }
}
