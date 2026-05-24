@extends('theme.default')

@section('title', 'Persetujuan Final - Kepala BUMDes')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     STYLES
═══════════════════════════════════════════════════════════ --}}
<style>
    /* ── Base ─────────────────────────────────────────────── */
    .kbf-page { font-family: 'Inter', 'Segoe UI', sans-serif; }

    /* ── Page Header ──────────────────────────────────────── */
    .kbf-header { margin-bottom: 2rem; }
    .kbf-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }
    .kbf-header p {
        font-size: 0.8125rem;
        color: #64748b;
        margin-top: 0.375rem;
    }

    /* ── Alerts ───────────────────────────────────────────── */
    .kbf-alert {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1.125rem;
        border-radius: 14px;
        font-size: 0.8125rem;
        font-weight: 500;
        margin-bottom: 1.25rem;
        border: 1px solid;
    }
    .kbf-alert-success {
        background: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }
    .kbf-alert-error {
        background: #fff1f2;
        color: #9f1239;
        border-color: #fecdd3;
    }
    .kbf-alert i { font-size: 1rem; flex-shrink: 0; }

    /* ── Table Card ───────────────────────────────────────── */
    .kbf-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 1px 2px rgba(0,0,0,.03);
        overflow: hidden;
    }
    .kbf-overflow { overflow-x: auto; }

    /* ── Table ────────────────────────────────────────────── */
    .kbf-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
    }
    .kbf-table thead tr {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .kbf-table thead th {
        padding: 0.875rem 1.125rem;
        font-size: 0.6875rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .08em;
        white-space: nowrap;
    }
    .kbf-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .kbf-table tbody tr:last-child { border-bottom: none; }
    .kbf-table tbody tr:hover { background: #fafbff; }
    .kbf-table td { padding: 1rem 1.125rem; vertical-align: middle; }

    /* ── Owner Cell ───────────────────────────────────────── */
    .kbf-owner { display: flex; align-items: center; gap: 0.75rem; }
    .kbf-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700;
        flex-shrink: 0;
    }
    .kbf-avatar-purple { background: #ede9fe; color: #6d28d9; }
    .kbf-avatar-teal   { background: #d1fae5; color: #065f46; }
    .kbf-avatar-amber  { background: #fef3c7; color: #92400e; }
    .kbf-avatar-blue   { background: #dbeafe; color: #1e40af; }
    .kbf-avatar-pink   { background: #fce7f3; color: #9d174d; }
    .kbf-owner-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.8125rem;
        line-height: 1.2;
    }
    .kbf-owner-phone {
        font-size: 0.6875rem;
        color: #94a3b8;
        margin-top: 3px;
        display: flex; align-items: center; gap: 4px;
    }
    .kbf-wa-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        flex-shrink: 0;
    }

    /* ── Business Name ────────────────────────────────────── */
    .kbf-usaha {
        font-weight: 700;
        color: #334155;
        font-size: 0.8125rem;
        letter-spacing: -0.01em;
    }

    /* ── Category Badge ───────────────────────────────────── */
    .kbf-badge {
        display: inline-block;
        font-size: 0.625rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        border: 1px solid;
    }
    .kbf-badge-purple { background: #ede9fe; color: #5b21b6; border-color: #c4b5fd; }
    .kbf-badge-teal   { background: #d1fae5; color: #065f46; border-color: #6ee7b7; }
    .kbf-badge-amber  { background: #fef3c7; color: #92400e; border-color: #fcd34d; }
    .kbf-badge-blue   { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
    .kbf-badge-pink   { background: #fce7f3; color: #9d174d; border-color: #f9a8d4; }

    /* ── Address Cell ─────────────────────────────────────── */
    .kbf-addr-main {
        font-size: 0.75rem;
        color: #334155;
        font-weight: 500;
        line-height: 1.4;
    }
    .kbf-addr-sub {
        font-size: 0.6875rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── Status Badge ─────────────────────────────────────── */
    .kbf-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.6875rem;
        font-weight: 700;
        color: #166534;
        background: #f0fdf4;
        padding: 0.3rem 0.7rem;
        border-radius: 8px;
        border: 1px solid #bbf7d0;
        letter-spacing: .02em;
    }
    .kbf-status i { font-size: 0.75rem; }

    /* ── Action Buttons ───────────────────────────────────── */
    .kbf-actions { display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .kbf-btn-approve {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #10b981;
        color: #fff;
        border: none;
        padding: 0.45rem 0.9rem;
        border-radius: 10px;
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .15s, transform .1s, box-shadow .15s;
        box-shadow: 0 2px 8px rgba(16,185,129,.18);
    }
    .kbf-btn-approve:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16,185,129,.28);
    }
    .kbf-btn-approve i { font-size: 0.75rem; }
    .kbf-btn-reject {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
        border-radius: 10px;
        cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
        font-size: 0.75rem;
    }
    .kbf-btn-reject:hover {
        background: #e11d48;
        color: #fff;
        border-color: #e11d48;
    }

    /* ── Empty State ──────────────────────────────────────── */
    .kbf-empty {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
    }
    .kbf-empty i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: .5; }
    .kbf-empty p { font-size: 0.8125rem; font-style: italic; }

    /* ── Modal Overlay ────────────────────────────────────── */
    .kbf-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
    }
    .kbf-overlay.kbf-open {
        opacity: 1;
        pointer-events: all;
    }
    .kbf-modal {
        background: #fff;
        border-radius: 22px;
        width: 100%;
        max-width: 580px;
        padding: 2rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 60px rgba(0,0,0,.12), 0 4px 16px rgba(0,0,0,.06);
        transform: scale(.95) translateY(6px);
        transition: transform .25s cubic-bezier(.34,1.56,.64,1);
    }
    .kbf-overlay.kbf-open .kbf-modal {
        transform: scale(1) translateY(0);
    }

    /* ── Modal — Approve ──────────────────────────────────── */
    .kbf-modal-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }
    .kbf-modal-sub {
        font-size: 0.8125rem;
        color: #64748b;
        margin-bottom: 1.5rem;
    }
    .kbf-preview-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.875rem;
        background: #f8fafc;
        padding: 1.25rem;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    .kbf-preview-span { grid-column: 1 / -1; }
    .kbf-field-label {
        font-size: 0.625rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        margin-bottom: 0.35rem;
    }
    .kbf-field-value {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.6rem 0.875rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #1e293b;
    }
    .kbf-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }
    .kbf-btn-cancel {
        padding: 0.65rem 1.25rem;
        border-radius: 11px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: background .15s;
    }
    .kbf-btn-cancel:hover { background: #e2e8f0; }
    .kbf-btn-submit {
        padding: 0.65rem 1.25rem;
        border-radius: 11px;
        font-size: 0.8125rem;
        font-weight: 700;
        color: #fff;
        background: #10b981;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 2px 10px rgba(16,185,129,.25);
        transition: background .15s, box-shadow .15s;
    }
    .kbf-btn-submit:hover { background: #059669; box-shadow: 0 4px 14px rgba(16,185,129,.35); }
    .kbf-btn-submit i { font-size: 0.8125rem; }

    /* ── Modal — Reject ───────────────────────────────────── */
    .kbf-modal-sm { max-width: 440px; }
    .kbf-reject-icon {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: #fff1f2;
        border: 2px solid #fecdd3;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 1.5rem;
        color: #e11d48;
    }
    .kbf-reject-title {
        font-size: 1.1rem;
        font-weight: 700;
        text-align: center;
        color: #0f172a;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }
    .kbf-reject-body {
        font-size: 0.8125rem;
        color: #64748b;
        text-align: center;
        line-height: 1.65;
        margin-bottom: 1.75rem;
    }
    .kbf-reject-body strong { color: #1e293b; }
    .kbf-reject-body .danger { color: #e11d48; font-weight: 700; }
    .kbf-btn-danger {
        background: #e11d48;
        box-shadow: 0 2px 10px rgba(225,29,72,.22);
    }
    .kbf-btn-danger:hover { background: #be123c; box-shadow: 0 4px 14px rgba(225,29,72,.32); }

    /* ── Loading State ────────────────────────────────────── */
    .kbf-loading {
        text-align: center;
        padding: 2.5rem;
        color: #94a3b8;
        font-size: 0.8125rem;
    }
    .kbf-spinner {
        display: inline-block;
        width: 22px; height: 22px;
        border: 2.5px solid #e2e8f0;
        border-top-color: #6d28d9;
        border-radius: 50%;
        animation: kbf-spin 0.65s linear infinite;
        margin-bottom: 0.75rem;
    }
    @keyframes kbf-spin { to { transform: rotate(360deg); } }

    /* ── Responsive ───────────────────────────────────────── */
    @media (max-width: 768px) {
        .kbf-table thead th:nth-child(3),
        .kbf-table td:nth-child(3),
        .kbf-table thead th:nth-child(4),
        .kbf-table td:nth-child(4) { display: none; }
        .kbf-modal { padding: 1.5rem; }
        .kbf-preview-grid { grid-template-columns: 1fr; }
        .kbf-preview-span { grid-column: auto; }
        .kbf-header h1 { font-size: 1.3rem; }
    }
    @media (max-width: 480px) {
        .kbf-table thead th:nth-child(5),
        .kbf-table td:nth-child(5) { display: none; }
        .kbf-btn-approve span { display: none; }
        .kbf-btn-approve { padding: 0.5rem; width: 34px; height: 34px; justify-content: center; }
        .kbf-btn-approve i { font-size: 0.875rem; }
    }
</style>


{{-- ═══════════════════════════════════════════════════════════
     PAGE BODY
═══════════════════════════════════════════════════════════ --}}
<div class="kbf-page">

    {{-- HEADER --}}
    <div class="kbf-header">
        <h1>Persetujuan Akhir Mitra</h1>
        <p>Tinjau ulang data mitra dan sahkan pendaftaran dengan Tanda Tangan Digital QR Code.</p>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="kbf-alert kbf-alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="kbf-alert kbf-alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="kbf-card">
        <div class="kbf-overflow">
            <table class="kbf-table">
                <thead>
                    <tr>
                        <th>Pemilik</th>
                        <th>Nama Usaha</th>
                        <th>Kategori</th>
                        <th>Alamat Lengkap</th>
                        <th>Status Berkas</th>
                        <th style="text-align:center">Aksi Final</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuans as $item)

                    {{-- Assign avatar & badge color per index --}}
                    @php
                        $colors  = ['purple','teal','amber','blue','pink'];
                        $color   = $colors[$loop->index % count($colors)];
                        $initial = strtoupper(substr($item->name, 0, 2));
                    @endphp

                    <tr>

                        {{-- 1. PEMILIK --}}
                        <td>
                            <div class="kbf-owner">
                                <div class="kbf-avatar kbf-avatar-{{ $color }}">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <div class="kbf-owner-name">{{ $item->name }}</div>
                                    <div class="kbf-owner-phone">
                                        <span class="kbf-wa-dot"></span>
                                        {{ $item->mitra->no_hp ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- 2. NAMA USAHA --}}
                        <td>
                            <div class="kbf-usaha">
                                {{ $item->mitra->nama_usaha ?? 'N/A' }}
                            </div>
                        </td>

                        {{-- 3. KATEGORI --}}
                        <td>
                            <span class="kbf-badge kbf-badge-{{ $color }}">
                                {{ $item->mitra->jenis_usaha ?? '-' }}
                            </span>
                        </td>

                        {{-- 4. ALAMAT --}}
                        <td>
                            <div class="kbf-addr-main">
                                <i class="fas fa-map-marker-alt" style="color:#f43f5e;font-size:.6rem;margin-right:4px"></i>
                                {{ $item->mitra->alamat_usaha ?? '-' }}
                            </div>
                            <div class="kbf-addr-sub">
                               Dusun {{ $item->mitra->dusun ?? '-' }}
                            </div>
                        </td>

                        {{-- 5. STATUS --}}
                        <td>
                            <span class="kbf-status">
                                <i class="fas fa-check-double"></i>
                                Lolos Admin
                            </span>
                        </td>

                        {{-- 6. AKSI --}}
                        <td>
                            <div class="kbf-actions">
                                <button
                                    type="button"
                                    onclick="kbfOpenApprove({{ $item->id }})"
                                    class="kbf-btn-approve"
                                    title="Sahkan TTD untuk {{ $item->name }}">
                                    <i class="fas fa-signature"></i>
                                    <span>Sahkan TTD</span>
                                </button>

                                <button
                                    type="button"
                                    onclick="kbfOpenReject({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                    class="kbf-btn-reject"
                                    title="Tolak pengajuan {{ $item->name }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="kbf-empty">
                                <i class="fas fa-inbox"></i>
                                <p>Belum ada pengajuan dari Admin.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>{{-- /.kbf-page --}}


{{-- ═══════════════════════════════════════════════════════════
     MODAL — APPROVE
═══════════════════════════════════════════════════════════ --}}
<div id="kbfApproveOverlay" class="kbf-overlay" onclick="kbfBdClose(event,'kbfApproveOverlay')">
    <div class="kbf-modal">

        {{-- Loading --}}
        <div id="kbfApproveLoading" class="kbf-loading" style="display:none">
            <div class="kbf-spinner"></div>
            <p>Memuat data mitra...</p>
        </div>

        {{-- Content --}}
        <div id="kbfApproveContent">
            <p class="kbf-modal-title">Pratinjau Pengesahan</p>
            <p class="kbf-modal-sub">Pastikan data di bawah sudah benar sebelum TTD Digital diterbitkan.</p>

            <div class="kbf-preview-grid">
                <div class="kbf-preview-span">
                    <div class="kbf-field-label">Nama Pemilik</div>
                    <div class="kbf-field-value" id="kbfPrevName">—</div>
                </div>
                <div>
                    <div class="kbf-field-label">Nama Usaha</div>
                    <div class="kbf-field-value" id="kbfPrevUsaha">—</div>
                </div>
                <div>
                    <div class="kbf-field-label">Kategori</div>
                    <div class="kbf-field-value" id="kbfPrevKategori">—</div>
                </div>
            </div>

            <form id="kbfApproveForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="kbf-modal-actions">
                    <button type="button" onclick="kbfCloseApprove()" class="kbf-btn-cancel">
                        Batal
                    </button>
                    <button type="submit" class="kbf-btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Terbitkan Sertifikat &amp; Aktifkan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     MODAL — REJECT
═══════════════════════════════════════════════════════════ --}}
<div id="kbfRejectOverlay" class="kbf-overlay" onclick="kbfBdClose(event,'kbfRejectOverlay')">
    <div class="kbf-modal kbf-modal-sm">

        <div class="kbf-reject-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <p class="kbf-reject-title">Tolak Pengajuan?</p>
        <p class="kbf-reject-body">
            Anda akan menolak pengajuan dari
            <strong id="kbfRejectNama">—</strong>.
            Tindakan ini akan <span class="danger">menghapus semua data &amp; berkas</span>
            mitra secara permanen dan tidak dapat dibatalkan.
        </p>

        <form id="kbfRejectForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="kbf-modal-actions" style="justify-content:center">
                <button type="button" onclick="kbfCloseReject()" class="kbf-btn-cancel">
                    Batal
                </button>
                <button type="submit" class="kbf-btn-submit kbf-btn-danger">
                    <i class="fas fa-trash-alt"></i>
                    Ya, Tolak &amp; Hapus
                </button>
            </div>
        </form>

    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════════ --}}
<script>
(function () {

    /* ── Helpers ─────────────────────────────────────────── */
    function show(id)  { document.getElementById(id).style.display = ''; }
    function hide(id)  { document.getElementById(id).style.display = 'none'; }
    function open(id)  { document.getElementById(id).classList.add('kbf-open'); }
    function close(id) { document.getElementById(id).classList.remove('kbf-open'); }
    function el(id)    { return document.getElementById(id); }

    /* ── Close on backdrop click ─────────────────────────── */
    window.kbfBdClose = function (e, overlayId) {
        if (e.target === e.currentTarget) close(overlayId);
    };

    /* ── APPROVE ─────────────────────────────────────────── */
    window.kbfOpenApprove = async function (id) {
        hide('kbfApproveContent');
        show('kbfApproveLoading');
        open('kbfApproveOverlay');

        try {
            const res  = await fetch(`/kepala-bumdes/pengajuan/${id}/preview`);
            if (!res.ok) throw new Error('Server error');
            const data = await res.json();

            el('kbfPrevName').textContent     = data.name      ?? '—';
            el('kbfPrevUsaha').textContent    = data.nama_usaha ?? '—';
            el('kbfPrevKategori').textContent = data.jenis_usaha ?? '—';
            el('kbfApproveForm').action       = `/kepala-bumdes/pengajuan/${id}/setujui`;

            hide('kbfApproveLoading');
            show('kbfApproveContent');
        } catch (err) {
            el('kbfApproveLoading').innerHTML =
                `<p style="color:#e11d48;font-size:.8125rem">
                    <i class="fas fa-times-circle" style="margin-right:6px"></i>
                    Gagal memuat data. Silakan coba lagi.
                 </p>`;
        }
    };

    window.kbfCloseApprove = function () {
        close('kbfApproveOverlay');
        /* Reset untuk penggunaan berikutnya */
        setTimeout(function () {
            hide('kbfApproveContent');
            el('kbfApproveLoading').innerHTML =
                `<div class="kbf-spinner"></div><p>Memuat data mitra...</p>`;
            show('kbfApproveLoading');
        }, 300);
    };

    /* ── REJECT ──────────────────────────────────────────── */
    window.kbfOpenReject = function (id, nama) {
        el('kbfRejectNama').textContent = nama;
        el('kbfRejectForm').action      = `/kepala-bumdes/pengajuan/${id}/tolak`;
        open('kbfRejectOverlay');
    };

    window.kbfCloseReject = function () {
        close('kbfRejectOverlay');
    };

}());
</script>

@endsection
