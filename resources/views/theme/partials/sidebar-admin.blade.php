<aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-[#0f172a] text-slate-400 flex flex-col z-[100] border-r border-slate-800/50 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out">
    <div class="h-20 flex items-center px-8 border-b border-slate-800/50 shrink-0">
        <div class="w-9 h-9 bg-blue-600 text-white rounded-xl flex items-center justify-center text-lg shadow-lg shadow-blue-600/40 mr-3">
            <i class="fas fa-layer-group"></i>
        </div>
        <span class="font-extrabold text-xl text-white tracking-tight">BUMDes <span class="text-blue-500">Patimban</span></span>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-7 custom-scrollbar">

        <div>
            <p class="px-4 text-[10px] font-bold mb-4 uppercase tracking-[0.15em] text-slate-500">Utama</p>
            <nav class="space-y-1.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-chart-pie w-5 text-center text-sm"></i> Dashboard
                </a>

            </nav>
        </div>

        <div>
            <p class="px-4 text-[10px] font-bold mb-4 uppercase tracking-[0.15em] text-slate-500">Kemitraan</p>
            <nav class="space-y-1.5">
                <a href="{{ route('admin.pengajuan') }}"
                   class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.pengajuan') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all group font-semibold">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-check w-5 text-center text-sm"></i> Pengajuan
                    </div>
                    <span class="{{ request()->routeIs('admin.pengajuan') ? 'bg-white text-blue-600' : 'bg-amber-500/10 text-amber-500 group-hover:bg-amber-500 group-hover:text-white' }} text-[10px] px-2 py-0.5 rounded-lg font-bold transition-colors">
                        {{ \App\Models\User::where('role', 'mitra')->where('status', 'pending')->count() }}
                    </span>
                </a>

                <a href="{{ route('admin.mitra.index') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.mitra.index') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-users w-5 text-center text-sm"></i> Data Mitra
                </a>
            </nav>
        </div>

        <div>
            <p class="px-4 text-[10px] font-bold mb-4 uppercase tracking-[0.15em] text-slate-500">Keuangan</p>
            <nav class="space-y-1.5">
                <a href="{{ route('admin.bagihasil') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.bagihasil') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-hand-holding-usd w-5 text-center text-sm"></i> Bagi Hasil
                </a>
                <a href="{{ route('admin.laporan') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.laporan') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-file-invoice-dollar w-5 text-center text-sm"></i> Laporan Keuangan
                </a>
            </nav>
        </div>

        <div>
            <p class="px-4 text-[10px] font-bold mb-4 uppercase tracking-[0.15em] text-slate-500">Lainnya</p>
            <nav class="space-y-1.5">
                <a href="{{ route('admin.histori') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.histori') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-history w-5 text-center text-sm"></i> Histori Aktivitas
                </a>
                {{-- <a href="{{ route('admin.settings') }}"
                   class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.settings') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800/50 hover:text-slate-100' }} rounded-xl transition-all font-semibold">
                    <i class="fas fa-cog w-5 text-center text-sm"></i> Pengaturan Sistem
                </a> --}}
            </nav>
        </div>
    </div>

    <div class="p-5 border-t border-slate-800/50 bg-slate-900/50 shrink-0">
        <div class="flex items-center gap-3 p-2 rounded-2xl hover:bg-slate-800 transition-colors cursor-pointer group">
            <div class="w-10 h-10 bg-slate-800 text-slate-200 rounded-full flex items-center justify-center font-bold border border-slate-700 group-hover:border-blue-500 transition-all">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate leading-tight">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-[10px] text-slate-500 font-medium tracking-wide">Super Administrator</p>
            </div>
        </div>
    </div>
</aside>
