@extends('theme.default')

@section('content')
<div class="p-8 space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Laporan Keuangan BUMDes</h1>
        <p class="text-slate-400 text-sm mt-1">Rekapitulasi pendapatan dan bagi hasil — {{ $bulanAktif }}</p>
    </div>

    {{-- KARTU RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-2xl shadow-lg shadow-blue-500/20">
            <div class="flex items-center justify-between mb-4">
                <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">Kas Masuk BUMDes</p>
                <div class="bg-white/20 p-2 rounded-xl"><i class="fas fa-university text-white"></i></div>
            </div>
            <h3 class="text-3xl font-black font-mono">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</h3>
            <p class="text-blue-200 text-xs mt-2">Dari bagi hasil yang sudah selesai</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Omzet Mitra</p>
                <div class="bg-emerald-50 p-2 rounded-xl"><i class="fas fa-chart-line text-emerald-500"></i></div>
            </div>
            <h3 class="text-3xl font-black font-mono text-slate-800">Rp {{ number_format($totalBagiHasil, 0, ',', '.') }}</h3>
            <p class="text-slate-400 text-xs mt-2">Bulan {{ $bulanAktif }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Mitra Aktif</p>
                <div class="bg-amber-50 p-2 rounded-xl"><i class="fas fa-store text-amber-500"></i></div>
            </div>
            <h3 class="text-3xl font-black font-mono text-slate-800">{{ $totalMitra }}</h3>
            <p class="text-slate-400 text-xs mt-2">Terdaftar & aktif</p>
        </div>
    </div>

    @if($totalKasMasuk == 0 && $totalBagiHasil == 0)
    {{-- EMPTY STATE --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 text-center py-20">
        <i class="fas fa-chart-line text-5xl text-slate-200 mb-4 block"></i>
        <h3 class="text-lg font-bold text-slate-800">Grafik Laporan Belum Tersedia</h3>
        <p class="text-slate-400 text-sm">Belum ada bagi hasil yang selesai dikonfirmasi.</p>
    </div>
    @else

    {{-- GRAFIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Grafik Line: Tren Bulanan --}}
        <div class="md:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-base font-bold text-slate-700 mb-1">Tren Omzet & Kas BUMDes</h3>
            <p class="text-slate-400 text-xs mb-6">Perbandingan omzet mitra vs kas masuk BUMDes per bulan</p>
            <canvas id="grafikBulanan" height="120"></canvas>
        </div>

        {{-- Grafik Donut: Per Mitra --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-base font-bold text-slate-700 mb-1">Kontribusi per Mitra</h3>
            <p class="text-slate-400 text-xs mb-6">Berdasarkan total omzet keseluruhan</p>
            <canvas id="grafikMitra"></canvas>
            {{-- Legend --}}
            <div class="mt-4 space-y-2">
                @foreach($perMitra as $i => $pm)
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full inline-block" style="background: {{ ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'][$i % 5] }}"></span>
                        <span class="text-slate-600 font-medium">{{ $pm['nama'] }}</span>
                    </div>
                    <span class="font-bold text-slate-700">Rp {{ number_format($pm['omzet'], 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($totalKasMasuk > 0 || $totalBagiHasil > 0)

    // Grafik Line Bulanan
    new Chart(document.getElementById('grafikBulanan'), {
        type: 'line',
        data: {
            labels: {!! json_encode($labelGrafik) !!},
            datasets: [
                {
                    label: 'Total Omzet Mitra',
                    data: {!! json_encode($dataOmzet) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 5,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Kas Masuk BUMDes',
                    data: {!! json_encode($dataKasBumdes) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 5,
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top', labels: { font: { weight: 'bold' } } },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Grafik Donut Per Mitra
    new Chart(document.getElementById('grafikMitra'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($perMitra->pluck('nama')) !!},
            datasets: [{
                data: {!! json_encode($perMitra->pluck('omzet')) !!},
                backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                    }
                }
            }
        }
    });

    @endif
</script>

@endsection
