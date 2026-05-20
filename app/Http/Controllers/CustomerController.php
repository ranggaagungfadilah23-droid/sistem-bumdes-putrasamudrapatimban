<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $pesanan = Transaksi::where('customer_id', auth()->id())
            ->with(['produk', 'jasa'])
            ->latest()
            ->get();

        return view('customer.pesanan', compact('pesanan'));
    }

    public function pending()
    {
        $pesanan = Transaksi::where('customer_id', auth()->id())
            ->where('status_pembayaran', 'pending')
            ->with(['produk', 'jasa'])
            ->latest()
            ->get();

        return view('customer.pesanan', compact('pesanan'));
    }

    public function dikemas()
    {
        $pesanan = Transaksi::where('customer_id', auth()->id())
            ->whereIn('status_pengiriman', ['Diproses', 'Dikemas'])
            ->with(['produk', 'jasa'])
            ->latest()
            ->get();

        return view('customer.pesanan', compact('pesanan'));
    }

    public function dikirim()
    {
        $pesanan = Transaksi::where('customer_id', auth()->id())
            ->where('status_pengiriman', 'Dikirim')
            ->with(['produk', 'jasa'])
            ->latest()
            ->get();

        return view('customer.pesanan', compact('pesanan'));
    }

    public function selesai()
    {
        $pesanan = Transaksi::where('customer_id', auth()->id())
            ->where('status_pengiriman', 'Selesai')
            ->with(['produk', 'jasa'])
            ->latest()
            ->get();

        return view('customer.pesanan', compact('pesanan'));
    }

    public function konfirmasiDiterima($invoice)
    {
        $transaksi = Transaksi::where('invoice_number', $invoice)
            ->where('customer_id', auth()->id())
            ->firstOrFail();

        if ($transaksi->status_pengiriman !== 'Dikirim') {
            return back()->with('error', 'Pesanan belum dikirim atau sudah diproses.');
        }

        if ($transaksi->metode_pembayaran === 'po') {
            $transaksi->update(['status_pengiriman' => 'Lunas']);
            $pesan = 'Konfirmasi berhasil. Menunggu konfirmasi lunas dari Mitra BUMDes.';
        } else {
            $transaksi->update(['status_pengiriman' => 'Selesai']);
            $pesan = 'Terima kasih! Pesanan Anda telah selesai.';
        }

        return redirect()->route('customer.pesanan.selesai')->with('success', $pesan);
    }
}
