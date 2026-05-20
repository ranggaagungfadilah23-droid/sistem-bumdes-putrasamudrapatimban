@extends('theme.default')
@section('title', 'Pembayaran - ' . $invoice)

@section('content')
<div class="min-h-screen bg-[#f5f5f5]">

    {{-- BREADCRUMB --}}
    <div class="bg-white border-b border-slate-100 px-4 py-3">
        <div class="max-w-3xl mx-auto flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('customer.dashboard') }}" class="hover:text-orange-500 transition">Beranda</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <a href="{{ route('customer.cart') }}" class="hover:text-orange-500 transition">Keranjang</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-slate-600 font-medium">Pembayaran</span>
        </div>
    </div>

    <main class="max-w-3xl mx-auto py-6 px-4">

        {{-- FLASH --}}
        @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">
            <i class="fas fa-check-circle text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            {{ session('error') }}
        </div>
        @endif

        {{-- BANNER POLLING (tersembunyi, muncul setelah GoPay dibuka) --}}
        <div id="polling-info" class="hidden mb-4 flex items-start gap-3 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-4 rounded-xl text-sm">
            <i class="fas fa-spinner fa-spin text-blue-500 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-bold mb-0.5">Selesaikan pembayaran GoPay kamu</p>
                <p class="text-blue-500 text-xs">Setelah selesai bayar, kembali ke halaman ini. Sistem akan otomatis mendeteksi pembayaran dan mengarahkan kamu ke invoice.</p>
            </div>
        </div>

        {{-- ALAMAT PENGIRIMAN --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-4">
            <h2 class="text-sm font-black text-slate-700 uppercase tracking-wider mb-3">
                <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>Alamat Pengiriman
            </h2>
            <p class="font-bold text-slate-800">{{ Auth::user()->name }} | {{ Auth::user()->phone ?? '08xxxxxxxx' }}</p>
            <p class="text-slate-500 text-sm mt-1">{{ Auth::user()->pelanggan->alamat_lengkap ?? 'Alamat belum diatur, silakan edit profil.' }}</p>
        </div>

        {{-- DAFTAR ITEM --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-4">
            <h2 class="text-sm font-black text-slate-700 uppercase tracking-wider mb-4">
                <i class="fas fa-shopping-bag text-orange-400 mr-2"></i>Ringkasan Pesanan
            </h2>
            <div class="divide-y divide-slate-50">
                @foreach($transaksis as $trx)
                <div class="flex items-center gap-4 py-3">
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                        @if($trx->produk && $trx->produk->gambar)
                            <img src="{{ asset('storage/' . $trx->produk->gambar) }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='{{ asset('images/placeholder.png') }}'">
                        @elseif($trx->jasa && $trx->jasa->gambar)
                            <img src="{{ asset('storage/' . $trx->jasa->gambar) }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='{{ asset('images/placeholder.png') }}'">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 text-sm truncate">
                            {{ $trx->produk->nama_produk ?? $trx->jasa->nama_jasa ?? 'Item' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Jumlah: {{ $trx->jumlah }}</p>
                    </div>
                    <p class="font-bold text-slate-700 text-sm flex-shrink-0">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- TOTAL & TOMBOL --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-4">
            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Nomor Invoice</span>
                <span class="text-sm font-bold text-slate-700 tracking-wide">{{ $invoice }}</span>
            </div>
            <div class="flex justify-between items-center pt-4 mt-2">
                <span class="text-sm font-bold text-slate-600">Total Pembayaran</span>
                <span class="text-2xl font-black text-orange-500">
                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                </span>
            </div>

            @if($snapToken)
                <button id="pay-button"
                        class="w-full mt-6 flex items-center justify-center gap-2 bg-orange-500 text-white py-4 rounded-xl font-bold text-sm hover:bg-orange-600 transition-all shadow-lg shadow-orange-200 disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="pay-button-text"><i class="fas fa-credit-card mr-2"></i>Bayar Sekarang</span>
                </button>
                <p class="text-center text-xs text-slate-400 mt-3">
                    <i class="fas fa-lock mr-1 text-green-400"></i>
                    Pembayaran aman diproses oleh Midtrans
                </p>
            @else
                <div class="mt-4 flex items-center gap-3 bg-blue-50 border border-blue-100 text-blue-600 px-4 py-3 rounded-xl text-sm">
                    <i class="fas fa-info-circle text-blue-400"></i>
                    Pesanan PO kamu sudah tercatat. Kami akan menghubungi kamu untuk konfirmasi lebih lanjut.
                </div>
                <a href="{{ route('customer.invoice', $invoice) }}"
                   class="mt-4 w-full flex items-center justify-center gap-2 bg-orange-500 text-white py-4 rounded-xl font-bold text-sm hover:bg-orange-600 transition-all shadow-lg shadow-orange-200">
                    <i class="fas fa-file-invoice"></i>
                    Lihat Invoice
                </a>
            @endif
        </div>

        {{-- JAMINAN --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-6 py-4">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="flex flex-col items-center gap-1.5">
                    <i class="fas fa-shield-alt text-orange-500 text-lg"></i>
                    <p class="text-[11px] font-bold text-slate-600">Transaksi Aman</p>
                    <p class="text-[10px] text-slate-400">Diproses Midtrans</p>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <i class="fas fa-lock text-orange-500 text-lg"></i>
                    <p class="text-[11px] font-bold text-slate-600">Data Terenkripsi</p>
                    <p class="text-[10px] text-slate-400">SSL secured</p>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <i class="fas fa-headset text-orange-500 text-lg"></i>
                    <p class="text-[11px] font-bold text-slate-600">Bantuan 24 Jam</p>
                    <p class="text-[10px] text-slate-400">Siap membantu</p>
                </div>
            </div>
        </div>

    </main>
</div>

{{-- Midtrans Snap.js Sandbox --}}
@if($snapToken)
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    var invoiceUrl   = "{{ route('customer.invoice', $invoice) }}";
    var checkUrl     = "/api/check-payment/{{ $invoice }}";
    var pollingTimer = null;

    function showPollingBanner() {
        var banner = document.getElementById('polling-info');
        banner.classList.remove('hidden');
        banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function setButtonLoading() {
        var btn = document.getElementById('pay-button');
        btn.disabled = true;
        document.getElementById('pay-button-text').innerHTML =
            '<i class="fas fa-spinner fa-spin mr-2"></i>Mengecek pembayaran...';
    }

    function resetButton() {
        var btn = document.getElementById('pay-button');
        btn.disabled = false;
        document.getElementById('pay-button-text').innerHTML =
            '<i class="fas fa-credit-card mr-2"></i>Bayar Sekarang';
        document.getElementById('polling-info').classList.add('hidden');
    }

    function startPolling() {
        // Hindari double polling
        if (pollingTimer) clearInterval(pollingTimer);

        showPollingBanner();
        setButtonLoading();

        pollingTimer = setInterval(function () {
            fetch(checkUrl)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.status === 'Lunas') {
                        clearInterval(pollingTimer);
                        window.location.href = invoiceUrl;
                    }
                })
                .catch(function () {
                    // Abaikan error network, tetap polling
                });
        }, 3000);

        // Stop polling setelah 10 menit, reset tombol
        setTimeout(function () {
            clearInterval(pollingTimer);
            pollingTimer = null;
            resetButton();
        }, 600000);
    }

    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                // Langsung redirect (untuk metode selain GoPay)
                window.location.href = invoiceUrl;
            },
            onPending: function (result) {
                // GoPay sandbox buka tab simulator terpisah — mulai polling
                startPolling();
            },
            onError: function (result) {
                alert('Pembayaran gagal! Silakan coba lagi.');
            },
            onClose: function () {
                // User tutup popup — cek apakah sudah bayar
                startPolling();
            }
        });
    };
</script>
@endif

@endsection
