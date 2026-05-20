<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SertifikatMitraMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $pdfContent;
    public $namaFile;

    public function __construct($user, $pdfContent, $namaFile)
    {
        $this->user       = $user;
        $this->pdfContent = $pdfContent;
        $this->namaFile   = $namaFile;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sertifikat Mitra BUMDes Patimban',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi_disetujui',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                $this->namaFile
            )->withMime('application/pdf'),
        ];
    }
}
