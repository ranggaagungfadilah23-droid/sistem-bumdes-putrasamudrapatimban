<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Produk; // ✅ Tambahkan model Produk
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function confirm(Request $request)
    {
        if ($request->isMethod('get') || !$request->has('cart_ids')) {
            return redirect()->route('customer.cart')->with('error', 'Item tidak dipilih.');
        }

        $selectedCarts = Cart::with(['produk', 'jasa'])
            ->whereIn('id', $request->cart_ids)
            ->where('user_id', auth()->id())
            ->get();

        return view('customer.checkout-confirm', compact('selectedCarts'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'cart_ids'          => 'required|array|min:1',
            'cart_ids.*'        => 'exists:carts,id',
            'metode_pembayaran' => 'required|in:bayar_sekarang,po',
            'alamat'            => 'required|string|min:5|max:500',
        ]);

        $carts = Cart::with(['produk', 'jasa'])
            ->whereIn('id', $request->cart_ids)
            ->where('user_id', auth()->id())
            ->get();

        $invoiceNumber = 'INV-' . strtoupper(Str::random(8)) . '-' . now()->format('dmY');

        DB::beginTransaction();
        try {
            $itemDetails = [];
            $totalAmount = 0;

            foreach ($carts as $cart) {
                // ✅ CEK STOK PRODUK SEBELUM PROSES
                if ($cart->produk_id) {
                    $produk = Produk::find($cart->produk_id);
                    if (!$produk || $produk->jumlah < $cart->jumlah) {
                        throw new \Exception("Stok produk " . ($produk->nama_produk ?? 'tidak diketahui') . " tidak mencukupi.");
                    }

                    // ✅ JIKA METODE PO, LANGSUNG POTONG STOK SAAT INVOICE DIBUAT
                    if ($request->metode_pembayaran === 'po') {
                        $produk->decrement('jumlah', $cart->jumlah);
                    }
                }

                $harga   = $cart->produk->harga ?? $cart->jasa->harga ?? 0;
                $total   = $harga * $cart->jumlah;
                $mitraId = $cart->produk->user_id ?? $cart->jasa->user_id;

                Transaksi::create([
                    'invoice_number'    => $invoiceNumber,
                    'customer_id'       => auth()->id(),
                    'mitra_id'          => $mitraId,
                    'produk_id'         => $cart->produk_id,
                    'jasa_id'           => $cart->jasa_id,
                    'jumlah'            => $cart->jumlah,
                    'harga'             => $harga,
                    'total'             => $total,
                    'alamat'            => $request->alamat,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'status_pembayaran' => 'pending',
                    'status_pengiriman' => 'menunggu',
                ]);

                $itemDetails[] = [
                    'id'       => $cart->produk_id ?? 'jasa-' . $cart->jasa_id,
                    'price'    => (int) $harga,
                    'quantity' => (int) $cart->jumlah,
                    'name'     => substr($cart->produk->nama_produk ?? $cart->jasa->nama_jasa, 0, 50),
                ];

                $totalAmount += $total;
                $cart->delete();
            }

            if ($request->metode_pembayaran === 'bayar_sekarang') {
                $params = [
                    'transaction_details' => [
                        'order_id'     => $invoiceNumber,
                        'gross_amount' => (int) $totalAmount,
                    ],
                    'item_details'     => $itemDetails,
                    'customer_details' => [
                        'first_name' => auth()->user()->name,
                        'email'      => auth()->user()->email,
                    ],
                    'callbacks' => [
                        'finish' => route('customer.invoice', $invoiceNumber),
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                Transaksi::where('invoice_number', $invoiceNumber)->update(['snap_token' => $snapToken]);

                DB::commit();
                return redirect()->route('checkout_payment', $invoiceNumber);
            }

            DB::commit();
            return redirect()->route('customer.invoice', $invoiceNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function payment($invoice)
    {
        $transaksis = Transaksi::where('invoice_number', $invoice)->get();
        if ($transaksis->isEmpty()) return redirect()->route('index');

        $snapToken   = $transaksis->first()->snap_token;
        $totalAmount = $transaksis->sum('total');

        return view('customer.checkout_payment', compact('transaksis', 'snapToken', 'totalAmount', 'invoice'));
    }

    public function callback(Request $request)
    {
        $serverKey   = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        $orderId     = $request->order_id;
        $statusCode  = $request->status_code;
        $grossAmount = $request->gross_amount;

        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signature !== $request->signature_key) {
            \Log::warning("Midtrans: Signature tidak valid untuk order $orderId");
            return response()->json(['status' => 'invalid signature'], 403);
        }

        $isSettled = $request->transaction_status === 'settlement' ||
                     ($request->transaction_status === 'capture' && $request->fraud_status === 'accept');

        if ($isSettled) {
            DB::transaction(function () use ($orderId) {
                // ✅ AMBIL DATA TRANSAKSI DULU UNTUK MEMOTONG STOK
                $transaksis = Transaksi::where('invoice_number', $orderId)
                    ->where('status_pembayaran', '!=', 'Lunas') // idempotent
                    ->get();

                foreach ($transaksis as $transaksi) {
                    // ✅ JIKA BAYAR SEKARANG DAN ITEM ADALAH PRODUK, POTONG STOK SAAT LUNAS
                    if ($transaksi->metode_pembayaran !== 'po' && $transaksi->produk_id) {
                        $produk = Produk::find($transaksi->produk_id);
                        if ($produk && $produk->jumlah >= $transaksi->jumlah) {
                            $produk->decrement('jumlah', $transaksi->jumlah);
                        }
                    }

                    // UPDATE STATUS TRANSAKSI
                    $transaksi->update([
                        'status_pembayaran' => 'Lunas',
                        'status_pengiriman' => 'Diproses',
                        'tanggal_bayar'     => now(),
                    ]);
                }
            });

            \Log::info("Midtrans webhook OK: Invoice $orderId → Lunas, status_pengiriman = Diproses");

        } elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
            Transaksi::where('invoice_number', $orderId)
                ->where('status_pembayaran', '!=', 'Lunas')
                ->update(['status_pembayaran' => 'Gagal']);

            \Log::info("Midtrans webhook: Invoice $orderId → Gagal/Expired");
        }

        return response()->json(['status' => 'success']);
    }

    public function buyNowRedirect()
    {
        $cartId = session('buy_now_cart_id');
        if (!$cartId) return redirect()->route('customer.cart')->with('error', 'Sesi habis.');

        $cart = Cart::where('id', $cartId)->where('user_id', auth()->id())->first();
        if (!$cart) return redirect()->route('customer.cart')->with('error', 'Item tidak ditemukan.');

        session()->forget('buy_now_cart_id');
        session(['buynow_cart_ids' => [$cartId]]);

        return redirect()->route('checkout.buynow.confirm');
    }

    public function buyNowConfirm()
    {
        $cartIds = session('buynow_cart_ids');
        if (!$cartIds) return redirect()->route('customer.cart')->with('error', 'Sesi habis.');

        $selectedCarts = Cart::with(['produk', 'jasa'])
            ->whereIn('id', $cartIds)
            ->where('user_id', auth()->id())
            ->get();

        if ($selectedCarts->isEmpty()) return redirect()->route('customer.cart')->with('error', 'Item kosong.');

        return view('customer.checkout-confirm', compact('selectedCarts'));
    }

    public function invoice($invoice)
    {
        $transaksis = Transaksi::where('invoice_number', $invoice)->get();
        if ($transaksis->isEmpty()) {
            return redirect()->route('customer.dashboard')->with('error', 'Invoice tidak ditemukan.');
        }
        return view('customer.invoice', compact('transaksis', 'invoice'));
    }
}
