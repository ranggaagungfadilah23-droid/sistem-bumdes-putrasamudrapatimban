<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan daftar keranjang belanja
     */
    public function index()
    {
        $userId = Auth::id();
        $carts  = Cart::where('user_id', $userId)->with(['produk', 'jasa'])->get();

        $totalHarga = $carts->sum(function ($cart) {
            $harga = $cart->produk->harga ?? ($cart->jasa->harga ?? 0);
            return $harga * $cart->jumlah;
        });

        return view('customer.cart', compact('carts', 'totalHarga'));
    }

    /**
     * Menambah produk ke keranjang
     */
    public function add(Request $request)
    {
        if (!$request->produk_id && !$request->jasa_id) {
            return back()->with('error', 'Item tidak valid.');
        }

        $userId = Auth::id();

        // Cek apakah sudah ada di cart
        $cart = Cart::where('user_id', $userId)
            ->where('produk_id', $request->produk_id ?: null)
            ->where('jasa_id', $request->jasa_id ?: null)
            ->first();

        if ($cart) {
            // Tambah jumlah jika sudah ada
            $cart->increment('jumlah', $request->jumlah ?? 1);
        } else {
            // Buat baru jika belum ada
            $cart = Cart::create([
                'user_id'   => $userId,
                'produk_id' => $request->produk_id ?: null,
                'jasa_id'   => $request->jasa_id ?: null,
                'jumlah'    => $request->jumlah ?? 1,
            ]);
        }

        // Jika dari tombol "Beli Sekarang"
        if ($request->buy_now) {
            session(['buy_now_cart_id' => $cart->id]);
            return redirect()->route('checkout.buynow');
        }

        return back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menambah jasa ke keranjang
     */
    public function addJasa(Request $request, $id)
    {
        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)
            ->where('produk_id', null)
            ->where('jasa_id', $id)
            ->first();

        if ($cart) {
            $cart->increment('jumlah', $request->input('jumlah', 1));
        } else {
            $cart = Cart::create([
                'user_id'   => $userId,
                'produk_id' => null,
                'jasa_id'   => $id,
                'jumlah'    => $request->input('jumlah', 1),
            ]);
        }

        // Jika dari tombol "Beli Sekarang" di halaman jasa
        if ($request->buy_now) {
            session(['buy_now_cart_id' => $cart->id]);
            return redirect()->route('checkout.buynow');
        }

        return back()->with('success', 'Jasa berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menghapus item dari keranjang
     */
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item berhasil dihapus!');
    }

    /**
     * Mengosongkan keranjang
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
