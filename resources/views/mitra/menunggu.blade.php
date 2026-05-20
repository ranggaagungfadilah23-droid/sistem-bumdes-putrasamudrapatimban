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
            $status = $user->status;
            $isRejected = $status == 'rejected';
            $isApproved = $status == 'approved';

            // Logika Waktu Tunggu 1 Bulan (Logika Lama Tetap Dipertahankan)
            $rejectionDate = $user->updated_at;
            $canReapplyDate = $rejectionDate->copy()->addMonth();
            $now = now();
            $canReapply = $now->greaterThanOrEqualTo($canReapplyDate);
            $daysLeft = $now->diffInDays($canReapplyDate, false);
        @endphp

        {{-- Header Status --}}
        <div class="{{ $isRejected ? 'bg-rose-600' : ($isApproved ? 'bg-emerald-600' : 'bg-blue-600') }} p-8 sm:p-12 text-center relative overflow-hidden transition-colors duration-500">
            <i class="fas {{ $isRejected ? 'fa-times-circle' : 'fa-shield-check' }} absolute -right-10 -top-10 text-9xl text-white opacity-10 transform rotate-12"></i>
            <div class="relative z-10">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                    <i class="fas {{ $isRejected ? 'fa-user-times text-rose-600' : ($isApproved ? 'fa-user-check text-emerald-600' : 'fa-user-clock text-blue-600') }} text-4xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                    @if($isRejected) Pendaftaran Ditolak @elseif($isApproved) Pendaftaran Disetujui! @else Pendaftaran Berhasil! @endif
                </h1>
                <p class="text-white/80 mt-3 font-medium max-w-lg mx-auto text-sm sm:text-base">
                    @if($isRejected)
                        Mohon maaf, pendaftaran sebagai Mitra BUMDes Putra Samudra Patimban Anda tidak dapat kami setujui setelah tahap peninjauan data.
                    @elseif($isApproved)
                        Selamat! Akun Anda telah aktif. Anda kini dapat mengelola usaha Anda melalui dashboard mitra.
                    @else
                        Terima kasih telah bergabung. Saat ini akun Anda sedang dalam tahap peninjauan oleh Admin.
                    @endif
                </p>
            </div>
        </div>

        <div class="p-6 sm:p-12">
            <h2 class="text-sm font-bold text-slate-400 mb-12 uppercase tracking-[0.2em] text-center sm:text-left">Status Aktivasi Akun</h2>

            {{-- Stepper Progress --}}
            <div class="relative flex flex-row items-start justify-between mb-16 px-2">
                <div class="absolute top-6 left-0 w-full h-0.5 bg-slate-100 z-0"></div>

                <div class="relative z-10 flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-[10px] sm:text-xs font-bold text-slate-700 uppercase text-center">Registrasi</p>
                </div>

                <div class="relative z-10 flex flex-col items-center flex-1">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-[10px] sm:text-xs font-bold text-slate-700 uppercase text-center">Data Usaha</p>
                </div>

                <div class="relative z-10 flex flex-col items-center flex-1">
                    @if($isRejected)
                        <div class="w-12 h-12 rounded-full bg-rose-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                            <i class="fas fa-times"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-rose-600 uppercase text-center">Ditolak</p>
                    @elseif($isApproved)
                        <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-700 uppercase text-center">Selesai</p>
                    @else
                        <div class="relative w-12 h-12 mb-3">
                            <div class="absolute inset-0 rounded-full bg-amber-400 animate-ping opacity-30"></div>
                            <div class="relative w-12 h-12 rounded-full bg-white text-amber-500 border-2 border-amber-500 flex items-center justify-center font-bold shadow-md step-ring">
                                <i class="fas fa-spinner fa-spin text-sm"></i>
                            </div>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-amber-600 uppercase text-center tracking-tighter">Review</p>
                    @endif
                </div>

                <div class="relative z-10 flex flex-col items-center flex-1">
                    @if($isRejected)
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 border-2 border-rose-500 flex items-center justify-center font-bold mb-3 step-ring">
                            <i class="fas fa-ban"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-rose-700 uppercase text-center">Gagal</p>
                    @elseif($isApproved)
                        <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold mb-3 shadow-lg step-ring">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-emerald-700 uppercase text-center">Aktif</p>
                    @else
                        <div class="w-12 h-12 rounded-full bg-white text-slate-300 border-2 border-slate-200 flex items-center justify-center font-bold mb-3 step-ring text-sm">
                            <i class="fas fa-lock"></i>
                        </div>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase text-center">Hasil</p>
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
                        <h3 class="font-bold text-rose-900 mb-2 text-lg">Pendaftaran Belum Disetujui</h3>
                        <p class="text-sm text-rose-800/80 leading-relaxed mb-5">
                            Mohon maaf, pendaftaran Anda belum memenuhi kriteria mitra BUMDes saat ini. Anda dapat menghubungi Admin via WhatsApp untuk menanyakan alasan penolakan.
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
                                <i class="fab fa-whatsapp text-lg"></i> Hubungi Admin BUMDes
                            </a>
                        </div>
                    </div>
                </div>
            @elseif($isApproved)
                <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-6 flex items-start gap-5">
                    <div class="bg-emerald-100 text-emerald-600 rounded-2xl w-12 h-12 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-check-double text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-emerald-900 mb-2 text-lg">Akun Anda Telah Aktif</h3>
                        <p class="text-sm text-emerald-800/80 leading-relaxed mb-5">
                            Verifikasi pendaftaran Anda telah selesai. Anda kini resmi menjadi Mitra BUMDes Putra Samudra Patimban. Silakan masuk ke dashboard untuk mulai mengelola produk dan usaha Anda.
                        </p>
                        <div class="flex justify-center sm:justify-start">
                            <a href="{{ route('mitra.dashboard') }}" class="inline-flex items-center gap-3 bg-emerald-600 text-white px-8 py-4 rounded-2xl text-base font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all hover:-translate-y-1 group">
                                <i class="fas fa-rocket group-hover:animate-bounce"></i> Masuk ke Dashboard Mitra
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6 flex items-start gap-5">
                    <div class="bg-blue-100 text-blue-600 rounded-2xl w-12 h-12 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <i class="fas fa-info-circle text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-blue-900 mb-2 text-lg">Apa langkah selanjutnya?</h3>
                        <p class="text-sm text-blue-800/80 leading-relaxed mb-5">
                            Tim Admin sedang memverifikasi dokumen Anda (KTP & SKU). Proses ini memakan waktu maksimal <strong>1x24 jam</strong>. Kami akan mengirimkan notifikasi ke email Anda jika akun telah aktif.
                        </p>
                        <div class="flex justify-center sm:justify-start">
                            <button onclick="window.location.reload();" class="inline-flex items-center gap-3 bg-white border border-blue-200 text-blue-700 px-6 py-3 rounded-2xl text-sm font-bold shadow-sm hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all">
                                <i class="fas fa-sync-alt animate-spin-slow"></i> Refresh Status
                            </button>
                        </div>
                    </div>
                </div>
            @endif

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
