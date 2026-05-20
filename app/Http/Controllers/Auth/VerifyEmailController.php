<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

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
}
