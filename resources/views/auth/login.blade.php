<x-guest-layout :centered="true">

    {{-- Status session (misal: setelah reset password) --}}
    <x-auth-session-status class="status-success" :status="session('status')" />

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="status-error">
            <i class="fas fa-circle-exclamation" style="margin-right:6px;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Logo --}}
    <div class="logo-badge">
        <img src="{{ asset('asset/img/logoBumdes.png') }}"
             onerror="this.src='https://via.placeholder.com/54x54?text=B'"
             alt="Logo BUMDes">
    </div>

    {{-- Judul --}}
    <h2 class="card-title">Masuk ke Akun</h2>
    <p class="card-sub">Selamat datang di platform kami</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="field-group">
            <label class="field-label" for="email">Alamat Email</label>
            <div class="field-inner">
                <i class="fas fa-envelope field-icon"></i>
                <input id="email"
                       class="field-input"
                       type="email"
                       name="email"
                       placeholder="nama@email.com"
                       value="{{ old('email') }}"
                       required autofocus autocomplete="username">
            </div>
        </div>

        {{-- Password --}}
        <div class="field-group">
            <label class="field-label" for="password">Kata Sandi</label>
            <div class="field-inner">
                <i class="fas fa-lock field-icon"></i>
                <input id="password"
                       class="field-input"
                       type="password"
                       name="password"
                       placeholder="Masukkan kata sandi"
                       required autocomplete="current-password">
                <button type="button" class="toggle-eye" id="togglePassword" aria-label="Tampilkan sandi">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        {{-- Lupa sandi --}}
        @if (Route::has('password.request'))
            <div class="forgot-row">
                <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
            </div>
        @endif

        {{-- Tombol masuk --}}
        <button type="submit" class="btn-primary">
            <span>Masuk Sekarang</span>
            <i class="fas fa-arrow-right" style="font-size:12px;"></i>
        </button>

        {{-- Divider --}}
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">atau</span>
            <div class="divider-line"></div>
        </div>

        {{-- Google --}}
        <a href="{{ route('auth.google.redirect') }}" class="btn-google">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google">
            <span>Lanjutkan dengan Google</span>
        </a>

        {{-- Daftar --}}
        <p class="card-footer">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar gratis</a>
        </p>

    </form>

    @push('js')
    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const pwInput   = document.getElementById('password');

        if (toggleBtn && pwInput) {
            toggleBtn.addEventListener('click', function () {
                const isHidden = pwInput.type === 'password';
                pwInput.type   = isHidden ? 'text' : 'password';
                const icon     = this.querySelector('i');
                icon.classList.toggle('fa-eye',       !isHidden);
                icon.classList.toggle('fa-eye-slash',  isHidden);
                this.style.color = isHidden ? '#38bdf8' : '';
            });
        }
    </script>
    @endpush

</x-guest-layout>
