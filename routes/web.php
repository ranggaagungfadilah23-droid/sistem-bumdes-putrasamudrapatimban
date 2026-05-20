<?php

use App\Http\Controllers\{
    ProfileController, GoogleController, ProductController, JasaController,
    AdminController, KepalaBumdesController, PencarianController, SuratController,
    MitraController, CheckoutController, CustomerController, NotificationsController,
    BagihasilController
};
use App\Http\Controllers\Mitra\PesananController;
use App\Http\Controllers\Mitra\PendapatanController;
use App\Http\Controllers\Mitra\LaporanController;
use App\Http\Controllers\Customer\PesananController as CustomerPesananController;
use Illuminate\Support\Facades\{Route, Auth};

// --- 1. PUBLIC AREA ---
Route::get('/', [JasaController::class, 'landingPage'])->name('index');
Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
Route::get('/verifikasi/surat/{id}', [SuratController::class, 'verifikasi'])->name('verifikasi.surat');
Route::get('/panduan', fn() => view('auth.panduan-pendaftaran'))->name('panduan');

// Midtrans Webhook — bypass CSRF
// Midtrans Webhook — bypass CSRF
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    
// API cek status pembayaran (untuk polling di frontend)
Route::get('/api/check-payment/{invoice}', function ($invoice) {
    $transaksi = \App\Models\Transaksi::where('invoice_number', $invoice)->first();
    return response()->json([
        'status' => $transaksi->status_pembayaran ?? 'pending'
    ]);
})->middleware(['auth']);

// --- GOOGLE OAUTH ---
Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// --- 2. GUEST AREA ---
Route::middleware('guest')->group(function () {
    Route::view('/register/mitra', 'auth.register-mitra')->name('register.mitra');
    Route::view('/register/pelanggan', 'auth.register-pelanggan')->name('register.pelanggan');
    Route::post('/register/mitra', [GoogleController::class, 'storeMitra'])->name('mitra.store');
    Route::post('/register/pelanggan', [GoogleController::class, 'storePelanggan'])->name('pelanggan.store');
});

