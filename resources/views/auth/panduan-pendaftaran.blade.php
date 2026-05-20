<x-guest-layout>
<style>
    .panduan-wrap { max-width: 760px; margin: 0 auto; color: #fff; }

    /* Header */
    .panduan-header { text-align: center; margin-bottom: 24px; }
    .panduan-header .badge {
        display: inline-block; background: rgba(56,189,248,0.12);
        border: 1px solid rgba(56,189,248,0.25); color: #38bdf8;
        font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        padding: 4px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .panduan-header h1 { font-size: 21px; font-weight: 800; margin-bottom: 6px; }
    .panduan-header p  { font-size: 12px; color: rgba(255,255,255,0.45); line-height: 1.6; }

    /* Tab switcher */
    .tab-wrap { display: flex; gap: 0; margin-bottom: 22px; background: rgba(255,255,255,0.05); border-radius: 10px; padding: 4px; }
    .tab-btn {
        flex: 1; padding: 9px; border: none; border-radius: 7px; cursor: pointer;
        font-size: 12px; font-weight: 700; transition: 0.2s; background: transparent; color: rgba(255,255,255,0.45);
    }
    .tab-btn.active { background: #8a9a5b; color: #000; }
    .tab-btn i { margin-right: 6px; }

    /* Panel */
    .panel { display: none; }
    .panel.active { display: block; }

    /* Dasar hukum */
    .legal-box {
        background: rgba(56,189,248,0.06); border: 1px solid rgba(56,189,248,0.18);
        border-radius: 10px; padding: 12px 16px; margin-bottom: 18px;
        font-size: 12px; color: rgba(255,255,255,0.65); line-height: 1.7;
    }
    .legal-box strong { color: #38bdf8; display: block; margin-bottom: 5px; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; }
    .legal-box ul { margin: 0 0 0 16px; }

    /* Section */
    .section { margin-bottom: 18px; }
    .section-title {
        font-size: 11px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 1px; color: #fbbc05; margin-bottom: 10px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-title::after { content:''; flex:1; height:1px; background: rgba(251,188,5,0.15); }

    /* Steps */
    .steps { display: flex; flex-direction: column; gap: 8px; }
    .step {
        display: flex; gap: 12px; align-items: flex-start;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);
        border-radius: 9px; padding: 10px 13px;
    }
    .step-num {
        width: 26px; height: 26px; min-width: 26px;
        background: #8a9a5b; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; color: #000;
    }
    .step-body strong { display: block; font-size: 12px; font-weight: 700; color: #fff; margin-bottom: 1px; }
    .step-body span   { font-size: 11px; color: rgba(255,255,255,0.5); line-height: 1.5; }

    /* Docs */
    .doc-list { display: flex; flex-direction: column; gap: 7px; }
    .doc-item {
        display: flex; align-items: flex-start; gap: 10px;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);
        border-radius: 8px; padding: 9px 12px;
    }
    .doc-item i { color: #fbbc05; font-size: 14px; min-width: 16px; margin-top: 1px; }
    .doc-item strong { display: block; font-size: 12px; color: #fff; font-weight: 700; }
    .doc-item span   { font-size: 11px; color: rgba(255,255,255,0.5); line-height: 1.5; }

    /* Terms */
    .terms-list { display: flex; flex-direction: column; gap: 6px; }
    .term-item { display: flex; align-items: flex-start; gap: 9px; font-size: 12px; color: rgba(255,255,255,0.6); line-height: 1.5; }
    .term-item i { color: #8a9a5b; margin-top: 2px; min-width: 14px; font-size: 11px; }

    /* Info box */
    .info-box {
        background: rgba(138,154,91,0.08); border: 1px solid rgba(138,154,91,0.25);
        border-radius: 9px; padding: 11px 14px; font-size: 11px;
        color: rgba(255,255,255,0.6); line-height: 1.6; margin-bottom: 18px;
    }
    .info-box i { color: #8a9a5b; margin-right: 5px; }

    /* CTA */
    .cta-row { display: flex; gap: 9px; }
    .btn-primary {
        flex: 1; background: #8a9a5b; border: 2px solid #000; border-radius: 8px;
        padding: 10px; font-size: 12px; font-weight: 800; color: #000;
        text-decoration: none; text-align: center; transition: 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-primary:hover { background: #9cb066; }
    .btn-secondary {
        flex: 1; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px; padding: 10px; font-size: 12px; font-weight: 700;
        color: rgba(255,255,255,0.65); text-decoration: none; text-align: center; transition: 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-secondary:hover { background: rgba(255,255,255,0.1); color: #fff; }

    @media (max-width: 600px) {
        .panduan-header h1 { font-size: 17px; }
        .cta-row { flex-direction: column; }
    }
</style>

<div class="panduan-wrap">

    {{-- Header --}}
    <div class="panduan-header">
        <div class="badge"><i class="fas fa-book-open mr-1"></i> Panduan Resmi</div>
        <h1>Panduan Pendaftaran BUMDes Patimban</h1>
        <p>Pilih jenis akun yang ingin Anda daftarkan untuk melihat panduan yang sesuai.</p>
    </div>

    {{-- ==================== PANEL MITRA ==================== --}}
    <div id="panel-mitra" class="panel active">

        <div class="legal-box">
            <strong><i class="fas fa-balance-scale mr-1"></i> Dasar Hukum</strong>
            <ul>
                <li>UU No. 6 Tahun 2014 tentang Desa</li>
                <li>PP No. 11 Tahun 2021 tentang Badan Usaha Milik Desa</li>
                <li>Permendes PDTT No. 3 Tahun 2021 tentang BUMDes</li>
                <li>Peraturan Desa Patimban tentang Pengelolaan BUMDes</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title"><i class="fas fa-clipboard-check"></i> Syarat Umum</div>
            <div class="terms-list">
                <div class="term-item"><i class="fas fa-check-circle"></i> Warga atau pelaku usaha yang berdomisili di wilayah Desa Patimban.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Memiliki usaha yang sah, aktif, dan tidak bertentangan dengan hukum.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Berusia minimal 17 tahun atau sudah menikah (memiliki KTP).</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Bersedia mematuhi seluruh ketentuan dan kebijakan BUMDes Patimban.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Bersedia membayar bagi hasil sesuai kesepakatan yang ditetapkan BUMDes.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Tidak sedang dalam sengketa hukum berkaitan dengan usaha yang didaftarkan.</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title"><i class="fas fa-folder-open"></i> Dokumen yang Diperlukan</div>
            <div class="doc-list">
                <div class="doc-item">
                    <i class="fas fa-id-card"></i>
                    <div>
                        <strong>NIK / KTP</strong>
                        <span>Wajib. Input 16 digit NIK sesuai KTP asli untuk verifikasi identitas.</span>
                    </div>
                </div>
                <div class="doc-item">
                    <i class="fas fa-file-signature"></i>
                    <div>
                        <strong>Surat Keterangan Usaha (SKU)</strong>
                        <span>Wajib. Diterbitkan Kepala Desa setempat. Format: JPG, PNG, atau PDF. Maks. 2 MB.</span>
                    </div>
                </div>
                <div class="doc-item">
                    <i class="fas fa-mobile-alt"></i>
                    <div>
                        <strong>Nomor WhatsApp Aktif</strong>
                        <span>Wajib. Untuk notifikasi pesanan dan komunikasi resmi. Diawali angka 62.</span>
                    </div>
                </div>
                <div class="doc-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email Aktif</strong>
                        <span>Wajib. Untuk akses akun dan notifikasi sistem.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title"><i class="fas fa-route"></i> Alur Pendaftaran</div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <strong>Isi Formulir Pendaftaran</strong>
                        <span>Lengkapi seluruh data diri dan usaha. Pastikan semua informasi akurat dan dapat dipertanggungjawabkan.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <strong>Upload Dokumen Pendukung</strong>
                        <span>Unggah SKU yang telah disahkan Kepala Desa. File harus jelas dan dapat dibaca.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <strong>Verifikasi oleh Kepala BUMDes</strong>
                        <span>Data diperiksa oleh Kepala BUMDes. Proses verifikasi maksimal <strong style="color:#fff">3 hari kerja</strong>.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <div class="step-body">
                        <strong>Aktivasi Akun & Notifikasi</strong>
                        <span>Jika disetujui, akun diaktifkan dan notifikasi dikirim via email atau WhatsApp.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">5</div>
                    <div class="step-body">
                        <strong>Penandatanganan Perjanjian Kemitraan</strong>
                        <span>Mitra wajib menandatangani perjanjian kemitraan dengan BUMDes Patimban sebagai dasar hukum.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            Pendaftaran tidak lengkap atau berisi data tidak valid akan ditolak. BUMDes berhak mencabut kemitraan jika Mitra terbukti melanggar ketentuan.
            Hubungi kantor BUMDes Patimban pada jam kerja: <strong style="color:#8a9a5b">Senin–Jumat, 08.00–16.00 WIB.</strong>
        </div>

        <div class="cta-row">
            <a href="{{ route('register') }}" class="btn-primary">
                <i class="fas fa-user-tie"></i> Kembali ke Register
            </a>

        </div>
    </div>

    {{-- ==================== PANEL PELANGGAN ==================== --}}
    <div id="panel-pelanggan" class="panel">

        <div class="legal-box">
            <strong><i class="fas fa-shield-alt mr-1"></i> Perlindungan Konsumen</strong>
            <ul>
                <li>UU No. 8 Tahun 1999 tentang Perlindungan Konsumen</li>
                <li>PP No. 71 Tahun 2019 tentang Penyelenggaraan Sistem dan Transaksi Elektronik</li>
                <li>Kebijakan Privasi & Syarat Layanan BUMDes Patimban</li>
            </ul>
        </div>

        <div class="section">
            <div class="section-title"><i class="fas fa-clipboard-check"></i> Syarat Umum</div>
            <div class="terms-list">
                <div class="term-item"><i class="fas fa-check-circle"></i> Warga umum yang ingin membeli produk atau memesan jasa dari Mitra BUMDes Patimban.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Berusia minimal 17 tahun atau atas seizin orang tua/wali.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Memiliki email aktif dan nomor WhatsApp yang dapat dihubungi.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Bersedia memberikan alamat pengiriman yang valid dan dapat dijangkau.</div>
                <div class="term-item"><i class="fas fa-check-circle"></i> Bertanggung jawab atas setiap transaksi yang dilakukan menggunakan akun pribadi.</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title"><i class="fas fa-folder-open"></i> Data yang Diperlukan</div>
            <div class="doc-list">
                <div class="doc-item">
                    <i class="fas fa-user"></i>
                    <div>
                        <strong>Nama Lengkap</strong>
                        <span>Sesuai identitas resmi. Digunakan untuk keperluan pengiriman dan transaksi.</span>
                    </div>
                </div>
                <div class="doc-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email Aktif</strong>
                        <span>Wajib. Untuk login, konfirmasi pesanan, dan notifikasi transaksi.</span>
                    </div>
                </div>
                <div class="doc-item">
                    <i class="fas fa-mobile-alt"></i>
                    <div>
                        <strong>Nomor WhatsApp</strong>
                        <span>Wajib. Untuk konfirmasi pesanan dan komunikasi dengan Mitra. Format tanpa angka 0 di depan.</span>
                    </div>
                </div>
                <div class="doc-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Alamat Lengkap Pengiriman</strong>
                        <span>Wajib. Sertakan nama jalan, RT/RW, dusun, dan desa agar pengiriman akurat.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title"><i class="fas fa-route"></i> Alur Pendaftaran</div>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <strong>Isi Formulir Pendaftaran</strong>
                        <span>Lengkapi nama, email, nomor WhatsApp, jenis kelamin, dan alamat pengiriman.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <strong>Buat Password</strong>
                        <span>Buat password minimal 8 karakter. Gunakan kombinasi huruf dan angka untuk keamanan akun.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <strong>Akun Langsung Aktif</strong>
                        <span>Pendaftaran pelanggan <strong style="color:#fff">tidak memerlukan verifikasi manual</strong>. Akun langsung dapat digunakan.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <div class="step-body">
                        <strong>Mulai Berbelanja</strong>
                        <span>Masuk ke dashboard, jelajahi produk dan jasa dari Mitra BUMDes Patimban, lalu lakukan pemesanan.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            Data pribadi Anda dijaga kerahasiaannya sesuai kebijakan privasi BUMDes Patimban dan tidak akan dibagikan kepada pihak ketiga tanpa persetujuan Anda.
            Untuk pertanyaan, hubungi kami melalui WhatsApp resmi BUMDes Patimban.
        </div>

        <div class="cta-row">
            <a href="{{ route('register.pelanggan') }}" class="btn-primary">
                <i class="fas fa-shopping-bag"></i> Daftar sebagai Pelanggan
            </a>
            <a href="{{ route('login') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>

</div>

@push('js')
<script>
    function switchTab(type, btn) {
        // Update tombol
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Update panel
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        document.getElementById('panel-' + type).classList.add('active');
    }

    // Baca query param ?tab=pelanggan untuk deep link
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'pelanggan') {
        const btns = document.querySelectorAll('.tab-btn');
        switchTab('pelanggan', btns[1]);
    }
</script>
@endpush
</x-guest-layout>
