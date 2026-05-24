
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Verifikasi - BUMDes</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.01em; }
        .step-ring { @apply ring-4 ring-white; }
        @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .animate-spin-slow { animation: spin-slow 3s linear infinite; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 lg:p-8">

    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/60 border border-slate-100 overflow-hidden">

        @php
            $user = Auth::user();

            // ✅ BACA STATUS DARI TABEL MITRAS (Prioritas utama)
            $status = $user->mitra->status ?? $user->status;

            $isPending = $status === 'pending';
            $isMenungguKepala = $status === 'menunggu_kepala';
            $isAktif = in_array($status, ['aktif', 'approved']);
            $isRejected = $status === 'rejected';

            // Logika Waktu Tunggu Jika Ditolak
            $rejectionDate = $user->updated_at;
            $canReapplyDate = $rejectionDate->copy()->addMonth();
            $now = now();
            $canReapply = $now->greaterThanOrEqualTo($canReapplyDate);
            $daysLeft = $now->diffInDays($canReapplyDate, false);
        @endphp

        {{-- Header Status --}}
        <div class="{{ $isRejected ? 'bg-rose-600' : ($isAktif ? 'bg-emerald-600' : ($isMenungguKepala ? 'bg-indigo-600' : 'bg-blue-600')) }} p-8 sm:p-12 text-center relative overflow-hidden transition-colors duration-500">
            <i class="fas {{ $isRejected ? 'fa-times-circle' : 'fa-shield-check' }} absolute -right-10 -top-10 text-9xl text-white opacity-10 transform rotate-12"></i>
            <div class="relative z-10">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                    <i class="fas {{ $isRejected ? 'fa-user-times text-rose-600' : ($isAktif ? 'fa-user-check text-emerald-600' : ($isMenungguKepala ? 'fa-user-tie text-indigo-600' : 'fa-user-clock text-blue-600')) }} text-4xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                    @if($isRejected)
                        Pendaftaran Ditolak
                    @elseif($isAktif)
                        Selamat, Akun Aktif!
                    @elseif($isMenungguKepala)
                        Menunggu Kepala BUMDes
                    @else
                        Verifikasi Admin
                    @endif
                </h1>
                <p class="text-white/80 mt-3 font-medium max-w-lg mx-auto text-sm sm:text-base">
                    @if($isRejected)
                        Mohon maaf, pendaftaran Anda tidak dapat kami setujui setelah tahap peninjauan data.
                    @elseif($isAktif)
                        Pendaftaran Anda telah disahkan sepenuhnya. Sertifikat pengesahan telah diterbitkan ke email Anda.
                    @elseif($isMenungguKepala)
                        Tahap 1 Selesai! Berkas Anda lolos verifikasi Admin. Saat ini menunggu tanda tangan pengesahan dari Kepala BUMDes.
                    @else
                        Terima kasih telah mendaftar. Saat ini data KTP & SKU Anda sedang dalam tahap pemeriksaan oleh Admin BUMDes.
                    @endif
                </p>
            </div>
        </div>

        <div class="p-6 sm:p-12">
            <h2 class="text-sm font-bold text-slate-400 mb-12 uppercase tracking-[0.2em] text-center sm:text-left">Alur Pendaftaran Mitra</h2>

            {{-- Stepper Progress --}}
            <div class="relative flex flex-row items-start justify-between mb-16 px-2">
                <div class="absolute top-6 left-0 w-full h-0.5 bg-slate-100 z-0"></div>

                {{-- Step 1: Registrasi --}}
                <div class="relative z-10 flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-[10px] sm:text-xs font-bold text-slate-700 uppercase text-center">Registrasi</p>
                </div>

                {{-- Step 2: Data Usaha --}}
                <div class="relative z-10 flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-[10px] sm:text-xs font-bold text-slate-700 uppercase text-center">Data Usaha</p>
                </div>

                {{-- Step 3: Verifikasi Admin --}}
                <div class="relative z-10 flex flex-col items-center flex-1">
                    @if($isRejected && $user->status === 'rejected')
                        {{-- Jika ditolak admin --}}
                        <div class="w-12 h-12 rounded-full bg-rose-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                            <i class="fas fa-times"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-rose-600 uppercase text-center">Ditolak</p>
                    @elseif($isMenungguKepala || $isAktif)
                        {{-- Jika lolos admin (centang hijau) --}}
                        <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-700 uppercase text-center">Admin Sukses</p>
                    @else
                        {{-- Jika masih pending (muter kuning/biru) --}}
                        <div class="relative w-12 h-12 mb-3">
                            <div class="absolute inset-0 rounded-full bg-blue-400 animate-ping opacity-30"></div>
                            <div class="relative w-12 h-12 rounded-full bg-white text-blue-500 border-2 border-blue-500 flex items-center justify-center font-bold shadow-md step-ring">
                                <i class="fas fa-spinner fa-spin text-sm"></i>
                            </div>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-blue-600 uppercase text-center tracking-tighter">Cek Admin</p>
                    @endif
                </div>

                {{-- Step 4: Pengesahan Kepala BUMDes --}}
                <div class="relative z-10 flex flex-col items-center flex-1">
                    @if($isRejected)
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 border-2 border-rose-500 flex items-center justify-center font-bold mb-3 step-ring">
                            <i class="fas fa-ban"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-rose-700 uppercase text-center">Gagal</p>
                    @elseif($isAktif)
                        {{-- Jika disahkan kepala desa (centang hijau) --}}
                        <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-emerald-700 uppercase text-center">Aktif</p>
                    @elseif($isMenungguKepala)
                        {{-- Jika menunggu kepala desa (muter ungu/biru) --}}
                        <div class="relative w-12 h-12 mb-3">
                            <div class="absolute inset-0 rounded-full bg-indigo-400 animate-ping opacity-30"></div>
                            <div class="relative w-12 h-12 rounded-full bg-white text-indigo-500 border-2 border-indigo-500 flex items-center justify-center font-bold shadow-md step-ring">
                                <i class="fas fa-spinner fa-spin text-sm"></i>
                            </div>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-indigo-600 uppercase text-center tracking-tighter">Kepala Desa</p>
                    @else
                        {{-- Jika masih di tahap admin, ini dilock/gembok abu-abu --}}
                        <div class="w-12 h-12 rounded-full bg-white text-slate-300 border-2 border-slate-200 flex items-center justify-center font-bold mb-3 step-ring text-sm">
                            <i class="fas fa-lock"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase text-center">Kepala Desa</p>
                    @endif
                </div>
            </div>

            {{-- Kartu Aksi Berdasarkan Status --}}
            @if($isRejected)
                <div class="bg-rose-50 border border-rose-100 rounded-3xl p-6 flex items-start gap-5">
                    <div class="bg-rose-100 text-rose-600 rounded-2xl w-12 h-12 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-rose-900 mb-2 text-lg">Pendaftaran Ditolak</h3>
                        <p class="text-sm text-rose-800/80 leading-relaxed mb-5">
                            Mohon maaf, pendaftaran Anda belum memenuhi kriteria mitra BUMDes saat ini. Anda dapat menghubungi Admin via WhatsApp untuk menanyakan alasan penolakan lebih lanjut.
                        </p>

                        @if(!$canReapply)
                            <div class="mb-5 p-3 bg-rose-100/50 border border-rose-200 rounded-xl">
                                <p class="text-[11px] font-bold text-rose-700 flex items-center gap-2 uppercase text-center sm:text-left">
                                    <i class="fas fa-clock"></i> Anda dapat mengajukan kembali dalam {{ ceil($daysLeft) }} hari lagi
                                </p>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                            @if($canReapply)
                                <a href="{{ route('register.mitra') }}" class="inline-flex items-center gap-3 bg-emerald-600 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all hover:-translate-y-1">
                                    <i class="fas fa-edit"></i> Daftar Ulang Sekarang
                                </a>
                            @endif
                            <a href="https://wa.me/628123456789" target="_blank" class="inline-flex items-center gap-3 bg-rose-600 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-rose-200 hover:bg-rose-700 transition-all hover:-translate-y-1">
                                <i class="fab fa-whatsapp text-lg"></i> Hubungi Admin
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($isAktif)
                <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-6 flex items-start gap-5">
                    <div class="bg-emerald-100 text-emerald-600 rounded-2xl w-12 h-12 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-check-double text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-emerald-900 mb-2 text-lg">Akses Mitra Terbuka</h3>
                        <p class="text-sm text-emerald-800/80 leading-relaxed mb-5">
                            Selamat! Akun Anda sudah berstatus aktif dan disetujui penuh. Anda kini dapat memposting produk/jasa dan melakukan transaksi di BUMDes Patimban.
                        </p>
                        <div class="flex justify-center sm:justify-start">
                            {{-- ✅ TOMBOL KE DASHBOARD MITRA --}}
                            <a href="{{ route('mitra.dashboard') }}" class="inline-flex items-center gap-3 bg-emerald-600 text-white px-8 py-4 rounded-2xl text-base font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all hover:-translate-y-1 group">
                                <i class="fas fa-rocket group-hover:animate-bounce"></i> Masuk ke Dashboard Mitra
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($isMenungguKepala)
                <div class="bg-indigo-50 border border-indigo-100 rounded-3xl p-6 flex items-start gap-5">
                    <div class="bg-indigo-100 text-indigo-600 rounded-2xl w-12 h-12 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-file-signature text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-indigo-900 mb-2 text-lg">Verifikasi Admin Berhasil</h3>
                        <p class="text-sm text-indigo-800/80 leading-relaxed mb-5">
                            Dokumen usaha Anda telah dinyatakan <strong>VALID</strong> oleh Admin. Saat ini sistem sedang menerbitkan Sertifikat Kemitraan yang menunggu tanda tangan digital dari Kepala BUMDes. Harap bersabar sedikit lagi.
                        </p>
                        <div class="flex justify-center sm:justify-start">
                            <button onclick="window.location.reload();" class="inline-flex items-center gap-3 bg-white border border-indigo-200 text-indigo-700 px-6 py-3 rounded-2xl text-sm font-bold shadow-sm hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all">
                                <i class="fas fa-sync-alt animate-spin-slow"></i> Refresh Status
                            </button>
                        </div>
                    </div>
                </div>

            @else {{-- Pending / Verifikasi Admin --}}
                <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6 flex items-start gap-5">
                    <div class="bg-blue-100 text-blue-600 rounded-2xl w-12 h-12 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-info-circle text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-blue-900 mb-2 text-lg">Apa langkah selanjutnya?</h3>
                        <p class="text-sm text-blue-800/80 leading-relaxed mb-5">
                            Tim Admin sedang memverifikasi keaslian dokumen Anda (KTP & SKU). Proses pemeriksaan ini memakan waktu maksimal <strong>1x24 jam kerja</strong>. Anda dapat me-refresh halaman ini secara berkala.
                        </p>
                        <div class="flex justify-center sm:justify-start">
                            <button onclick="window.location.reload();" class="inline-flex items-center gap-3 bg-white border border-blue-200 text-blue-700 px-6 py-3 rounded-2xl text-sm font-bold shadow-sm hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all">
                                <i class="fas fa-sync-alt animate-spin-slow"></i> Refresh Status
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tombol Logout Sementara --}}
            <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-slate-400 hover:text-rose-500 transition-colors flex items-center justify-center gap-3 mx-auto uppercase tracking-widest">
                        <i class="fas fa-sign-out-alt"></i> Keluar Sementara
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

