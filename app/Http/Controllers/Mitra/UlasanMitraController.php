<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanMitraController extends Controller
{
    /**
     * Tampilkan semua ulasan yang masuk ke mitra ini.
     *
     * GET /mitra/ulasan
     */
    public function index()
    {
        $ulasan = Ulasan::where('mitra_id', Auth::id())
            ->with(['customer', 'transaksi'])
            ->latest()
            ->paginate(10);

        // Rata-rata bintang
        $rataRata = Ulasan::where('mitra_id', Auth::id())->avg('bintang');

        return view('mitra.ulasan.index', compact('ulasan', 'rataRata'));
    }

    /**
     * Mitra membalas ulasan dari customer.
     *
     * POST /mitra/ulasan/{ulasan}/balas
     */
    public function balas(Request $request, Ulasan $ulasan)
    {
        // Pastikan ulasan ini memang milik mitra yang login
        abort_if($ulasan->mitra_id !== Auth::id(), 403);

        $request->validate([
            'balasan_mitra' => ['required', 'string', 'max:500'],
        ]);

        $ulasan->update([
            'balasan_mitra' => $request->balasan_mitra,
            'dibalas_at'    => now(),
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }
}
