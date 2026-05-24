<?php

namespace App\Http\Controllers\KepalaBumdes;

use App\Http\Controllers\Controller;
use App\Models\BagiHasil;
use Illuminate\Http\Request;

class MonitoringKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $bulanAktif = (int) $request->get('bulan', date('n'));
        $tahunAktif = (int) $request->get('tahun', date('Y'));

        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April',   5 => 'Mei',       6 => 'Juni',
            7 => 'Juli',    8 => 'Agustus',   9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $namaBulan = $namaBulanList[$bulanAktif];

        $baseQuery = fn() => BagiHasil::whereYear('created_at', $tahunAktif)
            ->whereMonth('created_at', $bulanAktif)
            ->where('status', 'selesai');

        // PEMASUKAN = nominal_bumdes
        $dataPemasukan = $baseQuery()
            ->with('mitra:id,name')
            ->orderBy('created_at')
            ->get()
            ->map(fn($row) => (object) [
                'tanggal'    => $row->created_at,
                'keterangan' => 'Bagi Hasil — ' . ($row->mitra->name ?? 'Mitra #' . $row->mitra_id),
                'sumber'     => 'Bagi Hasil Mitra',
                'jumlah'     => (float) $row->nominal_bumdes,
            ]);

        $totalPemasukan       = $dataPemasukan->sum('jumlah');
        $jumlahTransaksiMasuk = $dataPemasukan->count();

        // PENGELUARAN — kosong dulu, uncomment jika ada tabel pengeluaran
        // $dataPengeluaran = PengeluaranOperasional::whereYear('tanggal', $tahunAktif)
        //     ->whereMonth('tanggal', $bulanAktif)->orderBy('tanggal')->get();
        $dataPengeluaran       = collect();
        $totalPengeluaran      = 0;
        $jumlahTransaksiKeluar = 0;

        // Saldo awal = akumulasi kas masuk sebelum bulan ini
        $saldoAwal = BagiHasil::whereYear('created_at', $tahunAktif)
            ->whereMonth('created_at', '<', $bulanAktif)
            ->where('status', 'selesai')
            ->sum('nominal_bumdes');

        $saldoAkhir          = $saldoAwal + $totalPemasukan - $totalPengeluaran;
        $kategoriPengeluaran = collect();

        // Grafik harian
        $jumlahHari  = cal_days_in_month(CAL_GREGORIAN, $bulanAktif, $tahunAktif);
        $labelHarian = collect(range(1, $jumlahHari))->values();

        $pemasukanHarian = $baseQuery()
            ->selectRaw('DAY(created_at) as hari, SUM(nominal_bumdes) as total')
            ->groupBy('hari')
            ->get()
            ->keyBy('hari');

        $dataMasukHarian  = collect(range(1, $jumlahHari))
            ->map(fn($d) => (float) ($pemasukanHarian->get($d)->total ?? 0))
            ->values();

        $dataKeluarHarian = collect(array_fill(0, $jumlahHari, 0))->values();

        return view('kepala-bumdes.monitoring-keuangan', compact(
            'bulanAktif', 'tahunAktif', 'namaBulan',
            'saldoAwal', 'saldoAkhir',
            'totalPemasukan', 'totalPengeluaran',
            'jumlahTransaksiMasuk', 'jumlahTransaksiKeluar',
            'dataPemasukan', 'dataPengeluaran', 'kategoriPengeluaran',
            'labelHarian', 'dataMasukHarian', 'dataKeluarHarian',
        ));
    }

    public function export(Request $request)
{
    $bulan = $request->get('bulan', date('n'));
    $tahun = $request->get('tahun', date('Y'));

    $data = BagiHasil::whereYear('created_at', $tahun)
        ->whereMonth('created_at', $bulan)
        ->where('status', 'selesai')
        ->with('mitra:id,name')
        ->get();

    $filename = "monitoring-keuangan-{$tahun}-{$bulan}.csv";

    $headers = ['Content-Type' => 'text/csv'];

    $callback = function () use ($data) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Tanggal', 'Mitra', 'Nominal BUMDes']);
        foreach ($data as $row) {
            fputcsv($file, [
                $row->created_at->format('d/m/Y'),
                $row->mitra->name ?? '-',
                $row->nominal_bumdes,
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers + [
        'Content-Disposition' => "attachment; filename={$filename}"
    ]);
}
}
