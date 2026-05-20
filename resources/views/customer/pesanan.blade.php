@extends('theme.default')

{{-- Menyisipkan CSS ke stack 'styles' milik master template jika didukung theme --}}
@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link href="{{ asset('css/customer/pesanan.css') }}" rel="stylesheet">
@endpush

@section('content')
{{-- Jika theme kamu tidak menggunakan @stack('styles'), hilangkan baris @push di atas dan aktifkan tag alternatif di bawah ini: --}}
{{-- <link href="{{ asset('css/customer/pesanan.css') }}" rel="stylesheet"> --}}

<div class="pesanan-wrap">

    {{-- Back --}}
    <a href="{{ route('customer.dashboard') }}" class="back-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Dashboard
    </a>

    <h1 class="page-title">Pesanan Saya</h1>

    {{-- Tab Nav --}}
    <nav class="tab-nav" role="tablist" aria-label="Filter pesanan">
        @php
            $links = [
                'customer.pesanan'         => 'Semua',
                'customer.pesanan.pending' => 'Belum Bayar',
                'customer.pesanan.dikemas' => 'Dikemas',
                'customer.pesanan.dikirim' => 'Dikirim',
                'customer.pesanan.selesai' => 'Selesai',
            ];
        @endphp
        @foreach($links as $route => $label)
            <a href="{{ route($route) }}"
               class="tab-link {{ request()->routeIs($route) ? 'active' : '' }}"
               role="tab"
               aria-selected="{{ request()->routeIs($route) ? 'true' : 'false' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>

    {{-- Orders --}}
    <div class="orders-list">
        @forelse($pesanan as $t)
        <div class="order-card">

            {{-- Header --}}
            <div class="card-header">
                <span class="invoice-num">#{{ $t->invoice_number }}</span>
                <div class="badge-row">
                    @if($t->status_pembayaran == 'Lunas')
                        <span class="badge badge-lunas">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            Lunas
                        </span>
                    @else
                        <span class="badge badge-pending">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Belum Bayar
                        </span>
                    @endif

                    @if($t->status_pembayaran == 'Lunas')
                        @if($t->status_pengiriman === 'Dikirim')
                            <span class="badge badge-dikirim">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                Dikirim
                            </span>
                        @elseif($t->status_pengiriman === 'Selesai')
                            <span class="badge badge-selesai">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                Selesai
                            </span>
                        @elseif($t->status_pengiriman === 'Dikemas')
                            <span class="badge badge-dikemas">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                Dikemas
                            </span>
                        @else
                            <span class="badge badge-dikemas">Diproses</span>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Product Info --}}
            <div class="product-row">
                <div class="product-img">
                    @if($t->produk && $t->produk->foto)
                        <img src="{{ asset('storage/' . $t->produk->foto) }}" alt="{{ $t->produk->nama_produk }}">
                    @else
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-name">{{ $t->produk->nama_produk ?? $t->jasa->nama_jasa ?? 'Item' }}</div>
                    <div class="product-meta">{{ $t->jumlah }} item</div>
                    <div class="product-total">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
                    <div class="product-method">
                        {{ $t->metode_pembayaran == 'po' ? '🔁 Transfer PO' : '⚡ Bayar Sekarang' }}
                    </div>
                </div>
            </div>

            <hr class="divider">

            {{-- Footer Actions --}}
            <div class="card-footer">
                <span class="order-date">{{ $t->created_at->format('d M Y, H:i') }}</span>

                <div class="action-group">
                    {{-- Skenario 1: Jika Belum Bayar --}}
                    @if($t->status_pembayaran === 'pending')
                        @if($t->metode_pembayaran === 'po')
                            <form action="{{ route('customer.pesanan.konfirmasi-diterima', $t->invoice_number) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin pesanan sudah diterima?')">
                                @csrf
                                <button type="submit" class="btn btn-green">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="20 6 9 17 4 12"/></svg>
                                    Konfirmasi Diterima
                                </button>
                            </form>
                        @else
                            <a href="{{ route('checkout_payment', $t->invoice_number) }}" class="btn btn-orange">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Bayar Sekarang
                            </a>
                        @endif

                    {{-- Skenario 2: Jika Sudah Lunas --}}
                    @else
                        @if($t->status_pengiriman === 'Dikirim')
                            <form action="{{ route('customer.pesanan.konfirmasi-diterima', $t->invoice_number) }}" method="POST"
                                  onsubmit="return confirm('Apakah barang sudah diterima dengan baik?')">
                                @csrf
                                <button type="submit" class="btn btn-indigo">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="20 6 9 17 4 12"/></svg>
                                    Barang Diterima
                                </button>
                            </form>
                        @elseif($t->status_pengiriman === 'Selesai')
                            <span class="status-chip chip-done">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" points="20 6 9 17 4 12"/></svg>
                                Pesanan Selesai
                            </span>
                        @else
                            <span class="status-chip chip-process">
                                <svg class="spin-icon" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Sedang Diproses
                            </span>
                        @endif
                    @endif

                    <a href="{{ route('customer.invoice', $t->invoice_number) }}" class="btn btn-ghost">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Lihat Detail
                    </a>
                </div>
            </div>

        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="empty-title">Belum ada pesanan</div>
            <div class="empty-sub">Pesanan yang kamu buat akan tampil di sini.</div>
        </div>
        @endforelse
    </div>

</div>
@endsection
