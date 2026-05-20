@extends('theme.default')
@section('content')

<main class="max-w-4xl mx-auto px-6 py-12">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800">Konfirmasi Pesanan</h1>
            <p class="text-slate-500 text-sm mt-1">Periksa pesananmu sebelum membayar</p>
        </div>
        <a href="{{ route('customer.cart') }}"
           class="flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition text-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
        </a>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center gap-3 mb-10">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">✓</div>
            <span class="text-xs font-semibold text-blue-600">Keranjang</span>
        </div>
        <div class="flex-1 h-px bg-blue-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center">2</div>
            <span class="text-xs font-bold text-blue-600">Konfirmasi</span>
        </div>
        <div class="flex-1 h-px bg-slate-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-400 text-xs font-bold flex items-center justify-center">3</div>
            <span class="text-xs text-slate-400">Invoice</span>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="confirmForm">
        @csrf

        {{-- Hidden: kirim cart_ids yang sudah dipilih --}}
        @foreach($selectedCarts as $cart)
            <input type="hidden" name="cart_ids[]" value="{{ $cart->id }}">
        @endforeach

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Kiri: Detail Item + Alamat --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Daftar Item --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-700">
                            <i class="fas fa-box text-blue-500 mr-2"></i>Detail Item
                        </h3>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @foreach($selectedCarts as $cart)
                            @php
                                $nama     = $cart->produk->nama_produk ?? $cart->jasa->nama_jasa ?? '-';
                                $harga    = $cart->produk->harga ?? $cart->jasa->harga ?? 0;
                                $gambar   = $cart->produk->gambar ?? $cart->jasa->gambar ?? '';
                                $subtotal = $harga * $cart->jumlah;
                            @endphp
                            <div class="flex items-center gap-4 px-6 py-4">
                                <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/' . $gambar) }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.src='{{ asset('images/placeholder.png') }}'">
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $nama }}</p>
                                    <p class="text-xs text-slate-400">{{ $cart->produk ? 'Produk' : 'Jasa' }} &bull; Qty {{ $cart->jumlah }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-bold text-slate-800 text-sm">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-400">@ Rp {{ number_format($harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Alamat Pengiriman --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-700">
                            <i class="fas fa-map-marker-alt text-rose-500 mr-2"></i>Alamat Pengiriman
                        </h3>
                    </div>
                    <div class="p-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap Tujuan</label>
                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Masukkan alamat lengkap pengiriman..."
                            required>{{ auth()->user()->pelanggan->alamat_lengkap ?? '' }}</textarea>
                        <p class="mt-2 text-[11px] text-slate-400 italic">
                            *Alamat otomatis diambil dari data profil Anda. Silakan ubah jika pengiriman ditujukan ke alamat lain.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Kanan: Metode Bayar + Total --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Metode Pembayaran --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-700">
                            <i class="fas fa-credit-card text-green-500 mr-2"></i>Pilih Metode Bayar
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="space-y-3">
                            {{-- Bayar Sekarang --}}
                            <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-blue-50 transition border-slate-100 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                                <input type="radio" name="metode_pembayaran" value="bayar_sekarang" class="w-4 h-4 text-blue-600" checked>
                                <div class="ml-4 flex items-center gap-3">
                                    <div class="w-9 h-9 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">Bayar Sekarang</p>
                                        <p class="text-[10px] text-slate-500">Transfer Bank / QRIS</p>
                                    </div>
                                </div>
                            </label>

                            {{-- Open PO --}}
                            <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-amber-50 transition border-slate-100 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/50">
                                <input type="radio" name="metode_pembayaran" value="po" class="w-4 h-4 text-amber-600">
                                <div class="ml-4 flex items-center gap-3">
                                    <div class="w-9 h-9 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-sm">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">Bayar Nanti (PO)</p>
                                        <p class="text-[10px] text-slate-500">Purchase Order / Termin</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('metode_pembayaran')
                            <p class="text-[10px] text-red-500 px-1 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Ringkasan Total --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-700">
                            <i class="fas fa-receipt text-amber-500 mr-2"></i>Ringkasan Pembayaran
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        @php $grandTotal = 0 @endphp
                        @foreach($selectedCarts as $cart)
                            @php
                                $harga    = $cart->produk->harga ?? $cart->jasa->harga ?? 0;
                                $subtotal = $harga * $cart->jumlah;
                                $grandTotal += $subtotal;
                                $nama = $cart->produk->nama_produk ?? $cart->jasa->nama_jasa ?? '-';
                            @endphp
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 truncate max-w-[140px]">{{ $nama }} x{{ $cart->jumlah }}</span>
                                <span class="text-slate-700 font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div class="border-t border-slate-100 pt-3 flex justify-between items-center">
                            <span class="font-bold text-slate-800">Total Bayar</span>
                            <span class="text-xl font-black text-blue-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Konfirmasi --}}
                <button type="button" onclick="submitConfirm(this)"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white py-4 rounded-2xl
                               font-extrabold text-lg transition-all duration-200 shadow-lg shadow-blue-100">
                    <i class="fas fa-check-circle mr-2"></i>Buat Pesanan
                </button>

                <p class="text-[10px] text-center text-slate-400">
                    <i class="fas fa-shield-alt mr-1"></i>Pembayaran aman melalui sistem BUMDes Patimban
                </p>
            </div>
        </div>
    </form>
</main>

<script>
    function submitConfirm(btn) {
        const metode = document.querySelector('input[name="metode_pembayaran"]:checked');
        const alamat = document.querySelector('textarea[name="alamat"]').value.trim();

        if (!metode) {
            alert('Silakan pilih metode pembayaran.');
            return;
        }
        if (!alamat) {
            alert('Alamat pengiriman tidak boleh kosong.');
            return;
        }

        // Efek Loading
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        btn.disabled = true;

        document.getElementById('confirmForm').submit();
    }
</script>

@endsection
