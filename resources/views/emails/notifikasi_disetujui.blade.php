<!DOCTYPE html>
<html>
<head>
    <title>Akun Aktif - BUMDes Patimban</title>
</head>
<body style="font-family: sans-serif; line-height: 1.5; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #10b981;">Selamat! Akun Mitra Anda Telah Aktif</h2>
        <p>Halo, <strong>{{ $user->name }}</strong>.</p>
        <p>Dengan senang hati kami menginformasikan bahwa pendaftaran mitra Anda di <strong>BUMDes Putra Samudra Patimban</strong> telah disetujui dan disahkan oleh Kepala BUMDes.</p>

        <p>Bersama email ini, kami lampirkan <strong>Surat Pengesahan Mitra</strong> dalam format PDF sebagai bukti resmi keanggotaan Anda.</p>

        <div style="background-color: #f9fafb; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px;"><strong>Detail Usaha:</strong></p>
            <ul style="font-size: 14px; margin-top: 5px;">
                <li>Nama Usaha: {{ $user->mitra->nama_usaha ?? '-' }}</li>
                <li>Status: Aktif</li>
            </ul>
        </div>

        <p>Silakan simpan sertifikat tersebut dengan baik. Jika ada pertanyaan, hubungi kami melalui WhatsApp Official BUMDes Patimban.</p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #999; text-align: center;">
            &copy; {{ date('Y') }} BUMDes Putra Samudra Patimban. <br>
            Jl. Raya Patimban No. 1, Subang, Jawa Barat.
        </p>
    </div>
</body>
</html>
