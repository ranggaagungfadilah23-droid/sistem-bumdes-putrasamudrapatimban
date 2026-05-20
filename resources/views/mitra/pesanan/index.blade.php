@extends('theme.default')

@section('content')
<div class="container mx-auto p-4 md:p-6 max-w-6xl">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Pesanan Masuk (Mitra)</h2>
        <span class="text-sm bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-semibold">
            Mode Kelola Pesanan
        </span>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg text-sm font-medium border border-green-200">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-lg text-sm font-medium border border-red-200">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Search & Filter Bar --}}
    <form method="GET" action="{{ route('mitra.pesanan.index') }}" class="mb-4">
        <div class="flex flex-wrap gap-2 items-center bg-white border border-gray-100 rounded-xl shadow-sm p-3">

            {{-- Search Input --}}
            <div class="flex items-center gap-2 flex-1 min-w-[200px] bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari invoice atau nama customer..."
                    class="bg-transparent text-sm text-gray-700 placeholder-gray-400 outline-none w-full"
                >
            </div>

            {{-- Filter Status Pengiriman --}}
            <select name="status" class="text-sm border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-700 outline-none focus:border-orange-400 cursor-pointer">
                <option value="">Semua Status Kirim</option>
                <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                <option value="Diproses"  {{ request('status') === 'Diproses'  ? 'selected' : '' }}>Diproses</option>
                <option value="Dikemas"   {{ request('status') === 'Dikemas'   ? 'selected' : '' }}>Dikemas</option>
                <option value="Dikirim"   {{ request('status') === 'Dikirim'   ? 'selected' : '' }}>Dikirim</option>
                <option value="Diterima"  {{ request('status') === 'Diterima'  ? 'selected' : '' }}>Diterima</option>
                <option value="Selesai"   {{ request('status') === 'Selesai'   ? 'selected' : '' }}>Selesai</option>
            </select>

            {{-- Filter Metode Pembayaran --}}
            <select name="metode" class="text-sm border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-700 outline-none focus:border-orange-400 cursor-pointer">
                <option value="">Semua Metode</option>
                <option value="po"      {{ request('metode') === 'po'      ? 'selected' : '' }}>PO</option>
                <option value="instant" {{ request('metode') === 'instant' ? 'selected' : '' }}>Instant</option>
            </select>

            {{-- Tombol --}}
            <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-lg transition">
                Cari
            </button>

            @if(request()->hasAny(['search', 'status', 'metode']))
                <a href="{{ route('mitra.pesanan.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition">
                    Reset
                </a>
            @endif
        </div>

        {{-- Info hasil filter --}}
        @if(request()->hasAny(['search', 'status', 'metode']))
            <p class="text-xs text-gray-500 mt-2 ml-1">
                Menampilkan {{ $pesanan->total() }} hasil
                @if(request('search')) untuk "<strong>{{ request('search') }}</strong>"@endif
                @if(request('status')) · Status: <strong>{{ request('status') }}</strong>@endif
                @if(request('metode')) · Metode: <strong>{{ strtoupper(request('metode')) }}</strong>@endif
            </p>
        @endif
    </form>

    {{-- Tabel Pesanan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4 w-10 text-center">#</th>
                        <th class="p-4">Invoice / Tanggal</th>
                        <th class="p-4">Pelanggan</th>
                        <th class="p-4">Item Pesanan</th>
                        <th class="p-4 text-center">Status Bayar</th>
                        <th class="p-4 text-center">Status Kirim</th>
                        <th class="p-4 text-right">Aksi Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                    @forelse($pesanan as $t)
                    @php $no = ($pesanan->currentPage() - 1) * $pesanan->perPage() + $loop->iteration; @endphp
                    <tr class="hover:bg-gray-50/50 transition">

                        {{-- Nomor Urut --}}
                        <td class="p-4 text-center text-xs font-bold text-gray-400">
                            {{ $no }}
                        </td>

                        {{-- Invoice & Tanggal --}}
                        <td class="p-4">
                            <span class="font-mono font-bold text-gray-900 block">#{{ $t->invoice_number }}</span>
                            <span class="text-xs text-gray-400 block mt-1">{{ $t->created_at->format('d M Y, H:i') }}</span>
                            <span class="text-[10px] inline-block mt-1 px-2 py-0.5 font-bold uppercase rounded bg-gray-100 text-gray-600">
                                {{ $t->metode_pembayaran === 'po' ? 'Metode: PO' : 'Metode: Instant' }}
                            </span>
                        </td>

                        {{-- Pelanggan --}}
                        <td class="p-4">
                            <span class="font-semibold block text-gray-800">{{ $t->customer->name ?? 'User Pembeli' }}</span>
                        </td>

                        {{-- Item & Total --}}
                        <td class="p-4">
                            <span class="font-medium block text-gray-800">
                                {{ $t->produk->nama_produk ?? $t->jasa->nama_jasa ?? 'Item Jasa/Produk' }}
                            </span>
                            <span class="text-xs text-gray-500 block">
                                {{ $t->jumlah }} item | Rp {{ number_format($t->total, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Status Pembayaran --}}
                        <td class="p-4 text-center">
                            @php
                                $bayarClass = match($t->status_pembayaran) {
                                    'Lunas' => 'bg-green-100 text-green-700',
                                    'Gagal' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full inline-block {{ $bayarClass }}">
                                {{ strtoupper($t->status_pembayaran ?? 'Pending') }}
                            </span>
                        </td>

                        {{-- Status Pengiriman --}}
                        <td class="p-4 text-center">
                            @php
                                $kirimClass = match($t->status_pengiriman) {
                                    'Selesai'  => 'bg-indigo-100 text-indigo-700',
                                    'Diterima' => 'bg-green-100 text-green-700',
                                    'Dikirim'  => 'bg-purple-100 text-purple-700',
                                    'Dikemas'  => 'bg-blue-100 text-blue-700',
                                    'Diproses' => 'bg-cyan-100 text-cyan-700',
                                    default    => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full uppercase inline-block {{ $kirimClass }}">
                                {{ $t->status_pengiriman ?? 'Menunggu' }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="p-4 text-right whitespace-nowrap">
                            <div class="flex gap-2 justify-end items-center">

                                {{-- Tombol Konfirmasi Lunas PO --}}
                                @if($t->metode_pembayaran === 'po' && $t->status_pengiriman === 'Dikirim' && $t->status_pembayaran !== 'Lunas')
                                    <form action="{{ route('mitra.pesanan.konfirmasi-lunas', $t->id) }}" method="POST"
                                          onsubmit="return confirm('Konfirmasi pelunasan PO untuk invoice #{{ $t->invoice_number }}?')">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                            💰 Konfirmasi Lunas
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol Update Status --}}
                                @if($t->status_pengiriman === 'Selesai')
                                    <span class="text-xs text-green-600 font-bold bg-green-50 px-2 py-1 rounded">
                                        ✅ Transaksi Selesai
                                    </span>
                                @elseif($t->metode_pembayaran === 'po' && $t->status_pengiriman === 'Dikirim')
                                    <span class="text-xs text-amber-600 font-medium italic bg-amber-50 px-2 py-1 rounded border border-amber-100">
                                        Menunggu Pelunasan
                                    </span>
                                @else
                                    <form action="{{ route('mitra.pesanan.update-status', $t->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                            @if(is_null($t->status_pengiriman) || $t->status_pengiriman === 'menunggu')
                                                📋 Proses Pesanan
                                            @elseif($t->status_pengiriman === 'Diproses')
                                                📦 Kemas Pesanan
                                            @elseif($t->status_pengiriman === 'Dikemas')
                                                🚚 Kirim Pesanan
                                            @elseif($t->status_pengiriman === 'Diterima')
                                                ✅ Selesaikan Pesanan
                                            @else
                                                🔄 Update Status
                                            @endif
                                        </button>
                                    </form>
                                @endif

                                {{-- Detail Invoice --}}
                                <a href="{{ route('mitra.pesanan.cetak-invoice', $t->id) }}" target="_blank"
                                   class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-lg transition">
                                    Detail
                                </a>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400 italic">
                            @if(request()->hasAny(['search', 'status', 'metode']))
                                Tidak ada pesanan yang cocok dengan filter kamu.
                                <a href="{{ route('mitra.pesanan.index') }}" class="text-orange-500 underline ml-1">Reset filter</a>
                            @else
                                Belum ada pesanan masuk untuk tokomu.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pesanan->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-100">
            {{ $pesanan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
