<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pendapatan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Received', $payload);

        // Verifikasi signature key dari Midtrans
      $serverKey = config('services.midtrans.server_key');
        $orderId      = $payload['order_id'] ?? '';
        $statusCode   = $payload['status_code'] ?? '';
        $grossAmount  = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($hash !== $signatureKey) {
            Log::warning('Midtrans: Signature tidak valid', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? '';

        // Cari transaksi berdasarkan order_id (= invoice_number)
        $transaksi = Transaksi::where('invoice_number', $orderId)->first();

        if (!$transaksi) {
            Log::warning('Midtrans: Transaksi tidak ditemukan', ['order_id' => $orderId]);
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Jika sudah Lunas, skip
        if ($transaksi->status_pembayaran === 'Lunas') {
            return response()->json(['message' => 'Already settled']);
        }

        // Cek status dari Midtrans
        $isSettled = ($transactionStatus === 'settlement') ||
                     ($transactionStatus === 'capture' && $fraudStatus === 'accept');

       if ($isSettled) {
    DB::transaction(function () use ($transaksi) {
        $transaksi->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar'     => now(),
           'status_pengiriman' => 'Diproses',
        ]);

        $sudahAda = Pendapatan::where('transaksi_id', $transaksi->id)->exists();
        if (!$sudahAda) {
            Pendapatan::create([
                'transaksi_id'   => $transaksi->id,
                'mitra_id'       => $transaksi->mitra_id,
                'total_diterima' => $transaksi->total,
                'keterangan'     => 'Bayar Sekarang (Midtrans) - Invoice: ' . $transaksi->invoice_number,
                'tanggal_masuk'  => now(),
            ]);
        }
    });

            Log::info('Midtrans: Transaksi berhasil di-settle', ['invoice' => $transaksi->invoice_number]);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaksi->update(['status_pembayaran' => 'Gagal']);
            Log::info('Midtrans: Transaksi gagal/expired', ['invoice' => $transaksi->invoice_number]);
        }

        return response()->json(['message' => 'OK']);
    }
}
