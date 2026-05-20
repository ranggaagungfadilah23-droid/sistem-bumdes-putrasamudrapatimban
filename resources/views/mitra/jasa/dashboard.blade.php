@extends('theme.default')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Dashboard Jasa - {{ Auth::user()->mitra->nama_usaha }}</h1>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-sm border">
            <p class="text-xs font-bold text-slate-500 uppercase">Pesanan Baru</p>
            <h3 class="text-2xl font-extrabold">{{ $pesananBaru }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border">
            <p class="text-xs font-bold text-slate-500 uppercase">Pendapatan Jasa</p>
            <h3 class="text-2xl font-extrabold">Rp {{ number_format($totalPendapatanBersih, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border">
            <p class="text-xs font-bold text-slate-500 uppercase">Selesai</p>
            <h3 class="text-2xl font-extrabold">{{ $pesananSelesai }}</h3>
        </div>
    </div>

  
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border shadow-sm">
            <h3 class="font-bold mb-4">Tren Pendapatan Jasa</h3>
            <canvas id="revenueChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-3xl border shadow-sm">
            <h3 class="font-bold mb-4">Status Pesanan Jasa</h3>
            <canvas id="orderChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data Pendapatan
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($pendapatanBulanan->pluck('bulan')) !!},
            datasets: [{ label: 'Pendapatan (Rp)', data: {!! json_encode($pendapatanBulanan->pluck('total')) !!}, borderColor: '#3b82f6' }]
        }
    });

    // Data Status
    new Chart(document.getElementById('orderChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusPesanan->keys()) !!},
            datasets: [{ data: {!! json_encode($statusPesanan->values()) !!}, backgroundColor: ['#10b981', '#f59e0b', '#ef4444'] }]
        }
    });
</script>
@endsection
