@extends('theme.default')

@section('content')
<div class="p-6 bg-gray-50/50 min-h-screen">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-gray-500 text-sm mt-1">Pantau performa dan ringkasan pendaftaran Mitra BUMDes Anda hari ini.</p>
        </div>

        <div class="text-sm text-gray-400 font-medium bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100">
            <i class="fas fa-calendar-alt mr-2"></i> {{ date('d M Y') }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <a href="{{ route('kepala-bumdes.mitra.index') }}" class="block bg-blue-50/50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 hover:bg-blue-100/50 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Mitra Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800">
                        {{ \App\Models\User::where('role', 'mitra')->where('status', 'aktif')->count() }}
                    </h3>
                </div>
                <div class="bg-blue-500 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <i class="fas fa-store text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-blue-600 font-semibold">
                <span>Lihat Detail Mitra</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </a>

        <a href="{{ route('kepala-bumdes.pengajuan') }}" class="block bg-amber-50/50 border border-amber-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 hover:bg-amber-100/50 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Pengajuan Baru</p>
                    <h3 class="text-3xl font-bold text-gray-800">
                        {{ \App\Models\User::where('role', 'mitra')->where('status', 'menunggu_kepala')->count() }}
                    </h3>
                </div>
                <div class="bg-amber-500 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-amber-600 font-semibold">
                <span>Butuh Persetujuan Anda</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </a>

        <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Estimasi Pendapatan</p>
                    <h3 class="text-3xl font-bold text-gray-800">Rp 0</h3>
                </div>
                <div class="bg-emerald-500 text-white w-12 h-12 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                    <i class="fas fa-wallet text-lg"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-emerald-600 font-semibold">
                <span class="bg-emerald-100 px-2 py-1 rounded">Update Real-time</span>
            </div>
        </div>

    </div>

    <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center shadow-sm">
        <img src="https://illustrations.popsy.co/white/data-analysis.svg" alt="Empty State" class="w-48 mx-auto mb-4">
        <h4 class="text-lg font-bold text-gray-800">Belum ada aktivitas terbaru</h4>
        <p class="text-gray-500 text-sm">Semua data pendaftaran mitra akan muncul di sini setelah diproses.</p>
    </div>

</div>
@endsection
