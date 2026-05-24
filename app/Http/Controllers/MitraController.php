<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
    /**
     * Menampilkan halaman status verifikasi (Menunggu)
     */
    public function cekStatus()
    {
        // ✅ Apapun statusnya (pending, menunggu_kepala, atau aktif),
        // tetap tampilkan view menunggu. Biarkan file Blade yang merender
        // tampilan sesuai status saat ini.
        return view('mitra.menunggu');
    }

    /**
     * Menangani rute dashboard mitra setelah aktif
     */
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
