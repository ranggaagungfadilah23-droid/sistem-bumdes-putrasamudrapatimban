<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\User; // ✅ Model User diimport untuk mencari admin

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Jika user sudah terverifikasi sebelumnya
        if ($user->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($user);
        }

        // 2. Jika proses verifikasi baru saja berhasil
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            // ✅ Kirim Notifikasi WA & Database khusus untuk Mitra yang baru verifikasi
            if (trim($user->role) === 'mitra') {

                // --- A. KIRIM WA KE MITRA ---
                $no_hp = $user->mitra->no_hp ?? '';

                if ($no_hp) {
                    $pesan = "Halo *{$user->name}*,\n\nEmail Anda telah berhasil diverifikasi! Pendaftaran Mitra BUMDes Anda saat ini berstatus *Menunggu Verifikasi Admin*.\n\nKami akan segera memproses data Anda dan menginformasikan kembali melalui WhatsApp ini jika berkas sudah diperiksa.\n\nTerima kasih.\n\n*Sistem BUMDes Patimban*";

                    $this->kirimWA($no_hp, $pesan);
                }

                // --- B. KIRIM WA KE ADMIN (Ambil no_hp dari tabel users) ---
                $admin = User::where('role', 'admin')->first(); // Mengambil data admin pertama
                $admin_no_hp = $admin->no_hp ?? '';

                if ($admin_no_hp) {
                    $namaUsaha = $user->mitra->nama_usaha ?? '-';
                    $pesanAdmin = "Halo Admin BUMDes,\n\nAda pendaftaran Mitra baru yang memerlukan verifikasi berkas:\n\nNama Pemilik: *{$user->name}*\nNama Usaha: *{$namaUsaha}*\nEmail: {$user->email}\n\nStatus berkas saat ini: *Menunggu Verifikasi Admin*.\nSilakan masuk ke Dashboard Admin untuk memeriksa dokumen pendukung (SKU) mitra tersebut.\n\n*Sistem BUMDes Patimban*";

                    $this->kirimWA($admin_no_hp, $pesanAdmin);
                }

                // --- C. KIRIM NOTIFIKASI DATABASE (SISTEM LONCENG) KE ADMIN ---
                if ($admin) {
                    $admin->notify(new \App\Notifications\PendaftarBaruNotification($user->name, $user->mitra->nama_usaha ?? '-'));
                }
            }
        }

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Helper untuk menentukan rute redirect berdasarkan role
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        $role = trim($user->role);

        if ($role === 'admin') {
            $url = route('admin.dashboard', absolute: false);
        } elseif ($role === 'kepala-bumdes') {
            $url = route('kepala-bumdes.dashboard', absolute: false);
        } elseif ($role === 'mitra') {
            // Mitra baru biasanya harus menunggu approval, jadi arahkan ke halaman menunggu
            $url = route('mitra.menunggu', absolute: false);
        } else {
            $url = route('index', absolute: false);
        }

        return redirect()->intended($url . '?verified=1');
    }

    /**
     * Helper untuk kirim pesan WA via Fonnte API
     */
    private function kirimWA($no_hp, $pesan)
    {
        $token  = "obEnSgdDTVkALfwmMYTy"; // Token Fonnte
        $target = preg_replace('/^0/', '62', $no_hp);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => [
                'target'      => $target,
                'message'     => $pesan,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => ["Authorization: $token"],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }
}