// --- 3. AUTH AREA ---
Route::middleware(['auth', 'verified'])->group(function () {

 Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'mitra') return redirect()->route('mitra.dashboard');
        if ($user->role === 'kepala-bumdes') return redirect()->route('kepala-bumdes.dashboard');
        return redirect()->route('customer.dashboard');
    })->name('dashboard'); //  Sudah diperbaiki menggunakan '})'

    Route::get('/cari', [PencarianController::class, 'index'])->name('global.search');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/send-reset-link', [ProfileController::class, 'sendResetLink'])->name('profile.send-reset-link');

    // --- NOTIFICATIONS ---
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{id}', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    })->name('notifications.destroy');
    Route::delete('/notifications', function () {
        auth()->user()->notifications()->delete();
        return back();
    })->name('notifications.destroyAll');

    // --- ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard', [
            'pengajuans' => \App\Models\User::where('role', 'mitra')->where('status', 'pending')->get()
        ]))->name('admin.dashboard');

        Route::get('/pengajuan', [AdminController::class, 'pengajuan'])->name('admin.pengajuan');
        Route::post('/approve/{id}', [AdminController::class, 'approve'])->name('admin.approve');
        Route::post('/reject/{id}', [AdminController::class, 'reject'])->name('admin.reject');
        Route::get('/data-mitra', [AdminController::class, 'dataMitra'])->name('admin.mitra.index');
        Route::delete('/data-mitra/{id}', [AdminController::class, 'destroyMitra'])->name('admin.mitra.destroy');

        // Bagi Hasil
        Route::get('/bagihasil', [BagihasilController::class, 'index'])->name('admin.bagihasil');
        Route::post('/bagihasil/store', [BagihasilController::class, 'store'])->name('admin.bagihasil.store');
        Route::patch('/bagihasil/confirm', [BagihasilController::class, 'confirm'])->name('admin.bagihasil.confirm');
        Route::get('/bagihasil/omzet/{mitra_id}', [BagihasilController::class, 'getOmzet'])->name('admin.bagihasil.omzet');

        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
        Route::get('/histori', [AdminController::class, 'histori'])->name('admin.histori');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    });

    // --- KEPALA BUMDES ---
    Route::middleware(['role:kepala-bumdes'])->prefix('kepala-bumdes')->group(function () {
        Route::get('/dashboard', [KepalaBumdesController::class, 'dashboard'])->name('kepala-bumdes.dashboard');
        Route::get('/pengajuan', [KepalaBumdesController::class, 'pengajuan'])->name('kepala-bumdes.pengajuan');
        Route::get('/data-mitra', [KepalaBumdesController::class, 'dataMitra'])->name('kepala-bumdes.mitra.index');
        Route::get('/pengajuan/{id}/preview', [SuratController::class, 'apiPreview'])->name('mitra.preview-api');
        Route::patch('/pengajuan/{id}/setujui', [SuratController::class, 'setujuiMitra'])->name('mitra.setujui');
        Route::post('/pengajuan/{id}/reject', [KepalaBumdesController::class, 'reject'])->name('kepala-bumdes.reject');
    });

    // --- MITRA ---
    Route::prefix('mitra')->group(function () {
        Route::get('/menunggu', [MitraController::class, 'cekStatus'])->name('mitra.menunggu');

        Route::middleware(['role:mitra', 'mitra_check'])->group(function () {
            Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('mitra.dashboard');
            Route::get('/jasa/dashboard', [App\Http\Controllers\Mitra\Jasa\DashboardController::class, 'dashboard'])->name('mitra.jasa.dashboard');
            Route::get('/produk/dashboard', [App\Http\Controllers\Mitra\Produk\DashboardController::class, 'dashboard'])->name('mitra.produk.dashboard');

           Route::get('/kelola-usaha', function () {
    if (Auth::user()->mitra->jenis_usaha == 'Jasa') {
        return view('mitra.jasa.index');
    } else {
        // Ambil data produk dan kirimkan ke view
        $produks = \App\Models\Produk::where('user_id', Auth::id())->latest()->get();
        return view('mitra.produk.index', compact('produks'));
    }
})->name('mitra.kelola');

            // SINKRONISASI ROUTE PESANAN MITRA
            Route::get('/pesanan', [PesananController::class, 'index'])->name('mitra.pesanan.index');
            Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('mitra.pesanan.update-status');
            Route::post('/pesanan/{id}/konfirmasi', [PesananController::class, 'konfirmasiLunas'])->name('mitra.pesanan.konfirmasi-lunas');
            Route::get('/pesanan/{id}/cetak', [PesananController::class, 'cetakInvoice'])->name('mitra.pesanan.cetak-invoice');

            Route::resource('produk', ProductController::class)->names('mitra.produk');
            Route::resource('jasa', JasaController::class)->names('mitra.jasa');

            // Laporan
            Route::prefix('laporan')->name('mitra.laporan.')->group(function () {
                Route::get('/',    [LaporanController::class, 'index'])->name('index');
                Route::get('/pdf', [LaporanController::class, 'pdf'])->name('pdf');
                Route::post('/kirim', [LaporanController::class, 'kirimKeAdmin'])->name('kirim');
            });

            // Pendapatan
            Route::prefix('pendapatan')->name('mitra.pendapatan.')->group(function () {
                Route::get('/',              [PendapatanController::class, 'index'])->name('index');
                Route::get('/laporan',       [PendapatanController::class, 'laporan'])->name('laporan');
                Route::get('/laporan/pdf_rekap', [PendapatanController::class, 'laporanPdf'])->name('laporan.pdf_rekap');
                Route::post('/kirim',        [BagihasilController::class, 'store'])->name('kirim');
            });
        });
    });

    // --- CUSTOMER ---
    Route::middleware(['role:customer'])->prefix('customer')->group(function () {
        Route::get('/dashboard', function () {
            return view('customer.dashboard', [
                'produks' => \App\Models\Produk::all(),
                'jasas'   => \App\Models\Jasa::all(),
            ]);
        })->name('customer.dashboard');

        // Cart
        Route::prefix('cart')->group(function() {
            Route::get('/', [App\Http\Controllers\Customer\CartController::class, 'index'])->name('customer.cart');
            Route::post('/add/{id}', [App\Http\Controllers\Customer\CartController::class, 'add'])->name('cart.add');
            Route::post('/add-jasa/{id}', [App\Http\Controllers\Customer\CartController::class, 'addJasa'])->name('cart.add.jasa');
            Route::post('/add-produk/{id}', [App\Http\Controllers\Customer\CartController::class, 'addProduk'])->name('cart.add.produk');
            Route::get('/clear', [App\Http\Controllers\Customer\CartController::class, 'clear'])->name('customer.cart.clear');
        });

        // Detail Produk & Jasa
        Route::get('/produk/{id}', [ProductController::class, 'show'])->name('customer.produk.show');
        Route::get('/jasa/{id}', [JasaController::class, 'show'])->name('customer.jasa.show');

        // Checkout
        Route::match(['get', 'post'], '/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/checkout/payment/{invoice}', [CheckoutController::class, 'payment'])->name('checkout_payment');

        // Buy Now
        Route::get('/checkout/buynow', [CheckoutController::class, 'buyNowRedirect'])->name('checkout.buynow');
        Route::get('/checkout/buynow/confirm', [CheckoutController::class, 'buyNowConfirm'])->name('checkout.buynow.confirm');

        // Invoice
        Route::get('/checkout/invoice/{invoice}', [CheckoutController::class, 'invoice'])->name('customer.invoice');

        // --- PESANAN CUSTOMER ---
        Route::get('/pesanan', [CustomerPesananController::class, 'index'])->name('customer.pesanan');
        Route::get('/pesanan/pending', [CustomerPesananController::class, 'pending'])->name('customer.pesanan.pending');
        Route::get('/pesanan/dikemas', [CustomerPesananController::class, 'dikemas'])->name('customer.pesanan.dikemas');
        Route::get('/pesanan/dikirim', [CustomerPesananController::class, 'dikirim'])->name('customer.pesanan.dikirim');
        Route::get('/pesanan/selesai', [CustomerPesananController::class, 'selesai'])->name('customer.pesanan.selesai');

        // Memproses aksi konfirmasi penerimaan barang/jasa BUMDes
        Route::post('/pesanan/{invoice}/konfirmasi-diterima', [CustomerPesananController::class, 'konfirmasiDiterima'])
            ->name('customer.pesanan.konfirmasi-diterima');
    });

});

require base_path('routes/auth.php');

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});
