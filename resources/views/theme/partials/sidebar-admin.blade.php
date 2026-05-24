{{-- sidebar-admin.blade.php --}}
<aside class="app-sidebar" id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon" style="background: #1d4ed8;">
            <i class="fas fa-shield-alt"></i>
        </div>
        <span class="sidebar-logo-text">BUMDes <span style="color: #60a5fa;">Patimban</span></span>
        <div class="sidebar-logo-close" onclick="closeSidebar()" aria-label="Tutup menu">
            <i class="fas fa-times" style="font-size: 14px;"></i>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="sidebar-body">

        <div class="nav-section">
            <p class="nav-section-label">Utama</p>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.dashboard') ? '--sidebar-active-color: #60a5fa; --sidebar-active-bg: rgba(29,78,216,0.12);' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-section-label">Verifikasi</p>
            <a href="{{ route('admin.pengajuan') }}"
               class="nav-link {{ request()->routeIs('admin.pengajuan') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.pengajuan') ? '--sidebar-active-color: #60a5fa; --sidebar-active-bg: rgba(29,78,216,0.12);' : '' }}">
                <i class="fas fa-file-alt"></i> Pengajuan Mitra
                @php $pendingCount = \App\Models\User::where('role','mitra')->where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="nav-badge nav-badge-blue">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.mitra.index') }}"
               class="nav-link {{ request()->routeIs('admin.mitra.index') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.mitra.index') ? '--sidebar-active-color: #60a5fa; --sidebar-active-bg: rgba(29,78,216,0.12);' : '' }}">
                <i class="fas fa-users"></i> Data Mitra
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-section-label">Keuangan</p>
            <a href="{{ route('admin.bagihasil') }}"
               class="nav-link {{ request()->routeIs('admin.bagihasil') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.bagihasil') ? '--sidebar-active-color: #60a5fa; --sidebar-active-bg: rgba(29,78,216,0.12);' : '' }}">
                <i class="fas fa-hand-holding-usd"></i> Bagi Hasil
            </a>
            <a href="{{ route('admin.laporan') }}"
               class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.laporan') ? '--sidebar-active-color: #60a5fa; --sidebar-active-bg: rgba(29,78,216,0.12);' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i> Laporan
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-section-label">Sistem</p>
            <a href="{{ route('admin.histori') }}"
               class="nav-link {{ request()->routeIs('admin.histori') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.histori') ? '--sidebar-active-color: #60a5fa; --sidebar-active-bg: rgba(29,78,216,0.12);' : '' }}">
                <i class="fas fa-history"></i> Histori Aktivitas
            </a>
        </div>

    </div>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-profile">
            <div class="sidebar-avatar" style="background: #1e3a5f; border-color: #1d4ed8; color: #60a5fa;">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div style="overflow: hidden; flex: 1;">
                <div class="sidebar-profile-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="sidebar-profile-role">Administrator</div>
            </div>
        </div>
    </div>

</aside>
