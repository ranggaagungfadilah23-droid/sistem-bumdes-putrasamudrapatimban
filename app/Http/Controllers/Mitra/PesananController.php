<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\{Transaksi, Pendapatan, Mitra};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};

class PesananController extends Controller
{
    /**
     * Flow Status Pengiriman:
     *
     * Bayar Sekarang: menunggu → Diproses → Dikemas → Dikirim → Diterima → Selesai
     *                 (hanya bisa maju jika status_pembayaran = Lunas via Midtrans)
     *
     * PO:             menunggu → Diproses → Dikemas → Dikirim
     *                 → [Mitra klik Konfirmasi Lunas] → Diterima → Selesai
     */

    const FLOW_MAP = [
        'menunggu' => 'Diproses',
        'Diproses' => 'Dikemas',
        'Dikemas'  => 'Dikirim',
        'Dikirim'  => 'Diterima',
        'Diterima' => 'Selesai',
    ];

   public function index(Request $request)
{
    $mitra = Mitra::where('user_id', Auth::id())->first();
    if (!$mitra) return back()->with('error', 'Mitra tidak ditemukan.');

    $query = Transaksi::where('mitra_id', $mitra->user_id)
        ->with(['customer', 'produk', 'jasa']);

    // Search by invoice atau nama customer
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('invoice_number', 'like', "%{$search}%")
              ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
        });
    }

    // Filter by status pengiriman
    if ($request->filled('status')) {
        if ($request->status === 'menunggu') {
            $query->whereIn('status_pengiriman', ['menunggu', null])
                  ->orWhereNull('status_pengiriman');
        } else {
            $query->where('status_pengiriman', $request->status);
        }
    }

    // Filter by metode pembayaran
    if ($request->filled('metode')) {
        $query->where('metode_pembayaran', $request->metode);
    }

    $pesanan = $query->latest()->paginate(10)->withQueryString();

    return view('mitra.pesanan.index', compact('pesanan'));
}

    /**
     * Update status pengiriman ke step berikutnya (State Machine).
     */
    public function updateStatus(Request $request, $id)
    {
        $mitra = Mitra::where('user_id', Auth::id())->first();
        if (!$mitra) return back()->with('error', 'Mitra tidak ditemukan.');

        $transaksi = Transaksi::where('id', $id)
            ->where('mitra_id', $mitra->user_id)
            ->firstOrFail();

        $isPO    = $transaksi->metode_pembayaran === 'po';
        $current = $transaksi->status_pengiriman ?? 'menunggu';

        // Sudah di status akhir
        if ($current === 'Selesai') {
            return back()->with('error', 'Pesanan sudah berada di status akhir (Selesai).');
        }

        // Non-PO: wajib sudah Lunas dari Midtrans sebelum bisa diproses
        if (!$isPO && $transaksi->status_pembayaran !== 'Lunas') {
            return back()->with('error', 'Pesanan belum terkonfirmasi lunas oleh Midtrans.');
        }

        // PO: di step Dikirim wajib konfirmasi lunas dulu sebelum lanjut
        if ($isPO && $current === 'Dikirim') {
            return back()->with('error', 'Gunakan tombol "Konfirmasi Lunas" untuk memproses pembayaran PO terlebih dahulu.');
        }

        $nextStep = self::FLOW_MAP[$current] ?? null;

        if (!$nextStep) {
            return back()->with('error', 'Alur status tidak valid.');
        }

        $transaksi->update(['status_pengiriman' => $nextStep]);
        return back()->with('success', "Status pesanan diperbarui ke: {$nextStep}");
    }

    /**
     * Konfirmasi pelunasan PO — mengubah status pembayaran jadi Lunas
     * dan status pengiriman maju ke Diterima.
     */
    public function konfirmasiLunas($id)
    {
        $mitra = Mitra::where('user_id', Auth::id())->first();
        if (!$mitra) return back()->with('error', 'Mitra tidak ditemukan.');

        $transaksi = Transaksi::where('id', $id)
            ->where('mitra_id', $mitra->user_id)
            ->firstOrFail();

        if ($transaksi->metode_pembayaran !== 'po') {
            return back()->with('error', 'Hanya pesanan PO yang memerlukan konfirmasi lunas manual.');
        }

        if ($transaksi->status_pengiriman !== 'Dikirim') {
            return back()->with('error', 'Konfirmasi lunas hanya bisa dilakukan saat status "Dikirim".');
        }

        if ($transaksi->status_pembayaran === 'Lunas') {
            return back()->with('error', 'Pesanan ini sudah dikonfirmasi lunas.');
        }

        try {
            DB::transaction(function () use ($transaksi) {
                $transaksi->update([
                    'status_pembayaran' => 'Lunas',
                    'tanggal_bayar'     => now(),
                    'status_pengiriman' => 'Diterima', // ✅ langsung maju ke Diterima
                ]);

                Pendapatan::updateOrCreate(
                    ['transaksi_id' => $transaksi->id],
                    [
                        'mitra_id'       => $transaksi->mitra_id,
                        'total_diterima' => $transaksi->total,
                        'keterangan'     => 'Pelunasan PO - Invoice: ' . $transaksi->invoice_number,
                        'tanggal_masuk'  => now(),
                    ]
                );
            });

            return redirect()->route('mitra.pesanan.index')
                ->with('success', 'Pelunasan PO dikonfirmasi! Klik "Selesaikan Pesanan" untuk menutup transaksi.');

        } catch (\Exception $e) {
            Log::error("Konfirmasi Lunas Gagal ID {$id}: " . $e->getMessage());
            return redirect()->route('mitra.pesanan.index')
                ->with('error', 'Gagal mengkonfirmasi lunas: ' . $e->getMessage());
        }
    }

    public function cetakInvoice($id)
    {
        $transaksis = Transaksi::with(['customer', 'produk', 'jasa'])
            ->where('id', $id)
            ->get();

        if ($transaksis->isEmpty()) {
            return back()->with('error', 'Data invoice tidak ditemukan.');
        }

        return view('customer.invoice', compact('transaksis'));
    }
}
