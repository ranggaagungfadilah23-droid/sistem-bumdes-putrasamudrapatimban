<?php
namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\BagiHasil;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PendapatanController extends Controller
{
    private function getQuery(int $mitraId, string $periode)
    {
        $query = BagiHasil::where('mitra_id', $mitraId);

        if ($periode == 'mingguan') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } else {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        return $query->orderBy('created_at', 'asc');
    }

    public function index()
    {
        $mitra      = Auth::user()->mitra;
        $mitraId    = $mitra->user_id;
        $jenisUsaha = $mitra->jenis_usaha ?? null;

        $totalPendapatan = BagiHasil::where('mitra_id', $mitraId)
                            ->sum('nominal_mitra');

        $pesananBaru = Transaksi::where('mitra_id', $mitraId)
                        ->where('status_pengiriman', 'baru')
                        ->count();

        $riwayatPendapatan = BagiHasil::where('mitra_id', $mitraId)
                                ->latest()
                                ->get();

        return view('mitra.pendapatan.index', compact(
            'totalPendapatan',
            'pesananBaru',
            'riwayatPendapatan',
            'jenisUsaha'
        ));
    }

    public function laporan(Request $request)
    {
        $mitra      = Auth::user()->mitra;
        $mitraId    = $mitra->user_id;
        $periode    = $request->get('periode', 'bulanan');

        $dataLaporan     = $this->getQuery($mitraId, $periode)->get();
        $totalOmzet      = $dataLaporan->sum('total_omzet');
        $totalPendapatan = $dataLaporan->sum('nominal_mitra');
        $totalItem       = $dataLaporan->count();

        return view('mitra.pendapatan.laporan', compact(
            'dataLaporan', 'totalOmzet', 'totalPendapatan', 'totalItem', 'periode', 'mitra'
        ));
    }

    public function laporanPdf(Request $request)
    {
        $mitra   = Auth::user()->mitra;
        $mitraId = $mitra->user_id;
        $periode = $request->get('periode', 'bulanan');

        $dataLaporan     = $this->getQuery($mitraId, $periode)->get();
        $totalOmzet      = $dataLaporan->sum('total_omzet');
        $totalPendapatan = $dataLaporan->sum('nominal_mitra');

        $pdf = Pdf::loadView('mitra.pendapatan.pdf', [
            'dataLaporan'     => $dataLaporan,
            'totalOmzet'      => $totalOmzet,
            'totalPendapatan' => $totalPendapatan,
            'periode'         => $periode,
            'mitra'           => $mitra,
        ]);

        return $pdf->stream('Laporan_Pendapatan_' . $periode . '.pdf');
    }
}
