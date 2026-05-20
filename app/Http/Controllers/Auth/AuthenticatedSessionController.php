<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $role = trim($user->role);

        // 1. Redirect untuk Admin
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');

        // 2. Redirect untuk Kepala BUMDes
        } elseif ($role === 'kepala-bumdes') {
            return redirect()->route('kepala-bumdes.dashboard');

        // 3. Redirect untuk Mitra
       } elseif ($role === 'mitra') {
    // Cek apakah mitra sudah ada dan statusnya 'aktif' di tabel mitras
    if ($user->mitra && $user->mitra->status === 'aktif') {
        return redirect()->route('mitra.dashboard');
    } else {
        return redirect()->route('mitra.menunggu');
    }


        // 4. KHUSUS CUSTOMER (Tambahkan ini agar tidak mental ke index)
        } elseif ($role === 'customer') {
            return redirect()->route('customer.dashboard');

        // 5. Fallback terakhir jika tidak ada yang cocok
        } else {
            return redirect()->intended('/');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
