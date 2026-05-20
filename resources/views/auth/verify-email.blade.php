<x-guest-layout>
    <style>
        .verify-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 20px;
            text-align: center;
            color: #ffffff;
        }

        .verify-text {
            font-size: 14px;
            line-height: 1.6;
            color: #e0e0e0;
            text-align: center;
            margin-bottom: 30px;
        }

        .status-message {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            color: #34d399;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-verify {
            width: 100%;
            background-color: #8a9a5b;
            border: 2px solid #000000;
            border-radius: 8px;
            padding: 12px;
            font-size: 15px;
            font-weight: 700;
            color: #000000;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .btn-verify:hover {
            background-color: #9cb066;
            transform: translateY(-2px);
        }

        .btn-logout {
            display: block;
            text-align: center;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            text-decoration: underline;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: opacity 0.2s;
        }

        .btn-logout:hover {
            opacity: 0.8;
        }
    </style>

    <h1 class="verify-title">Verifikasi Email</h1>

    <div class="verify-text">
        Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan ulang.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="status-message">
            Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
        </div>
    @endif

    <div class="mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-verify">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                Keluar (Log Out)
            </button>
        </form>
    </div>
</x-guest-layout>
