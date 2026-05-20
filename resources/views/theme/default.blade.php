<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" href="{{ asset('asset/img/logoBumdes.png') }}" type="image/png">
    @include('theme.head')
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased relative">

    @include('theme.preloader')

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-[80] hidden md:hidden transition-opacity cursor-pointer" onclick="toggleSidebar()"></div>

    <div class="flex min-h-screen">


        @if(Auth::check())
            @if(Auth::user()->role === 'admin')
                @include('theme.partials.sidebar-admin')
            @elseif(Auth::user()->role === 'kepala-bumdes')
                @include('theme.partials.sidebar-kepala')
            @elseif(Auth::user()->role === 'mitra')
                @include('theme.partials.sidebar-mitra')
            @endif
        @endif


        <div class="flex-1 flex flex-col min-w-0">


            @include('theme.navbar')


            <main class="flex-1 p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>

            @include('theme.footer')
        </div>
    </div>

    @include('theme.foot')
    @stack('scripts')

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>
