{{-- sidebar-mitra.blade.php --}}
<aside class="app-sidebar" id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon" style="background: #1f6feb;">
            <i class="fas fa-store"></i>
        </div>
        <span class="sidebar-logo-text">Mitra <span style="color: #58a6ff;">Patimban</span></span>
        <div class="sidebar-logo-close" onclick="closeSidebar()" aria-label="Tutup menu">
            <i class="fas fa-times" style="font-size: 14px;"></i>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="sidebar-body">

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

            $mitraIdSidebar   = Auth::user()->mitra->id ?? null;
            $jumlahPesananBaru = \App\Models\Transaksi::where('mitra_id', $mitraIdSidebar)
                                    ->whereIn('status_pengiriman', ['menunggu','Diproses'])
                                    ->whereIn('status_pembayaran', ['Lunas','pending'])
                                    ->count();

            $jumlahUlasanBaru = \App\Models\Ulasan::where('mitra_id', Auth::id())
                                    ->whereNull('balasan_mitra')
                                    ->count();
        @endphp

        <div class="nav-section">
            <p class="nav-section-label">Utama</p>
            <a href="{{ route('mitra.dashboard') }}"
               class="nav-link {{ $isDashboardActive ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-section-label">Manajemen Usaha</p>
            <a href="{{ route('mitra.kelola') }}"
               class="nav-link {{ $isKelolaActive ? 'active' : '' }}">
                <i class="{{ $icon }}"></i> {{ $label }}
            </a>
            <a href="{{ route('mitra.pesanan.index') }}"
               class="nav-link {{ request()->routeIs('mitra.pesanan.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-basket"></i> Pesanan Masuk
                @if($jumlahPesananBaru > 0)
                    <span class="nav-badge nav-badge-blue">{{ $jumlahPesananBaru }}</span>
                @endif
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-section-label">Keuangan</p>
            <a href="{{ route('mitra.pendapatan.index') }}"
               class="nav-link {{ request()->routeIs('mitra.pendapatan.index') || request()->routeIs('mitra.pendapatan.laporan*') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i> Pendapatan Saya
            </a>
            <a href="{{ route('mitra.laporan.index') }}"
               class="nav-link {{ request()->routeIs('mitra.laporan.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice"></i> Laporan Transaksi
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-section-label">Ulasan & Keluhan</p>
            <a href="{{ route('mitra.pendapatan.ulasan.index') }}"
               class="nav-link {{ request()->routeIs('mitra.pendapatan.ulasan.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Ulasan Masuk
                @if($jumlahUlasanBaru > 0)
                    <span class="nav-badge" style="background: rgba(187,128,9,0.2); color: #e3b341;">
                        {{ $jumlahUlasanBaru }} baru
                    </span>
                @endif
            </a>
        </div>

    </div>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-profile">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
            </div>
            <div style="overflow: hidden; flex: 1;">
                <div class="sidebar-profile-name">{{ Auth::user()->name ?? 'Mitra BUMDes' }}</div>
                <div class="sidebar-profile-role">Mitra Resmi</div>
            </div>
        </div>
    </div>

</aside>
