@extends('theme.default')
@section('title', 'Pendapatan Saya')

@section('content')
<div class="container-fluid px-6 py-8">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Keuangan & Pendapatan</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau hasil bagi hasil penjualan {{ $jenisUsaha }} Anda.</p>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3 rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3 rounded-2xl flex items-center gap-2">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- KARTU REKAP --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        {{-- Total Pendapatan --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-3xl p-7 text-white shadow-xl shadow-blue-500/20">
            <div class="flex items-center justify-between mb-4">
                <p class="text-blue-100 font-semibold text-xs uppercase tracking-widest">Total Pendapatan</p>
                <div class="bg-white/20 rounded-2xl p-2">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
            </div>
            <h2 class="text-3xl font-black">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            <p class="text-blue-200 text-xs mt-2">Akumulasi nominal mitra</p>
        </div>

        {{-- Total Bagi Hasil --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-7 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 font-semibold text-xs uppercase tracking-widest">Total Bagi Hasil</p>
                <div class="bg-emerald-50 rounded-2xl p-2">
                    <i class="fas fa-receipt text-emerald-500 text-lg"></i>
                </div>
            </div>
            <h2 class="text-3xl font-black text-emerald-500">{{ $riwayatPendapatan->count() }}</h2>
            <p class="text-slate-400 text-xs mt-2">Jumlah transaksi tercatat</p>
        </div>

        {{-- Pesanan Baru --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-7 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 font-semibold text-xs uppercase tracking-widest">Pesanan Baru</p>
                <div class="bg-rose-50 rounded-2xl p-2">
                    <i class="fas fa-bell text-rose-500 text-lg"></i>
                </div>
            </div>
            <h2 class="text-3xl font-black text-rose-500">{{ $pesananBaru }}</h2>
            <p class="text-slate-400 text-xs mt-2">Menunggu diproses</p>
        </div>

    </div>

    {{-- SHORTCUT --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
        <a href="{{ route('mitra.laporan.index') }}"
            class="flex items-center gap-4 bg-white border border-slate-200 rounded-2xl px-6 py-4 hover:border-blue-400 hover:shadow-md transition group">
            <div class="bg-blue-50 group-hover:bg-blue-100 rounded-xl p-3 transition">
                <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="font-bold text-slate-700">Laporan Transaksi</p>
                <p class="text-xs text-slate-400">Detail penjualan bulanan / mingguan</p>
            </div>
            <i class="fas fa-chevron-right ml-auto text-slate-300 group-hover:text-blue-400 transition"></i>
        </a>
    </div>

    {{-- TABEL RIWAYAT BAGI HASIL --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-700">
                <i class="fas fa-table mr-2 text-slate-400"></i>
                Riwayat Bagi Hasil
            </h3>
            <span class="text-xs text-slate-400 font-medium">{{ $riwayatPendapatan->count() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-slate-400 border-b border-slate-200 text-[10px] uppercase tracking-widest font-black">
                        <th class="p-5">Tanggal</th>
                        <th class="p-5 text-right">Total Omzet</th>
                        <th class="p-5 text-center">% Mitra</th>
                        <th class="p-5 text-right">Pendapatan Mitra</th>
                        <th class="p-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($riwayatPendapatan as $bagi)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-5 text-slate-500 font-medium">
                            {{ $bagi->created_at ? $bagi->created_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="p-5 text-right text-slate-600 font-semibold">
                            Rp {{ number_format($bagi->total_omzet ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="p-5 text-center text-slate-500">
                            {{ $bagi->persen_mitra ?? 0 }}%
                        </td>
                        <td class="p-5 text-right font-black text-emerald-600">
                            Rp {{ number_format($bagi->nominal_mitra ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="p-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ ($bagi->status ?? '') == 'SELESAI'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700' }}">
                                {{ $bagi->status ?? 'PENDING' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center text-slate-400">
                            <i class="fas fa-receipt text-4xl mb-3 block text-slate-200"></i>
                            <p>Belum ada data bagi hasil.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if($riwayatPendapatan->count() > 0)
                <tfoot>
                    <tr class="bg-slate-50 border-t-2 border-slate-200">
                        <td colspan="3" class="p-5 font-black text-slate-700 uppercase text-xs tracking-wider text-right">
                            Total Pendapatan Mitra
                        </td>
                        <td class="p-5 text-right font-black text-emerald-600 text-lg">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection
