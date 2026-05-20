<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-[80] hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

{{-- Sidebar Container --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-[#0f172a] text-slate-400 flex flex-col z-[100] border-r border-slate-800/50 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out shadow-2xl">

    {{-- Header Sidebar --}}
    <div class="h-20 flex items-center justify-between px-8 border-b border-slate-800/50 shrink-0">
        <div class="flex items-center">
            <div class="w-9 h-9 bg-blue-600 text-white rounded-xl flex items-center justify-center text-lg shadow-lg shadow-blue-600/40 mr-3">
                <i class="fas fa-store"></i>
            </div>
            <span class="font-extrabold text-xl text-white tracking-tight">Mitra <span class="text-blue-500">Patimban</span></span>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-7 custom-scrollbar">

        @php
            $jenis = strtolower(Auth::user()->mitra->jenis_usaha ?? 'produk');
            $label = ($jenis == 'jasa') ? 'Kelola Layanan Jasa' : 'Kelola Produk';
            $icon  = ($jenis == 'jasa') ? 'fas fa-concierge-bell' : 'fas fa-box-open';


            $isDashboardActive = request()->routeIs('mitra.dashboard')
                              || request()->routeIs('mitra.produk.dashboard')
                              || request()->routeIs('mitra.jasa.dashboard');


            $isKelolaActive = request()->routeIs('mitra.kelola')
                           || (request()->routeIs('mitra.produk.*') && !request()->routeIs('mitra.produk.dashboard'))
                           || (request()->routeIs('mitra.jasa.*')   && !request()->routeIs('mitra.jasa.dashboard'));

            $mitraIdSidebar    = Auth::user()->mitra->id ?? null;
            $jumlahPesananBaru = \App\Models\Transaksi::where('mitra_id', $mitraIdSidebar)
                                    ->whereIn('status_pengiriman', ['menunggu', 'Diproses'])
                                    ->whereIn('status_pembayaran', ['Lunas', 'pending'])
                                    ->count();
        @endphp

        {{-- Section Utama --}}
        <div>
            <p class="px-4 text-[10px] font-bold mb-4 uppercase tracking-[0.15em] text-slate-500">Utama</p>
            <nav class="space-y-1.5">
                <a href="{{ route('mitra.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ $isDashboardActive ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-chart-pie w-5 text-center text-sm"></i> Dashboard
                </a>
            </nav>
        </div>

        {{-- Section Manajemen Usaha --}}
        <div>
            <p class="px-4 text-[10px] font-bold mb-4 uppercase tracking-[0.15em] text-slate-500">Manajemen Usaha</p>
            <nav class="space-y-1.5">
                <a href="{{ route('mitra.kelola') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ $isKelolaActive ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="{{ $icon }} w-5 text-center text-sm"></i> {{ $label }}
                </a>

                <a href="{{ route('mitra.pesanan.index') }}"
                   class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('mitra.pesanan.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all group font-semibold">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shopping-basket w-5 text-center text-sm"></i> Pesanan Masuk
                    </div>
                    @if($jumlahPesananBaru > 0)
                        <span class="bg-blue-500/20 text-blue-400 group-hover:bg-blue-500 group-hover:text-white text-[10px] px-2 py-0.5 rounded-lg font-bold transition-colors">
                            {{ $jumlahPesananBaru }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        {{-- Section Keuangan --}}
        <div>
            <p class="px-4 text-[10px] font-bold mb-4 uppercase tracking-[0.15em] text-slate-500">Keuangan</p>
            <nav class="space-y-1.5">
                <a href="{{ route('mitra.pendapatan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('mitra.pendapatan.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-wallet w-5 text-center text-sm"></i> Pendapatan Saya
                </a>
                <a href="{{ route('mitra.laporan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('mitra.laporan.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-file-invoice w-5 text-center text-sm"></i> Laporan Transaksi
                </a>
            </nav>
        </div>
    </div>

    {{-- Footer Profile Sidebar --}}
    <div class="p-5 border-t border-slate-800/50 bg-slate-900/50 shrink-0">
        <div class="flex items-center gap-3 p-2 rounded-2xl hover:bg-slate-800 transition-colors cursor-pointer group">
            <div class="w-10 h-10 bg-slate-800 text-slate-200 rounded-full flex items-center justify-center font-bold border border-slate-700 group-hover:border-blue-500 transition-all">
                {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate leading-tight">{{ Auth::user()->name ?? 'Mitra BUMDes' }}</p>
                <p class="text-[10px] text-slate-500 font-medium tracking-wide">Mitra Resmi</p>
            </div>
        </div>
    </div>
</aside>
