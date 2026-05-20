<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mitra;
use App\Mail\SertifikatMitraMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

class KepalaBumdesController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_mitra'      => User::where('role', 'mitra')->where('status', 'aktif')->count(),
            'pending_approval' => User::where('role', 'mitra')->where('status', 'menunggu_kepala')->count(),
        ];

        return view('kepala-bumdes.dashboard', compact('stats'));
    }

    public function pengajuan()
    {
        $pengajuans = User::with('mitra')
            ->where('role', 'mitra')
            ->where('status', 'menunggu_kepala')
            ->latest()
            ->get();

        return view('kepala-bumdes.pengajuan', compact('pengajuans'));
    }

    public function previewSurat($id)
    {
        $user = User::with('mitra')->findOrFail($id);

        return response()->json([
            'name'         => $user->name,
            'nama_usaha'   => $user->mitra->nama_usaha ?? '-',
            'jenis_usaha'  => $user->mitra->jenis_usaha ?? '-',
            'alamat_usaha' => $user->mitra->alamat_usaha ?? '-',
            'rt_rw'        => $user->mitra->rt_rw ?? '-',
            'no_hp'        => $user->mitra->no_hp ?? '-',
        ]);
    }

    public function approve(Request $request, $id)
    {
        $user = User::with('mitra')->findOrFail($id);

        try {
            DB::beginTransaction();

            $namaFile = 'Sertifikat-' . Str::slug($user->name) . '-' . strtoupper(Str::random(5)) . '.pdf';

            $user->update([
                'status'           => 'aktif',
                'surat_pengesahan' => $namaFile,
            ]);

            if ($user->mitra) {
                $user->mitra->update(['status' => 'aktif']);
            }

            // Generate QR Code — endroid/qr-code, tidak butuh Imagick
            $isiQR = "DISAHKAN OLEH: IQBAL NUR AFRIZAL\n" .
                     "Direktur Utama BUMDes Putra Samudra Patimban\n" .
                     "Mitra: " . $user->name;

            $qrCode  = new QrCode($isiQR);
            $writer  = new SvgWriter();
            $result  = $writer->write($qrCode);
            $qrBase64 = base64_encode($result->getString());

            $pdf = Pdf::loadView('pdf.sertifikat', [
                'user'     => $user,
                'qrCode'   => $qrBase64,
                'tanggal'  => now()->translatedFormat('d F Y'),
            ]);

            $pdfContent = $pdf->output();

            Mail::to($user->email)->send(
                new SertifikatMitraMail($user, $pdfContent, $namaFile)
            );

            DB::commit();

            return redirect()->route('kepala-bumdes.pengajuan')
                ->with('success', "Pendaftaran {$user->name} disahkan. Sertifikat dikirim ke email mitra.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses sertifikat: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $user = User::with('mitra')->findOrFail($id);

        DB::transaction(function () use ($user) {
            if ($user->mitra) {
                if ($user->mitra->ktp_path) Storage::disk('public')->delete($user->mitra->ktp_path);
                if ($user->mitra->sku_path) Storage::disk('public')->delete($user->mitra->sku_path);
                $user->mitra->delete();
            }
            $user->update(['status' => 'rejected']);
        });

        return redirect()->route('kepala-bumdes.pengajuan')
            ->with('success', 'Pengajuan telah ditolak.');
    }

 public function dataMitra()
{
    $mitras = Mitra::whereHas('user', function($q) {
            $q->where('status', 'aktif');
        })
        ->latest()
        ->get();

    return view('kepala-bumdes.data-mitra', compact('mitras'));
}

}
