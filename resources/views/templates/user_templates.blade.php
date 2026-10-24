<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMP Negeri 1 Pulau Derawan')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Panggil CSS Global dan Landing -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <a href="/" class="nav-brand">
            <img src="{{ asset('images/logo_smp.png') }}" alt="Logo">
            <span>SMPN 1 Pulau Derawan</span>
        </a>
        <div class="nav-links">

            <a href="{{ route('logout') }}" class="btn-nav-login" id="tombol_login">Logout</a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="landing_wrapper">
        <!-- BACKGROUND ANIMASI -->
        <div class="bg-animation">
            <span></span><span></span><span></span><span></span><span></span>
        </div>
        @yield('content')
    </div>
    <script>
        // Efek Navbar saat di-scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
