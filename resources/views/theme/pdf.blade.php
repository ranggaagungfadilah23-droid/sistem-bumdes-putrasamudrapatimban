<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan BUMDes - {{ $bulanAktif }}</title>
    <style>
        @page { size: A4; margin: 0; }
        body { font-family: "Times New Roman", Times, serif; font-size: 13px; line-height: 1.8; margin: 0; padding: 35px 45px; color: #000; }
        .border-double { border: 4px double #000; padding: 25px 30px; min-height: 92%; }

        /* KOP */
        .kop-surat { display: table; width: 100%; padding-bottom: 12px; margin-bottom: 5px; }
        .kop-logo { display: table-cell; vertical-align: middle; width: 95px; }
        .kop-logo img { width: 85px; height: 85px; }
        .kop-teks { display: table-cell; vertical-align: middle; text-align: center; padding-right: 95px; }
        .kop-teks .instansi { font-size: 13px; font-weight: normal; text-transform: uppercase; margin: 0; letter-spacing: 1px; }
        .kop-teks .nama-bumdes { font-size: 22px; font-weight: bold; text-transform: uppercase; margin: 2px 0; letter-spacing: 1px; }
        .kop-teks .alamat { font-size: 11px; margin: 3px 0 0 0; line-height: 1.5; }
        .garis-kop { border: none; border-top: 4px solid #000; margin: 0 0 2px 0; }
        .garis-kop-tipis { border: none; border-top: 1px solid #000; margin: 0 0 20px 0; }

        /* KONTEN */
        .judul { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 3px; text-decoration: underline; letter-spacing: 2px; text-transform: uppercase; }
        .sub-judul { text-align: center; font-size: 12px; margin-bottom: 20px; color: #444; }
        .info-table td { padding: 2px 6px; font-size: 12px; }
        .info-table td:first-child { width: 140px; color: #555; }
        .ringkasan { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .ringkasan td { border: 1px solid #cbd5e1; padding: 10px 14px; width: 50%; }
        .ringkasan .label { font-size: 9px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }
        .ringkasan .nilai { font-size: 14px; font-weight: bold; margin-top: 4px; }
        table.data { width: 100%; border-collapse: collapse; margin: 16px 0; }
        table.data thead tr { background: #1e3a5f; color: white; }
        table.data thead th { padding: 8px 10px; font-size: 10px; text-transform: uppercase; text-align: left; }
        table.data thead th.right { text-align: right; }
        table.data tbody tr:nth-child(even) { background: #f8fafc; }
        table.data tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        table.data tbody td.right { text-align: right; }
        table.data tfoot td { padding: 8px 10px; font-weight: bold; background: #f1f5f9; border-top: 2px solid #1e3a5f; font-size: 11px; }
        table.data tfoot td.right { text-align: right; color: #1d4ed8; }
        .ttd-ruang { height: 55px; }
        .footer-note { font-size: 9px; font-style: italic; color: #555; margin-top: 30px; text-align: center; border-top: 1px solid #ccc; padding-top: 8px; }
    </style>
</head>
<body>
<div class="border-double">

    @include('theme.partials.kop-surat')

    <div class="judul">Laporan Keuangan Bagi Hasil Mitra</div>
    <div class="sub-judul">Periode: {{ $bulanAktif }}</div>

    <table class="info-table" style="margin-bottom:14px;">
        <tr><td>Tanggal Cetak</td><td>:</td><td>{{ now()->translatedFormat('d F Y') }}</td></tr>
        <tr><td>Dicetak Oleh</td><td>:</td><td>{{ auth()->user()->name }} (Admin)</td></tr>
        <tr><td>Jumlah Mitra Aktif</td><td>:</td><td>{{ $totalMitra }} Mitra</td></tr>
    </table>

    <table class="ringkasan">
        <tr>
            <td>
                <div class="label">Total Omzet Mitra</div>
                <div class="nilai">Rp {{ number_format($totalBagiHasil, 0, ',', '.') }}</div>
            </td>
            <td style="background:#eff6ff;">
                <div class="label" style="color:#3b82f6;">Kas Masuk BUMDes</div>
                <div class="nilai" style="color:#1d4ed8;">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mitra</th>
                <th class="right">Total Omzet</th>
                <th class="right">% BUMDes</th>
                <th class="right">Kas Masuk BUMDes</th>
                <th class="right">Bagian Mitra</th>
                <th style="text-align:center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perMitra as $i => $pm)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $pm['nama'] }}</td>
                <td class="right">Rp {{ number_format($pm['omzet'], 0, ',', '.') }}</td>
                <td class="right">{{ $pm['persen_bumdes'] ?? '-' }}%</td>
                <td class="right">Rp {{ number_format($pm['kas_bumdes'] ?? 0, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($pm['omzet'] - ($pm['kas_bumdes'] ?? 0), 0, ',', '.') }}</td>
                <td style="text-align:center">Selesai</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="right">Rp {{ number_format($totalBagiHasil, 0, ',', '.') }}</td>
                <td></td>
                <td class="right">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($totalBagiHasil - $totalKasMasuk, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <table style="margin-top:30px; width:100%;">
        <tr>
            <td width="55%">&nbsp;</td>
            <td width="45%" style="text-align:center;">
                <p style="margin:0; font-size:13px;">Patimban, {{ now()->translatedFormat('d F Y') }}<br>Kepala BUMDes Putra Samudra Patimban,</p>
                <div class="ttd-ruang"></div>
                <p style="margin:0; font-size:13px;"><strong><u>IQBAL NUR AFRIZAL</u></strong><br>Kepala BUMDes</p>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara digital oleh Sistem Informasi BUMDes Patimban &bull;
        Diterbitkan pada {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</div>
</body>
</html>
