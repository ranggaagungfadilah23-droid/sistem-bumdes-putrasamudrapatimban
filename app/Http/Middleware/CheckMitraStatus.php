<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMitraStatus
{
   public function handle(Request $request, Closure $next): Response
{
    $user = Auth::user();

    if ($user && $user->role === 'mitra') {
        $mitra = \App\Models\Mitra::where('user_id', $user->id)->first();

        // Jika belum ada data mitra, tetap di halaman menunggu
        if (!$mitra) return redirect()->route('mitra.menunggu');

        // HANYA IZINKAN JIKA STATUS AKTIF
        if (trim($mitra->status) === 'aktif') {
            return $next($request);
        }

        // Semua status lain (pending, menunggu_kepala, rejected) arahkan ke menunggu
        return redirect()->route('mitra.menunggu');
    }

    return $next($request);
}
}
