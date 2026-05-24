<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UlasanMasukNotification; // buat jika pakai notifikasi

class UlasanController extends Controller
{
    /**
     * Simpan ulasan dari customer dan kirimkan ke mitra.
     * Dipanggil via AJAX dari halaman pesanan.
     *
     * POST /customer/ulasan
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'invoice_number' => ['required', 'string', 'exists:transaksi,invoice_number'],
            'bintang'        => ['required', 'integer', 'min:1', 'max:5'],
            'pesan'          => ['nullable', 'string', 'max:1000'],
            'mitra_id'       => ['required', 'integer', 'exists:users,id'],
        ]);

        // 2. Ambil transaksi & pastikan milik customer yang login
        $transaksi = Transaksi::where('invoice_number', $request->invoice_number)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        // 3. Pastikan pesanan sudah selesai
        if (! in_array($transaksi->status_pengiriman, ['Selesai', 'Diterima'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan belum selesai, ulasan tidak dapat diberikan.',
            ], 422);
        }

        // 4. Pastikan belum pernah mengulas transaksi ini
        if (Ulasan::where('invoice_number', $request->invoice_number)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah memberikan ulasan untuk pesanan ini.',
            ], 422);
        }

        // 5. Simpan ulasan
        $ulasan = Ulasan::create([
            'invoice_number' => $request->invoice_number,
            'customer_id'    => Auth::id(),
            'mitra_id'       => $request->mitra_id,
            'bintang'        => $request->bintang,
            'pesan'          => $request->pesan,
        ]);

        // 6. Kirim notifikasi ke mitra
        //    Pastikan model User mitra sudah menggunakan trait Notifiable.
        //    Jika belum pakai notifikasi Laravel, hapus blok try-catch ini.
        try {
            $mitra = \App\Models\User::find($request->mitra_id);
            if ($mitra) {
                $mitra->notify(new UlasanMasukNotification($ulasan));
            }
        } catch (\Throwable $e) {
            // Notifikasi gagal tidak membatalkan simpan ulasan
            \Log::warning('Notifikasi ulasan gagal: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim!',
            'data'    => [
                'invoice_number' => $ulasan->invoice_number,
                'bintang'        => $ulasan->bintang,
                'label'          => $ulasan->label_bintang,
            ],
        ]);
    }

    /**
     * (Opsional) Tampilkan semua ulasan milik customer yang login.
     *
     * GET /customer/ulasan
     */
    public function index()
    {
        $ulasan = Ulasan::where('customer_id', Auth::id())
            ->with(['transaksi', 'mitra'])
            ->latest()
            ->paginate(10);

        return view('customer.ulasan.index', compact('ulasan'));
    }
}
