<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
    public function cekStatus()
    {
        $user = auth()->user();

        // 1. Jika status sudah aktif, baru boleh diredirect ke dashboard
        if ($user->mitra && trim($user->mitra->status) === 'aktif') {
            return redirect()->route('mitra.dashboard');
        }

        // 2. Jika status belum aktif (atau belum ada data mitra),
        // HANYA tampilkan view-nya saja.
        return view('mitra.menunggu');
    }

    public function dashboard()
    {
        $mitra = Auth::user()->mitra;

        // Cek jika data mitra tidak ada, lempar ke halaman menunggu
        if (!$mitra) {
            return redirect()->route('mitra.menunggu');
        }

        // Arahkan berdasarkan jenis usaha
        return ($mitra->jenis_usaha == 'Jasa')
            ? redirect()->route('mitra.jasa.dashboard')
            : redirect()->route('mitra.produk.dashboard');
    }
}
