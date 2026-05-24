<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mitra;
use App\Models\Bagihasil;
use App\Models\LaporanKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function pengajuan()
    {
        $pengajuans = User::with('mitra')
            ->where('role', 'mitra')
            ->where('status', 'pending')
            ->get();

        return view('admin.pengajuan', compact('pengajuans'));
    }

    public function approve($id)
    {
        $user = User::with('mitra')->findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->update(['status' => 'menunggu_kepala']);
            if ($user->mitra) {
                $user->mitra->update(['status' => 'menunggu_kepala']);
            }
        });

        activity('admin')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Menyetujui pendaftaran mitra: {$user->name}");

        // 1. KIRIM WA KE MITRA (Pemberitahuan lolos tahap 1)
        $no_hp = $user->mitra->no_hp ?? '';
        if ($no_hp) {
            $pesan = "Halo *{$user->name}*,\n\nBerkas pendaftaran Mitra BUMDes Anda telah lolos verifikasi tahap pertama oleh Admin. Saat ini berkas Anda sedang diteruskan dan menunggu persetujuan akhir dari *Kepala BUMDes*.\n\nMohon kesediaannya menunggu. Terima kasih.\n\n*Admin BUMDes Patimban*";
            $this->kirimWA($no_hp, $pesan);
        }

        // ✅ 2. KIRIM WA KE KEPALA BUMDES (Ambil no_hp dari tabel users)
        $kepalaBumdes = User::where('role', 'kepala-bumdes')->get();
        foreach ($kepalaBumdes as $kepala) {
            $kepala_no_hp = $kepala->no_hp ?? '';

            if ($kepala_no_hp) {
                $namaUsaha = $user->mitra->nama_usaha ?? '-';
                $jenisUsaha = $user->mitra->jenis_usaha ?? '-';

                $pesanKepala = "Halo Kepala BUMDes,\n\nAda pendaftaran Mitra baru yang telah *LOLOS VERIFIKASI ADMIN* dan memerlukan persetujuan serta pengesahan Anda:\n\nNama Pemilik: *{$user->name}*\nNama Usaha: *{$namaUsaha}*\nJenis Usaha: *{$jenisUsaha}*\n\nStatus berkas saat ini: *Menunggu Pengesahan Kepala BUMDes*.\nSilakan masuk ke Dashboard Kepala BUMDes untuk memeriksa data dan menandatangani sertifikat pengesahan resmi.\n\n*Sistem BUMDes Patimban*";

                $this->kirimWA($kepala_no_hp, $pesanKepala);
            }
        }

        return redirect()->route('admin.pengajuan')->with('success', 'Berkas valid dan diteruskan ke Kepala BUMDes!');
    }

    public function reject(Request $request, $id)
    {
        $user = User::with('mitra')->findOrFail($id);
        $alasan = $request->pesan_penolakan ?? 'Tidak disebutkan';
        $no_hp = $user->mitra->no_hp ?? '';
        $namaUser = $user->name;

        DB::transaction(function () use ($user) {
            if ($user->mitra) {
                if ($user->mitra->sku) Storage::disk('public')->delete($user->mitra->sku);
                $user->mitra->delete();
            }
            $user->update(['status' => 'rejected']);
        });

        activity('admin')
            ->causedBy(auth()->user())
            ->withProperties(['alasan' => $alasan])
            ->log("Menolak pendaftaran mitra: {$namaUser} — Alasan: {$alasan}");

        if ($no_hp) {
            $pesanWA = "Halo *{$namaUser}*,\n\nMohon maaf, pendaftaran Mitra BUMDes Anda *DITOLAK* oleh Admin.\n\n*Alasan:* {$alasan}\n\nData berkas Anda telah kami bersihkan. Anda dapat mencoba mendaftar kembali setelah 30 hari.\n\nTeria kasih.\n\n*Admin BUMDes Patimban*";
            $this->kirimWA($no_hp, $pesanWA);
        }

        return redirect()->route('admin.pengajuan')->with('success', 'Pengajuan ditolak. Detail berkas dihapus.');
    }

    public function dataMitra()
    {
        $mitras = Mitra::whereHas('user', function($q) {
                $q->where('status', 'aktif');
            })
            ->latest()
            ->get();

        return view('admin.data-mitra', compact('mitras'));
    }

    public function destroyMitra($id)
    {
        $user = User::with('mitra')->findOrFail($id);
        $namaUsaha = $user->mitra->nama_usaha ?? '-';
        $namaUser  = $user->name;

        DB::transaction(function () use ($user) {
            if ($user->mitra) {
                if ($user->mitra->sku) Storage::disk('public')->delete($user->mitra->sku);
                $user->mitra->delete();
            }
            $user->delete();
        });

        // ✅ Log hapus mitra
        activity('admin')
            ->causedBy(auth()->user())
            ->log("Menghapus data mitra: {$namaUsaha} ({$namaUser})");

        return redirect()->route('admin.mitra.index')->with('success', 'Data Mitra berhasil dihapus total.');
    }

    public function laporan()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $totalKasMasuk = Bagihasil::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where('status', 'SELESAI')
            ->sum('nominal_bumdes');

        $totalBagiHasil = Bagihasil::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->where('status', 'SELESAI')
                        ->sum('total_omzet');


        $totalMitra = Mitra::whereHas('user', fn($q) => $q->where('status', 'aktif'))->count();

        $bulanAktif = now()->translatedFormat('F Y');

        $grafikBulanan = Bagihasil::selectRaw('MONTH(tanggal) as bulan, SUM(total_omzet) as omzet, SUM(nominal_bumdes) as kas_bumdes')
            ->whereYear('tanggal', $tahunIni)
            ->where('status', 'SELESAI')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $namaBulan     = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $labelGrafik   = $grafikBulanan->map(fn($g) => $namaBulan[$g->bulan - 1]);
        $dataOmzet     = $grafikBulanan->pluck('omzet');
        $dataKasBumdes = $grafikBulanan->pluck('kas_bumdes');

        $perMitra = Bagihasil::where('status', 'SELESAI')
            ->get()
            ->groupBy('mitra_id')
            ->map(fn($group) => [
                'nama'  => optional(Mitra::where('user_id', $group->first()->mitra_id)->first())->nama_usaha ?? '-',
                'omzet' => $group->sum('total_omzet'),
                 'persen_bumdes'=> $group->first()->persen_bumdes,   // ← tambah ini
                'kas_bumdes'   => $group->sum('nominal_bumdes'),
                    ])->values();

        return view('admin.laporan', compact(
            'totalKasMasuk', 'totalBagiHasil', 'totalMitra',
            'bulanAktif', 'labelGrafik', 'dataOmzet', 'dataKasBumdes', 'perMitra'
        ));
    }

    public function histori()
    {
        $aktivitas = \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->paginate(20);

        return view('admin.histori', compact('aktivitas'));
    }

    private function kirimWA($no_hp, $pesan)
    {
        $token  = "obEnSgdDTVkALfwmMYTy";
        $target = preg_replace('/^0/', '62', $no_hp);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => [
                'target'      => $target,
                'message'     => $pesan,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER => ["Authorization: $token"],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }

    public function kirimLaporan(Request $request)
    {
        $request->validate([
            'bulan_aktif'    => 'required|string',
            'total_kas_masuk'=> 'required|numeric',
            'total_omzet'    => 'required|numeric',
            'total_mitra'    => 'required|integer',
            'catatan'        => 'nullable|string|max:500',
        ]);

        // Simpan ke tabel laporan_kas
        \App\Models\LaporanKas::create([
            'dikirim_oleh'   => auth()->id(),
            'bulan_aktif'    => $request->bulan_aktif,
            'total_kas_masuk'=> $request->total_kas_masuk,
            'total_omzet'    => $request->total_omzet,
            'total_mitra'    => $request->total_mitra,
            'catatan'        => $request->catatan,
            'status'         => 'terkirim',
            'dikirim_at'     => now(),
        ]);

        // Kirim notifikasi ke semua Kepala BUMDes
        $kepalaBumdes = \App\Models\User::where('role', 'kepala-bumdes')->get();
        foreach ($kepalaBumdes as $kepala) {
            $kepala->notify(new \App\Notifications\LaporanKasDikirim(
                $request->bulan_aktif,
                $request->total_kas_masuk,
                $request->catatan,
            ));
        }

        return redirect()->route('admin.laporan')
            ->with('laporan_terkirim', true);
    }

    public function laporanPdf()
{
    $bulanIni = now()->month;
    $tahunIni = now()->year;

    $totalKasMasuk = Bagihasil::whereMonth('tanggal', $bulanIni)
        ->whereYear('tanggal', $tahunIni)
        ->where('status', 'SELESAI')
        ->sum('nominal_bumdes');

    $totalBagiHasil = Bagihasil::whereMonth('tanggal', $bulanIni)
        ->whereYear('tanggal', $tahunIni)
        ->where('status', 'SELESAI')
        ->sum('total_omzet');

    $totalMitra = Mitra::whereHas('user', fn($q) => $q->where('status', 'aktif'))->count();
    $bulanAktif = now()->translatedFormat('F Y');

    $perMitra = Bagihasil::whereMonth('tanggal', $bulanIni)
        ->whereYear('tanggal', $tahunIni)
        ->where('status', 'SELESAI')
        ->get()
        ->groupBy('mitra_id')
        ->map(fn($group) => [
            'nama'          => optional(Mitra::where('user_id', $group->first()->mitra_id)->first())->nama_usaha ?? '-',
            'omzet'         => $group->sum('total_omzet'),
            'persen_bumdes' => $group->first()->persen_bumdes,
            'kas_bumdes'    => $group->sum('nominal_bumdes'),
        ])->values();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan_pdf', compact(
        'totalKasMasuk', 'totalBagiHasil', 'totalMitra', 'bulanAktif', 'perMitra'
    ))->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan_BagiHasil_' . now()->format('Y_m') . '.pdf');
}
}
