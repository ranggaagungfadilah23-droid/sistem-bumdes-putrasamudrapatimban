@extends('theme.customer')
@section('content')

<main class="max-w-3xl mx-auto px-6 py-12">

    {{-- Sukses Banner --}}
    <div class="text-center mb-10 no-print">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-4xl text-green-500"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-800">Pesanan Berhasil Dibuat!</h1>
        <p class="text-slate-500 text-sm mt-1">Simpan invoice ini sebagai bukti pemesananmu</p>
    </div>

    {{-- Card Invoice --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden" id="invoice-card">

        {{-- Header Invoice --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-200 text-xs uppercase tracking-widest font-semibold mb-1">Invoice</p>
                    <p class="text-2xl font-black">{{ $transaksis->first()->invoice_number ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-blue-200 text-xs mb-1">Tanggal</p>
                    <p class="font-semibold text-sm">{{ now()->format('d M Y, H:i') }}</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase">
                        {{ $transaksis->first()->status_pembayaran ?? 'PENDING' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Info Pembeli & Metode (SUDAH FIX DINAMIS) --}}
        <div class="grid grid-cols-2 gap-6 px-8 py-6 border-b border-slate-100">
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-2 font-semibold">Pembeli</p>
                <p class="font-bold text-slate-800">{{ auth()->user()->name }}</p>
                @if($transaksis->first() && $transaksis->first()->alamat)
                    <p class="text-sm text-slate-500 mt-1">{{ $transaksis->first()->alamat }}</p>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Metode Pembayaran</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="font-medium text-gray-800">
                        {{ ($transaksis->first()->metode_pembayaran ?? '') === 'po' ? 'Transfer PO (Pre-Order)' : 'Bayar Sekarang (Instant)' }}
                    </span>
                </div>

                <p class="text-xs mt-1 font-semibold">
                    Status:
                    @if(strtolower($transaksis->first()->status_pembayaran ?? '') === 'lunas')
                        <span class="text-green-600 uppercase">Lunas</span>
                    @elseif(strtolower($transaksis->first()->status_pembayaran ?? '') === 'gagal')
                        <span class="text-red-600 uppercase">Gagal</span>
                    @else
                        <span class="text-amber-600 uppercase">Menunggu Pembayaran</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Tabel Item --}}
        <div class="px-8 py-6">
            <p class="text-xs text-slate-400 uppercase tracking-wide mb-4 font-semibold">Detail Pesanan</p>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-left">
                        <th class="pb-3 text-slate-500 font-semibold">Item</th>
                        <th class="text-center pb-3 text-slate-500 font-semibold">Qty</th>
                        <th class="text-right pb-3 text-slate-500 font-semibold">Harga</th>
                        <th class="text-right pb-3 text-slate-500 font-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($transaksis as $trx)
                        <tr>
                            <td class="py-3">
                                <p class="font-semibold text-slate-800">
                                    {{ $trx->produk->nama_produk ?? $trx->jasa->nama_jasa ?? '-' }}
                                </p>
                                <p class="text-xs text-slate-400">{{ $trx->produk ? 'Produk' : 'Jasa' }}</p>
                            </td>
                            <td class="py-3 text-center text-slate-600">{{ $trx->jumlah }}</td>
                            <td class="py-3 text-right text-slate-600">Rp {{ number_format($trx->harga, 0, ',', '.') }}</td>
                            <td class="py-3 text-right font-bold text-slate-800">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-200">
                        <td colspan="3" class="pt-4 font-bold text-slate-700 text-right pr-4">TOTAL</td>
                        <td class="pt-4 text-right">
                            <span class="text-2xl font-black text-blue-600">
                                Rp {{ number_format($transaksis->sum('total'), 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Instruksi Pembayaran Tambahan (Hanya muncul jika belum lunas) --}}
        @if(strtolower($transaksis->first()->status_pembayaran ?? '') !== 'lunas')
            @if(($transaksis->first()->metode_pembayaran ?? '') === 'transfer_bank')
            <div class="mx-8 mb-6 p-5 bg-blue-50 border border-blue-100 rounded-2xl">
                <p class="font-bold text-blue-800 text-sm mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Instruksi Transfer Bank
                </p>
                <div class="space-y-1 text-sm text-blue-700">
                    <p>Bank BRI &bull; <span class="font-mono font-bold">1234-5678-9012</span> &bull; a/n Bumdes</p>
                    <p>Bank BNI &bull; <span class="font-mono font-bold">0987-6543-2100</span> &bull; a/n Bumdes</p>
                </div>
                <p class="text-xs text-blue-500 mt-2">
                    Transfer sesuai total: <strong>Rp {{ number_format($transaksis->sum('total'), 0, ',', '.') }}</strong>
                </p>
            </div>
            @elseif(($transaksis->first()->metode_pembayaran ?? '') === 'qris')
            <div class="mx-8 mb-6 p-5 bg-purple-50 border border-purple-100 rounded-2xl text-center">
                <p class="font-bold text-purple-800 text-sm mb-2">
                    <i class="fas fa-qrcode mr-2"></i>Scan QRIS untuk Pembayaran
                </p>
                <div class="w-32 h-32 bg-purple-100 rounded-xl mx-auto flex items-center justify-center">
                    <i class="fas fa-qrcode text-5xl text-purple-400"></i>
                </div>
                <p class="text-xs text-purple-500 mt-2">QR Code berlaku 24 jam</p>
            </div>
            @endif
        @endif

        {{-- Footer Invoice --}}
        <div class="bg-slate-50 px-8 py-5 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-400">
            <p>Terima kasih telah berbelanja di BUMDes Putra Samudra Patimban</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-3 mt-8 no-print">
        <button onclick="window.print()"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-6 border-2 border-slate-200 text-slate-600
                       rounded-2xl font-semibold hover:border-slate-300 transition text-sm">
            <i class="fas fa-print"></i> Cetak Invoice
        </button>
        <a href="{{ route('customer.pesanan') }}"
           class="flex-1 flex items-center justify-center gap-2 py-3 px-6 bg-blue-600 text-white
                  rounded-2xl font-semibold hover:bg-blue-700 transition text-sm">
            <i class="fas fa-list"></i> Lihat Semua Transaksi
        </a>
        <a href="{{ route('customer.dashboard') }}"
           class="flex-1 flex items-center justify-center gap-2 py-3 px-6 bg-green-600 text-white
                  rounded-2xl font-semibold hover:bg-green-700 transition text-sm">
            <i class="fas fa-shopping-bag"></i> Belanja Lagi
        </a>
    </div>

</main>

<style>
@media print {
    header, nav, footer, .no-print, a, button { display: none !important; }
    main { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
    #invoice-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; border-radius: 0 !important; }
}
</style>

@endsection
