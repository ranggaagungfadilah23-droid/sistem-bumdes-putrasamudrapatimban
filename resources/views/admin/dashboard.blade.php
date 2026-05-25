@extends('theme.default')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Halo, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-slate-400 text-sm mt-1">Pantau performa BUMDes Patimban hari ini.</p>
        </div>
        <div class="text-sm text-slate-400 bg-white px-4 py-2 rounded-lg border border-slate-100 shadow-sm">
            <i class="fas fa-calendar-alt mr-2"></i> {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    @php
        $mitraAktif   = \App\Models\Mitra::whereHas('user', fn($q)=>$q->where('status','aktif'))->count();
        $pengajuan    = \App\Models\User::where('role','mitra')->where('status','pending')->count();
        $omzetBulan   = \App\Models\BagiHasil::whereMonth('tanggal',now()->month)->whereYear('tanggal',now()->year)->sum('total_omzet');
        $kasBulan     = \App\Models\BagiHasil::whereMonth('tanggal',now()->month)->whereYear('tanggal',now()->year)->where('status','SELESAI')->sum('nominal_bumdes');
        $bhSelesai    = \App\Models\BagiHasil::whereMonth('tanggal',now()->month)->whereYear('tanggal',now()->year)->where('status','SELESAI')->count();
        $bhPending    = \App\Models\BagiHasil::whereMonth('tanggal',now()->month)->whereYear('tanggal',now()->year)->where('status','PENDING')->count();
    @endphp

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <a href="{{ route('admin.mitra.index') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center mb-3"><i class="fas fa-store text-blue-600"></i></div>
            <p class="text-xs text-slate-400 mb-1">Mitra Aktif</p>
            <p class="text-2xl font-bold text-slate-800">{{ $mitraAktif }}</p>
        </a>
        <a href="{{ route('admin.pengajuan') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center mb-3"><i class="fas fa-clock text-amber-500"></i></div>
            <p class="text-xs text-slate-400 mb-1">Pengajuan Mitra</p>
            <p class="text-2xl font-bold text-slate-800">{{ $pengajuan }}</p>
        </a>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center mb-3"><i class="fas fa-chart-line text-emerald-600"></i></div>
            <p class="text-xs text-slate-400 mb-1">Total Omzet</p>
            <p class="text-lg font-bold text-slate-800">Rp {{ number_format($omzetBulan,0,',','.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center mb-3"><i class="fas fa-university text-teal-600"></i></div>
            <p class="text-xs text-slate-400 mb-1">Kas BUMDes</p>
            <p class="text-lg font-bold text-slate-800">Rp {{ number_format($kasBulan,0,',','.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-3"><i class="fas fa-check-circle text-green-600"></i></div>
            <p class="text-xs text-slate-400 mb-1">Bagi Hasil Terkonfirmasi</p>
            <p class="text-2xl font-bold text-slate-800">{{ $bhSelesai }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center mb-3"><i class="fas fa-exclamation-circle text-rose-500"></i></div>
            <p class="text-xs text-slate-400 mb-1">Bagi Hasil Pending</p>
            <p class="text-2xl font-bold text-slate-800">{{ $bhPending }}</p>
        </div>
    </div>

    @php
        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $tren = \App\Models\Bagihasil::selectRaw('MONTH(tanggal) as bln, SUM(total_omzet) as omzet, SUM(nominal_bumdes) as kas')
            ->whereYear('tanggal', now()->year)->where('status','SELESAI')
            ->groupBy('bln')->orderBy('bln')->get();

        $mitraChart = \App\Models\Bagihasil::whereMonth('tanggal',now()->month)
            ->whereYear('tanggal',now()->year)->where('status','SELESAI')->get()
            ->groupBy('mitra_id')
            ->map(fn($g)=>['nama'=>optional(\App\Models\Mitra::where('user_id',$g->first()->mitra_id)->first())->nama_usaha??'-','omzet'=>$g->sum('total_omzet')])
            ->values();

        $recents = \App\Models\Bagihasil::latest()->take(5)->get();
    @endphp

    {{-- GRAFIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="md:col-span-2 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="font-bold text-slate-700 mb-0.5">Tren omzet & kas BUMDes</p>
            <p class="text-xs text-slate-400 mb-4">Tahun {{ now()->year }}</p>
            <canvas id="lineChart" height="120"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="font-bold text-slate-700 mb-0.5">Kontribusi per mitra</p>
            <p class="text-xs text-slate-400 mb-4">{{ now()->translatedFormat('F Y') }}</p>
            <canvas id="donutChart" height="180"></canvas>
            <div id="donutLegend" class="mt-3 space-y-1.5"></div>
        </div>
    </div>

    {{-- BAWAH --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="font-bold text-slate-700 mb-4">Omzet per mitra — bulan ini</p>
            <div id="barRows"></div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <p class="font-bold text-slate-700">Bagi hasil terbaru</p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-slate-50"><tr>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-400 uppercase">Mitra</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-400 uppercase">Omzet</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-400 uppercase">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recents as $bh)
                    @php $m = \App\Models\Mitra::where('user_id',$bh->mitra_id)->first(); @endphp
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-5 py-3 font-medium text-slate-700 max-w-[120px] truncate">{{ $m->nama_usaha ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-mono text-slate-600 text-xs">Rp {{ number_format($bh->total_omzet,0,',','.') }}</td>
                        <td class="px-5 py-3 text-center">
                            @if($bh->status=='SELESAI')
                                <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Selesai</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-5 py-8 text-center text-slate-400 text-xs">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const palette = ['#185FA5','#0F6E56','#854F0B','#993556','#3B6D11','#A32D2D'];
const fmt = v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
const fmtC = v => 'Rp ' + new Intl.NumberFormat('id-ID',{notation:'compact'}).format(v);

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($tren->map(fn($t)=>$namaBulan[$t->bln-1])) !!},
        datasets: [
            { label: 'Omzet Mitra', data: {!! json_encode($tren->pluck('omzet')) !!}, borderColor:'#185FA5', backgroundColor:'rgba(24,95,165,0.07)', borderWidth:2, pointRadius:4, tension:0.4, fill:true },
            { label: 'Kas BUMDes', data: {!! json_encode($tren->pluck('kas')) !!}, borderColor:'#0F6E56', backgroundColor:'rgba(15,110,86,0.07)', borderWidth:2, pointRadius:4, tension:0.4, fill:true }
        ]
    },
    options: { responsive:true, plugins: { legend:{position:'top',labels:{font:{size:11},boxWidth:12}}, tooltip:{callbacks:{label:c=>fmt(c.raw)}} },
        scales: { y:{ticks:{callback:fmtC,font:{size:10}},grid:{color:'rgba(0,0,0,0.04)'}}, x:{ticks:{font:{size:10}},grid:{display:false}} } }
});

const mc = {!! json_encode($mitraChart) !!};
if (mc.length) {
    new Chart(document.getElementById('donutChart'), {
        type:'doughnut',
        data:{ labels:mc.map(m=>m.nama), datasets:[{data:mc.map(m=>m.omzet),backgroundColor:palette,borderWidth:0,hoverOffset:4}] },
        options:{ responsive:true, cutout:'68%', plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>fmt(c.raw)}}} }
    });
    const leg = document.getElementById('donutLegend');
    mc.forEach((m,i)=> leg.innerHTML += `<div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748b;margin-bottom:4px;">
        <span style="width:8px;height:8px;border-radius:50%;background:${palette[i%palette.length]};flex-shrink:0;"></span>
        <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${m.nama}</span>
        <span style="font-weight:500;color:#1e293b;">${fmtC(m.omzet)}</span></div>`);

    const maxO = Math.max(...mc.map(m=>m.omzet),1);
    const br = document.getElementById('barRows');
    mc.forEach((m,i)=> br.innerHTML += `<div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
        <div style="font-size:12px;color:#64748b;width:90px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${m.nama}</div>
        <div style="flex:1;height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden;"><div style="height:100%;width:${Math.round(m.omzet/maxO*100)}%;background:${palette[i%palette.length]};border-radius:4px;"></div></div>
        <div style="font-size:11px;color:#64748b;width:60px;text-align:right;">${fmtC(m.omzet)}</div></div>`);
}
</script>
@endsection
