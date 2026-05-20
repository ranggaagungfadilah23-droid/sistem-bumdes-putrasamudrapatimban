<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Ambil role user dan bersihkan dari spasi (trim)
        $userRole = trim($request->user()->role);

        // 3. Cek apakah role user sesuai dengan yang diminta rute
        if ($userRole !== $role) {
            // Jika role tidak cocok, jangan dilempar ke index (biar tidak bingung)
            // Kita pakai abort 403 agar muncul pesan error yang jelas
            abort(403, 'Akses ditolak! Role Anda di database: "' . $userRole . '", tetapi halaman ini membutuhkan role: "' . $role . '"');
        }

        return $next($request);
    }




}
