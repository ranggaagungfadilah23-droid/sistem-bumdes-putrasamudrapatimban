@extends('theme.default')
@section('title', 'Laporan Rekapitulasi')

@section('content')
<div class="container-fluid px-6 py-8">

    {{-- HEADER & TOMBOL CETAK --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Laporan Rekapitulasi</h1>
            <p class="text-slate-500 text-sm mt-1">Preview data penjualan sebelum diekspor ke format PDF formal.</p>
        </div>
        <a href="{{ route('mitra.laporan.pdf', ['periode' => $periode]) }}" target="_blank"
            class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black shadow-xl shadow-blue-600/30 transition hover:-translate-y-1">
            <i class="fas fa-file-pdf text-xl"></i>
            <span>CETAK LAPORAN PDF</span>
        </a>
    </div>

    {{-- FILTER & RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-center">
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Pilih Periode Laporan</label>
            <form action="{{ route('mitra.laporan.index') }}" method="GET">
                <select name="periode" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 outline-none focus:border-blue-500 cursor-pointer">
                    <option value="bulanan"  {{ $periode == 'bulanan'  ? 'selected' : '' }}>Bulan Ini ({{ date('F Y') }})</option>
                    <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Minggu Ini (7 Hari Terakhir)</option>
                </select>
            </form>
        </div>

        <div class="bg-emerald-50 rounded-3xl p-6 border border-emerald-100">
            <p class="text-emerald-600 font-bold text-xs uppercase tracking-wider mb-1">
                Total Omzet Penjualan ({{ ucfirst($periode) }})
            </p>
            <h3 class="text-3xl font-black text-emerald-700">
                Rp {{ number_format($totalOmzet, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-blue-50 rounded-3xl p-6 border border-blue-100">
            <p class="text-blue-600 font-bold text-xs uppercase tracking-wider mb-1">Total Transaksi</p>
            <h3 class="text-3xl font-black text-blue-700">{{ $totalTransaksi }} Transaksi</h3>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3 rounded-2xl flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3 rounded-2xl flex items-center gap-2">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- TOMBOL KIRIM KE ADMIN --}}
  <form action="{{ route('mitra.laporan.kirim') }}" method="POST">
    @csrf
    <input type="hidden" name="periode" value="{{ $periode }}">

    <button type="submit" class="w-full bg-emerald-600 text-white rounded-lg px-4 py-3 font-semibold shadow-md hover:bg-emerald-700 transition">
        <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan ke Admin
    </button>
</form>

    {{-- TABEL LAPORAN --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 bg-slate-50/80 border-b border-slate-200">
            <h3 class="font-bold text-slate-700">
                <i class="fas fa-table mr-2 text-slate-400"></i>
                Detail Transaksi
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-slate-400 border-b border-slate-200 text-[10px] uppercase tracking-widest font-black">
                        <th class="p-5">Tanggal</th>
                        <th class="p-5">No. Invoice</th>
                        <th class="p-5">Nama Item</th>
                        <th class="p-5 text-center">Qty</th>
                        <th class="p-5 text-right">Total</th>
                        <th class="p-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($transaksi as $item)
                    <tr class="hover:bg-slate-50 transition">

                        {{-- Tanggal --}}
                        <td class="p-5 text-slate-500 font-medium">
                            {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                        </td>

                        {{-- ✅ Invoice Number dari tabel transaksi --}}
                        <td class="p-5 font-bold text-blue-600">
                            #{{ $item->invoice_number ?? 'N/A' }}
                        </td>

                        {{-- ✅ Nama item dari relasi produk atau jasa --}}
                        <td class="p-5 font-semibold text-slate-700">
                            @if($jenisUsaha == 'Jasa')
                                {{ $item->jasa->nama_jasa ?? '-' }}
                            @else
                                {{ $item->produk->nama_produk ?? '-' }}
                            @endif
                        </td>

                        {{-- ✅ Jumlah dari tabel transaksi --}}
                        <td class="p-5 text-center text-slate-500">
                            {{ $item->jumlah ?? 1 }}
                        </td>

                        {{-- ✅ Total dari tabel transaksi --}}
                        <td class="p-5 text-right font-black text-slate-700">
                            Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}
                        </td>

                        {{-- ✅ Status pembayaran --}}
                        <td class="p-5 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $item->status_pembayaran == 'Lunas'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700' }}">
                                {{ $item->status_pembayaran ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-16 text-center text-slate-400">
                            <i class="fas fa-folder-open text-4xl mb-3 block text-slate-200"></i>
                            <p>Tidak ada transaksi pada periode <b>{{ ucfirst($periode) }}</b> ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                @if($transaksi->count() > 0)
                <tfoot>
                    <tr class="bg-slate-50 border-t-2 border-slate-200">
                        <td colspan="4" class="p-5 font-black text-slate-700 uppercase text-xs tracking-wider text-right">
                            Total Omzet Periode {{ ucfirst($periode) }}
                        </td>
                        <td class="p-5 text-right font-black text-emerald-700 text-lg">
                            Rp {{ number_format($totalOmzet, 0, ',', '.') }}
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
