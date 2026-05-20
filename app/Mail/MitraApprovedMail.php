<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MitraApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $pdfPath;

    public function __construct($user, $pdfPath)
    {
        $this->user = $user;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        $namaUsaha = $this->user->mitra->nama_usaha ?? '-';

        return $this->subject('Selamat! Pendaftaran Mitra BUMDes Disetujui')
            ->html("
                <h3>Halo, {$this->user->name}!</h3>
                <p>Kabar gembira! Pendaftaran untuk usaha <strong>{$namaUsaha}</strong> telah <strong>DISETUJUI</strong> oleh Kepala BUMDes Putra Samudra Patimban.</p>
                <p>Bersama email ini, kami lampirkan <strong>Surat Pengesahan Mitra</strong> sebagai bukti resmi kemitraan Anda.</p>
                <p>Silakan login ke dashboard untuk mulai mengelola usaha Anda.</p>
                <br>
                <p>Salam hangat,<br><strong>Admin BUMDes Patimban</strong></p>
            ")
            ->attach(storage_path('app/public/' . $this->pdfPath), [
                'as' => 'Surat_Pengesahan_Mitra.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
