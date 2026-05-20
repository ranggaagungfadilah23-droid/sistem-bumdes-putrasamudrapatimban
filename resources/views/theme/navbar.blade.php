{{-- NAVBAR FULL FINAL --}}
<header class="sticky top-0 z-50 w-full bg-white border-b border-slate-200/60 px-4 md:px-8 h-16 md:h-20 flex items-center shadow-sm">
    <div class="flex items-center justify-between w-full max-w-7xl mx-auto h-full gap-2">

        {{-- 1. BAGIAN KIRI: Sidebar Toggle & Judul --}}
        {{-- Menggunakan flex-1 agar area kiri bisa melar, dan min-w-0 agar teks truncate berfungsi --}}
        <div class="flex items-center min-w-0 flex-1 md:flex-initial">
            <button onclick="toggleSidebar()" class="md:hidden text-slate-600 hover:text-emerald-600 focus:outline-none mr-3 shrink-0">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 class="text-xs sm:text-sm md:text-lg font-bold text-slate-800 truncate pr-2">
                Dashboard {{ auth()->check() ? ucwords(str_replace('-', ' ', auth()->user()->role)) : 'Guest' }}
            </h2>
        </div>

        {{-- 2. BAGIAN KANAN: Aksi & Profil --}}
        {{-- Kita hapus shrink-0 kaku dan atur max-w agar seimbang di mobile --}}
        <div class="flex items-center gap-2 md:gap-4 max-w-full">

            {{-- Wrapper Search agar di mobile tidak merusak flexbox utama --}}
            <div class="min-w-0">
                @stack('navbar-search')
            </div>

            @auth
                @if(auth()->user()->role == 'customer')
                    <a href="{{ route('customer.pesanan') }}" class="hidden md:flex items-center gap-2 text-slate-600 hover:text-blue-600 font-bold text-sm transition px-3">
                        <i class="fas fa-receipt"></i> <span class="hidden lg:inline">Pesanan</span>
                    </a>

                    <a href="{{ route('customer.cart') }}" class="relative w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:text-blue-600 transition-colors border border-slate-100 shrink-0">
                        <i class="fas fa-shopping-cart text-sm md:text-base"></i>
                        @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count(); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endif
            @endauth

            {{-- Tombol Notifikasi --}}
            @php
                $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('notifiable_id', auth()->id())
                    ->whereNull('read_at')
                    ->count();
            @endphp

            <a href="{{ route('notifications.index') }}" class="relative w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:text-blue-600 transition-colors border border-slate-100 shrink-0">
                <i class="fas fa-bell text-sm md:text-base"></i>
                <span id="notif-badge"
                      class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white {{ $unreadCount > 0 ? '' : 'hidden' }}">
                    {{ $unreadCount }}
                </span>
            </a>

            {{-- Dropdown Profil --}}
            <div class="relative flex items-center ml-1 md:ml-2 shrink-0">
                <div id="profileTrigger" class="w-9 h-9 md:w-10 md:h-10 bg-emerald-800 text-emerald-100 rounded-full flex items-center justify-center font-extrabold cursor-pointer hover:bg-emerald-900 transition shadow-sm border-2 border-emerald-50 overflow-hidden text-xs md:text-sm">
                    @if(Auth::check()) {{ strtoupper(substr(Auth::user()->name, 0, 1)) }} @else <i class="fas fa-user"></i> @endif
                </div>

                <div id="profileMenu" class="hidden absolute right-0 top-full mt-3 w-52 bg-white border border-slate-200 rounded-2xl shadow-xl z-[60] py-2">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Akun Saya</p>
                        <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all">
                        <i class="fas fa-user-edit opacity-50 text-xs"></i> Edit Profil
                    </a>
                    <hr class="my-1 border-slate-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 font-bold hover:bg-red-50 transition-all text-left">
                            <i class="fas fa-power-off text-xs"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.getElementById('profileTrigger');
        const menu = document.getElementById('profileMenu');

        if (trigger && menu) {
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && e.target !== trigger) {
                    menu.classList.add('hidden');
                }
            });
        }
    });
</script>
