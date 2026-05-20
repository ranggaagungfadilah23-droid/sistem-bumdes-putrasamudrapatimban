{{-- foot.blade.php --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@stack('js')

<script>
    // Paksa preloader hilang setelah 3 detik apapun yang terjadi
    function hidePreloader() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.style.opacity = '0';
            preloader.style.transition = 'opacity 0.4s';
            setTimeout(() => preloader.style.display = 'none', 400);
        }
    }

    window.addEventListener('load', hidePreloader);

    // Fallback: paksa hilang setelah 3 detik
    setTimeout(hidePreloader, 3000);
</script>
