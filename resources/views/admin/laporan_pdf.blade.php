@extends('theme.pdf')

@section('title', 'Laporan Keuangan BUMDes - ' . $bulanAktif)

@section('styles')
<style>
    .judul       { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 3px; text-decoration: underline; letter-spacing: 2px; text-transform: uppercase; }
    .sub-judul   { text-align: center; font-size: 12px; margin-bottom: 20px; color: #444; }
    .info-table td { padding: 2px 6px; font-size: 12px; }
    .info-table td:first-child { width: 140px; color: #555; }
    .ringkasan   { width: 100%; border-collapse: collapse; margin: 16px 0; }
    .ringkasan td { border: 1px solid #cbd5e1; padding: 10px 14px; width: 50%; }
    .ringkasan .label { font-size: 9px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }
    .ringkasan .nilai { font-size: 14px; font-weight: bold; margin-top: 4px; }
    table.data   { width: 100%; border-collapse: collapse; margin: 16px 0; }
    table.data thead tr { background: #1e3a5f; color: white; }
    table.data thead th { padding: 8px 10px; font-size: 10px; text-transform: uppercase; }
    table.data thead th.right { text-align: right; }
    table.data tbody tr:nth-child(even) { background: #f8fafc; }
    table.data tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
    table.data tbody td.right { text-align: right; }
    table.data tfoot td { padding: 8px 10px; font-weight: bold; background: #f1f5f9; border-top: 2px solid #1e3a5f; font-size: 11px; }
    table.data tfoot td.right { text-align: right; color: #1d4ed8; }
    .ttd-wrap    { margin-top: 30px; width: 100%; }
    .ttd-box     { text-align: center; }
    .ttd-ruang   { height: 55px; }
</style>
@endsection

@section('content')

    <div class="judul">Laporan Keuangan Bagi Hasil Mitra</div>
    <div class="sub-judul">Periode: {{ $bulanAktif }}</div>

    {{-- INFO DOKUMEN --}}
    <table class="info-table" style="margin-bottom: 14px;">
        <tr><td>Tanggal Cetak</td><td>:</td><td>{{ now()->translatedFormat('d F Y') }}</td></tr>
        <tr><td>Dicetak Oleh</td><td>:</td><td>{{ auth()->user()->name }} (Admin)</td></tr>
        <tr><td>Jumlah Mitra Aktif</td><td>:</td><td>{{ $totalMitra }} Mitra</td></tr>
    </table>

    {{-- RINGKASAN --}}
    <table class="ringkasan">
        <tr>
            <td>
                <div class="label">Total Omzet Mitra</div>
                <div class="nilai">Rp {{ number_format($totalBagiHasil, 0, ',', '.') }}</div>
            </td>
            <td style="background: #eff6ff;">
                <div class="label" style="color: #3b82f6;">Kas Masuk BUMDes</div>
                <div class="nilai" style="color: #1d4ed8;">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- TABEL DETAIL --}}
    <table class="data">
        <thead>
            <tr>
                <th style="text-align:left">No</th>
                <th style="text-align:left">Nama Mitra</th>
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

    {{-- TANDA TANGAN --}}
    <table class="ttd-wrap">
        <tr>
            <td width="55%">&nbsp;</td>
            <td width="45%" style="text-align:center;" class="ttd-box">
                <p style="margin:0; font-size:13px;">Patimban, {{ now()->translatedFormat('d F Y') }}<br>Kepala BUMDes Putra Samudra Patimban,</p>
                <div class="ttd-ruang"></div>
                <p style="margin:0; font-size:13px;"><strong><u>IQBAL NUR AFRIZAL</u></strong><br>Kepala BUMDes</p>
            </td>
        </tr>
    </table>

@endsection
