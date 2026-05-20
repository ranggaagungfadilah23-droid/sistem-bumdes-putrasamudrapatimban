<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * Satpam ini bertugas mengalau user yang SUDAH login agar tidak masuk ke halaman login/register lagi.
     */
  // Pastikan di file App\Http\Middleware\RedirectIfAuthenticated.php
public function handle(Request $request, Closure $next, string ...$guards): Response
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();

            // KUNCI PENGALIHAN: Langsung lempar ke dashboard tanpa tanya-tanya
            return match($user->role) {
                'admin'         => redirect()->route('admin.dashboard'),
                'mitra'         => redirect()->route('mitra.dashboard'),
                'kepala-bumdes' => redirect()->route('kepala-bumdes.dashboard'),
                'customer'      => redirect()->route('customer.dashboard'),
                default         => redirect('/'),
            };
        }
    }

    return $next($request);
}
}
