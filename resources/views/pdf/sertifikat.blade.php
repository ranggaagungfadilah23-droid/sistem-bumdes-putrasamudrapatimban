<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Mitra - {{ $user->mitra->nama_usaha ?? $user->name }}</title>
    <style>
        @page { size: A4; margin: 0; }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 13px;
            line-height: 1.8;
            margin: 0;
            padding: 35px 45px;
            color: #000;
        }

        .border-double {
            border: 4px double #000;
            padding: 25px 30px;
            min-height: 92%;
        }

        .kop-surat {
            display: table;
            width: 100%;
            padding-bottom: 12px;
            margin-bottom: 5px;
        }

        .kop-logo {
            display: table-cell;
            vertical-align: middle;
            width: 95px;
        }

        .kop-logo img {
            width: 85px;
            height: 85px;
        }

        .kop-teks {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-right: 95px;
        }

        .kop-teks .instansi {
            font-size: 13px;
            font-weight: normal;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
        }

        .kop-teks .nama-bumdes {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
            letter-spacing: 1px;
        }

        .kop-teks .alamat {
            font-size: 11px;
            margin: 3px 0 0 0;
            line-height: 1.5;
        }

        .garis-kop {
            border: none;
            border-top: 4px solid #000;
            margin: 0 0 2px 0;
        }

        .garis-kop-tipis {
            border: none;
            border-top: 1px solid #000;
            margin: 0 0 25px 0;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 3px;
            text-decoration: underline;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .nomor-surat {
            text-align: center;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .isi-surat {
            text-align: justify;
            margin: 0 0 15px 0;
            text-indent: 40px;
        }

        .tabel-data {
            width: 90%;
            margin: 15px auto 20px auto;
            border-collapse: collapse;
        }

        .tabel-data td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 13px;
        }

        .ttd-container {
            margin-top: 40px;
            width: 100%;
        }

        .footer-note {
            font-size: 9px;
            font-style: italic;
            color: #555;
            margin-top: 30px;
            text-align: center;
            clear: both;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
    </style>
</head>
<body>
<div class="border-double">

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <div class="kop-logo">
            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents($logo)) }}" alt="Logo BUMDes">
        </div>
        <div class="kop-teks">
            <p class="instansi">Badan Usaha Milik Desa</p>
            <p class="nama-bumdes">BUMDes Putra Samudra Patimban</p>
            <p class="alamat">
                Sekretariat: Jl. PUK No.114, Desa Patimban, Kec. Pusakanagara, Kab. Subang, Jawa Barat<br>
                Email: bumdespatimban9@gmail.com
            </p>
        </div>
    </div>
    <hr class="garis-kop">
    <hr class="garis-kop-tipis">

    {{-- JUDUL --}}
    <div class="judul">Surat Pengesahan Mitra</div>
    <div class="nomor-surat">
        Nomor: {{ sprintf('%03d', $user->id) }}/BUMDES-PTMB/SPM/{{ date('Y') }}
    </div>

    {{-- PEMBUKA --}}
    <div class="isi-surat">
        Yang bertanda tangan di bawah ini, Kepala Badan Usaha Milik Desa (BUMDes) Putra Samudra Patimban,
        berdasarkan hasil verifikasi berkas dan penilaian kelayakan usaha, dengan ini menyatakan bahwa:
    </div>

    {{-- DATA MITRA --}}
    <table class="tabel-data">
        <tr>
            <td width="38%">Nama Pemilik Usaha</td>
            <td width="4%">:</td>
            <td><strong style="text-transform: uppercase;">{{ $user->name }}</strong></td>
        </tr>
        <tr>
            <td>Nama Unit Usaha</td>
            <td>:</td>
            <td>{{ $user->mitra->nama_usaha ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis / Kategori Usaha</td>
            <td>:</td>
            <td>{{ $user->mitra->jenis_usaha ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Usaha</td>
            <td>:</td>
            <td>{{ $user->mitra->alamat_usaha ?? '-' }}, Dusun {{ $user->mitra->dusun ?? '-' }}, Desa Patimban</td>
        </tr>
        <tr>
            <td>Status Kemitraan</td>
            <td>:</td>
            <td><strong style="color: green;">AKTIF / RESMI TERDAFTAR</strong></td>
        </tr>
        <tr>
            <td>Tanggal Pengesahan</td>
            <td>:</td>
            <td>{{ $tanggal }}</td>
        </tr>
    </table>

    {{-- PENUTUP --}}
    <div class="isi-surat">
        Berdasarkan hal tersebut di atas, yang bersangkutan dinyatakan <strong>resmi terdaftar sebagai
        Mitra BUMDes Putra Samudra Patimban</strong> dan berhak mendapatkan hak serta kewajiban
        sebagai mitra sesuai ketentuan yang berlaku.
    </div>
    <div class="isi-surat">
        Demikian surat pengesahan ini diterbitkan untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    {{-- TTD -- pakai table agar DomPDF render benar --}}
    <div class="ttd-container">
        <table width="100%">
            <tr>
                <td width="55%">&nbsp;</td>
                <td width="45%" style="text-align: center;">
                    <p style="margin: 0; font-size: 13px; line-height: 1.8;">
                        Patimban, {{ $tanggal }}<br>
                        Kepala BUMDes Putra Samudra Patimban,
                    </p>
                    <div style="margin: 8px 0;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                             alt="QR Code TTD Digital"
                             style="width: 100px; height: 100px;">
                    </div>
                    <p style="margin: 0; font-size: 13px; line-height: 1.8;">
                        <strong><u>IQBAL NUR AFRIZAL</u></strong><br>
                        Kepala BUMDes
                    </p>
                </td>
            </tr>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer-note">
        Dokumen ini diterbitkan secara digital oleh Sistem Informasi BUMDes Patimban &bull;
        Keaslian dokumen dapat diverifikasi dengan memindai QR Code di atas &bull;
        Diterbitkan pada {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</div>
</body>
</html>
