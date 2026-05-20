<x-guest-layout>
    <h1 class="auth-title">Registrasi<br>Sebagai</h1>

    <a href="{{ route('register.mitra') }}" class="btn-auth-action">
        <i class="far fa-handshake"></i> Daftar Mitra
    </a>

    <a href="{{ route('register.pelanggan') }}" class="btn-auth-action">
        <i class="far fa-user-circle"></i> Pelanggan
    </a>
    <style>
        /* CSS khusus yang hanya ada di halaman ini */
        .guide-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #e0e0e0;
            text-decoration: none;
            font-size: 13px;
            margin-top: 20px;
            margin-bottom: 40px;
            transition: color 0.3s ease;
        }
        .guide-link i { font-size: 24px; color: #f4f1de; }
        .guide-link:hover i { transform: translateX(3px); transition: transform 0.3s ease; }
    </style>

    <a href="{{route('panduan') }}" class="guide-link">
        Panduan Pendaftaran <i class="fas fa-arrow-circle-right"></i>
    </a>

    <div class="footer-text">
        Sudah punya akun?
        <a href="{{ route('login') }}">Login</a>
    </div>
</x-guest-layout>
