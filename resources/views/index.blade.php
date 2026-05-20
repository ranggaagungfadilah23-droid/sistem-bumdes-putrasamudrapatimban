<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('asset/img/logoBumdes.png') }}" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUMDes Putra Samudra Patimban</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    {{-- 1. HERO SECTION --}}
  <div class="hero-bg" id="beranda" style="background-image: url('{{ asset('asset/img/berandabg.jpg') }}');">
        <div class="overlay"></div>
        <header id="main-navbar">
    <div class="top-left">
        <div class="brand">PUTRA SAMUDRA PATIMBAN</div>
        <div class="clock" id="live-clock">00:00:00</div>
        <div class="date" id="live-date">Memuat tanggal...</div>
    </div>
    <div class="nav-links">
      <a href="#beranda" class="glass-btn">Beranda</a>
        <a href="#tentang" class="glass-btn">Tentang</a>
        <a href="{{ route('login') }}" class="glass-btn">Login</a>
        <a href="{{ route('register') }}" class="glass-btn">Register</a>
    </div>
</header>

        <div class="main-content">
            <div class="badge">Badan Usaha Milik Desa</div>
            <h1 class="title-main">PUTRA SAMUDRA<br>PATIMBAN</h1>
            <div class="action-btns">
                <a href="{{ route('register.mitra') }}" class="glass-btn">Daftar menjadi Mitra</a>
                <a href="#kategori" class="btn-primary">Jelajahi Produk</a>
            </div>
        </div>
    </div>

    {{-- 2. SECTION TENTANG --}}
    <section id="tentang" style="padding: 100px 8%; background-color: #ffffff;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 60px;">
            <div style="flex: 1; min-width: 320px;">
                <div style="display: inline-block; padding: 8px 20px; background: #f1f5f9; color: #8a9a5b; border-radius: 50px; font-size: 12px; font-weight: 800; text-transform: uppercase; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                    Profil BUMDes
                </div>
                <h2 style="font-size: 42px; font-weight: 800; color: #1e293b; margin-bottom: 25px; line-height: 1.1; letter-spacing: -1px;">
                    Mendorong Ekonomi Desa Melalui Kemandirian
                </h2>
                <p style="color: #64748b; line-height: 1.8; font-size: 16px; margin-bottom: 30px;">
                    BUMDes Putra Samudra Patimban merupakan penggerak ekonomi Desa Patimban yang mengelola berbagai potensi lokal. Kami menjembatani para mitra usaha (UKM) dengan pelanggan untuk menciptakan ekosistem perdagangan yang sehat, transparan, dan menguntungkan bagi seluruh warga desa.
                </p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="padding: 25px; border-radius: 24px; background: #f8fafc; border: 1px solid #e2e8f0;">
                        <i class="fas fa-bullseye" style="color: #8a9a5b; font-size: 24px; margin-bottom: 15px;"></i>
                        <h4 style="font-weight: 800; color: #1e293b; margin-bottom: 8px;">Visi Kami</h4>
                        <p style="font-size: 13px; color: #64748b; line-height: 1.5;">Menjadi pilar ekonomi desa yang modern dan berdaya saing global.</p>
                    </div>
                    <div style="padding: 25px; border-radius: 24px; background: #f8fafc; border: 1px solid #e2e8f0;">
                        <i class="fas fa-users" style="color: #3b82f6; font-size: 24px; margin-bottom: 15px;"></i>
                        <h4 style="font-weight: 800; color: #1e293b; margin-bottom: 8px;">Misi Kami</h4>
                        <p style="font-size: 13px; color: #64748b; line-height: 1.5;">Pemberdayaan mitra lokal dan digitalisasi layanan publik desa.</p>
                    </div>
                </div>
            </div>
            <div style="flex: 1; min-width: 320px; position: relative;">
                <div style="position: absolute; top: -20px; left: -20px; width: 100px; height: 100px; background: #8a9a5b; border-radius: 24px; z-index: -1;"></div>
                <img src="{{ asset('asset/img/berandabg.jpg') }}" alt="Profil BUMDes" style="width: 100%; border-radius: 32px; box-shadow: 0 30px 60px rgba(0,0,0,0.12); object-fit: cover;">
            </div>
        </div>
    </section>

    {{-- 3. SECTION KATEGORI UTAMA --}}
    <section id="kategori" style="padding: 100px 8%; background-color: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
        <div style="text-align: center; max-width: 700px; margin: 0 auto 60px;">
            <h2 style="font-size: 36px; font-weight: 800; color: #1e293b; margin-bottom: 15px;">Layanan & Katalog</h2>
            <p style="color: #64748b; font-size: 16px;">Pilih kategori kebutuhan Anda. Kami menyediakan berbagai produk fisik dan layanan jasa profesional dari warga desa.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
            <div style="background: white; border-radius: 32px; border: 1px solid #e2e8f0; padding: 40px; display: flex; flex-direction: column; align-items: flex-start; transition: 0.4s ease;" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-10px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <div style="width: 80px; height: 80px; background: #eff6ff; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px;">
                    <i class="fas fa-box-open" style="font-size: 35px; color: #3b82f6;"></i>
                </div>
                <h4 style="font-weight: 800; font-size: 24px; color: #1e293b; margin-bottom: 12px;">Katalog Produk</h4>
                <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">Dapatkan produk sembako, kuliner khas Patimban, olahan laut, hingga kerajinan tangan kreatif langsung dari produsen desa.</p>
                <a href="{{ route('login') }}" style="font-weight: 800; color: #3b82f6; text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    Eksplor Produk <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div style="background: white; border-radius: 32px; border: 1px solid #e2e8f0; padding: 40px; display: flex; flex-direction: column; align-items: flex-start; transition: 0.4s ease;" onmouseover="this.style.borderColor='#10b981'; this.style.transform='translateY(-10px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                <div style="width: 80px; height: 80px; background: #ecfdf5; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px;">
                    <i class="fas fa-tools" style="font-size: 35px; color: #10b981;"></i>
                </div>
                <h4 style="font-weight: 800; font-size: 24px; color: #1e293b; margin-bottom: 12px;">Layanan Jasa</h4>
                <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">Butuh bantuan ahli? Cari layanan bengkel, jasa cukur, perbaikan rumah, hingga sewa alat penangkapan ikan di sini.</p>
                <a href="{{ route('login') }}" style="font-weight: 800; color: #10b981; text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    Cari Layanan <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
{{-- 4. SECTION PREVIEW PRODUK --}}
    <section style="padding: 100px 8%; background-color: #ffffff;">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 style="font-size: 32px; font-weight: 800; color: #1e293b;">Pilihan Produk & Jasa Desa</h2>
                <p style="color: #64748b;">Dukung UMKM Desa dengan membeli produk asli lokal.</p>
            </div>
        </div>

        <div class="row">
            {{-- Loop Jasa --}}
            @foreach($jasas as $jasa)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ $jasa->gambar ? asset('storage/' . $jasa->gambar) : asset('asset/img/default-product.jpg') }}"
                             class="card-img-top" alt="{{ $jasa->nama_jasa }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $jasa->nama_jasa }}</h5>
                            <p class="card-text text-primary fw-bold">Rp {{ number_format($jasa->harga, 0, ',', '.') }}</p>
                            @auth
                                <a href="/transaksi/jasa/{{ $jasa->id }}" class="btn btn-primary w-100">Beli Sekarang</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success w-100">Beli Sekarang</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Loop Produk --}}
            @foreach($produks as $produk)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('asset/img/default-product.jpg') }}"
                             class="card-img-top" alt="{{ $produk->nama_produk }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $produk->nama_produk }}</h5>
                            <p class="card-text text-primary fw-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            @auth
                                <a href="/transaksi/produk/{{ $produk->id }}" class="btn btn-primary w-100">Beli Sekarang</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success w-100">Beli Sekarang</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 5. FOOTER --}}
    <footer style="padding: 40px 8%; background-color: #1e293b; color: #94a3b8; text-align: center; font-size: 14px; border-top: 1px solid rgba(255,255,255,0.05);">
        &copy; 2026 BUMDes Putra Samudra Patimban. Seluruh hak cipta dilindungi. <br>
        <span style="font-size: 12px; opacity: 0.5; margin-top: 10px; display: block;">Dikembangkan oleh Regga Vision</span>
    </footer>

    {{-- SCRIPT JAM & SMOOTH SCROLL --}}
    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('live-clock').innerText = `${h} : ${m} : ${s}`;
            document.getElementById('live-date').innerText = now.toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
        }
        setInterval(updateClock, 1000);
        updateClock();

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });


      const navbar = document.getElementById('main-navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
});
    </script>
</body>
</html>
