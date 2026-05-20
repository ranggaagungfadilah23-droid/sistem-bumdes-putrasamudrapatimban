@extends('theme.default')

@section('title', 'Beranda - BUMDes Patimban')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/dashboard.css') }}">
@endpush

@section('content')
    {{-- 1. HEADER & PENCARIAN --}}
    <div class="search-container mb-10">
        <div class="search-input-wrapper w-full md:w-2/3 mx-auto md:mx-0 relative group">
            <input type="text" placeholder="Cari produk BUMDes, jajanan, atau jasa..."
                   class="w-full pl-14 pr-32 py-5 rounded-3xl border border-slate-200 bg-white shadow-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all font-medium text-slate-700">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <button class="absolute right-2 top-2 bottom-2 bg-blue-600 text-white px-8 rounded-2xl text-sm font-bold hover:bg-blue-700 transition active:scale-95">
                Cari
            </button>
        </div>
    </div>

    {{-- 2. BANNER PROMO --}}
    <div class="mb-12 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-blue-500/20 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-800 relative">
        <div class="flex items-center justify-between p-10 md:p-14">
            <div class="text-white z-10 w-full md:w-2/3">
                <span class="bg-yellow-400 text-blue-900 text-[10px] font-black uppercase px-4 py-1.5 rounded-full mb-4 inline-block tracking-widest shadow-lg">Promo Spesial</span>
                <h2 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight">Dukung Produk<br>Lokal Patimban</h2>
                <p class="text-blue-100 text-base opacity-90 leading-relaxed">Nikmati gratis ongkir untuk wilayah Patimban. Belanja mudah, cepat, dan aman langsung dari BUMDes.</p>
            </div>
            <div class="hidden md:block w-1/3 text-right">
                <i class="fas fa-shopping-bag text-[150px] text-white opacity-10 transform rotate-12"></i>
            </div>
        </div>
    </div>

    {{-- 3. KATEGORI --}}
    <div class="mb-12">
        <h3 class="text-sm font-bold text-slate-400 mb-6 uppercase tracking-widest px-1">Kategori Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="#" class="flex items-center gap-6 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:border-blue-200 hover:shadow-xl transition-all duration-300 group">
                <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center shrink-0 group-hover:bg-blue-600 transition-all duration-300">
                    <i class="fas fa-box-open text-3xl text-blue-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h4 class="text-xl font-extrabold text-slate-800 mb-1">Katalog Produk</h4>
                    <p class="text-sm text-slate-500">Sembako, jajanan, dan kerajinan lokal.</p>
                </div>
            </a>
            <a href="#" class="flex items-center gap-6 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:border-emerald-200 hover:shadow-xl transition-all duration-300 group">
                <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center shrink-0 group-hover:bg-emerald-500 transition-all duration-300">
                    <i class="fas fa-tools text-3xl text-emerald-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h4 class="text-xl font-extrabold text-slate-800 mb-1">Layanan Jasa</h4>
                    <p class="text-sm text-slate-500">Bengkel, pangkas rambut, dan lainnya.</p>
                </div>
            </a>
        </div>
    </div>

    {{-- 4. LAYANAN JASA --}}
    <div class="mb-12">
        <h3 class="text-sm font-bold text-slate-400 mb-6 uppercase tracking-widest px-1">Layanan Jasa</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse ($jasas as $jasa)
                <a href="{{ route('customer.jasa.show', $jasa->id) }}" class="group flex items-center gap-5 p-5 bg-white rounded-3xl border border-slate-100 hover:border-emerald-200 hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 bg-slate-100 relative overflow-hidden rounded-2xl">
                        <img src="{{ asset('storage/' . $jasa->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-base font-bold text-slate-800 mb-1">{{ $jasa->nama_jasa }}</h4>
                        <p class="text-sm font-black text-rose-500 mb-2">Rp {{ number_format($jasa->harga, 0, ',', '.') }}</p>
                        <div class="text-xs text-slate-400 font-medium">
                            <i class="fas fa-star text-yellow-400"></i> 4.9 | Tersedia
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-slate-400 italic px-2">Belum ada layanan jasa tersedia.</p>
            @endforelse
        </div>
    </div>

    {{-- 5. REKOMENDASI PRODUK --}}
    <div class="mb-20">
        <h3 class="text-sm font-bold text-slate-400 mb-6 uppercase tracking-widest px-1">Rekomendasi Untukmu</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @forelse ($produks as $produk)
                <a href="{{ route('customer.produk.show', $produk->id) }}" class="group block bg-white rounded-3xl overflow-hidden border border-slate-100 hover:shadow-2xl hover:border-blue-100 transition-all duration-300">
                    <div class="aspect-square bg-slate-50 relative overflow-hidden">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-800 text-[9px] font-black px-2 py-1 rounded-lg shadow-sm">BUMDES</div>
                    </div>
                    <div class="p-4">
                        <h4 class="text-sm font-bold text-slate-800 line-clamp-1 mb-1">{{ $produk->nama_produk }}</h4>
                        <div class="text-sm font-black text-rose-600 mb-2">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                        <div class="flex items-center text-[11px] text-slate-400 font-medium">
                            <i class="fas fa-star text-yellow-400 mr-1"></i> 4.9 | Stok: {{ $produk->jumlah }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 text-slate-400 italic">
                    Belum ada produk tersedia saat ini.
                </div>
            @endforelse
        </div>
    </div>
@endsection
