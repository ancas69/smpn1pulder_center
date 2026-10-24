@extends('templates.public_templates')

@section('title', 'Beranda - SMP Negeri 1 Pulau Derawan')

@section('content')



    <!-- HERO SECTION -->
    <section class="hero" id="beranda">
        <div class="hero-content">
            <h1>Selamat Datang di Portal Resmi SMP Negeri 1 Pulau Derawan</h1>
            <p>Mewujudkan generasi yang CERDAS (Cerdas, Religius, Disiplin, Aktif, dan Santun) berbasis teknologi dan
                berwawasan lingkungan bahari.</p>

            <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn-hero-primary">
                    Masuk ke Sistem <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
                <a href="#fitur" class="btn-hero-outline">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- FITUR SECTION -->
    <section class="features" id="fitur">
        <h2 class="section-title">Layanan & Informasi</h2>


        <div class="feature-grid">
            <!-- Card 1 -->
            <div class="feature-card" id="elearning">
                <div class="feature-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3>E-Learning</h3>
                <p>Akses materi pelajaran, tugas, dan ujian secara online dengan mudah kapan saja dan di mana saja.</p>
            </div>

            <!-- Card 2 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3>Portal Siswa & Guru</h3>
                <p>Platform terintegrasi untuk memantau nilai, kehadiran, dan informasi akademik secara *real-time*.</p>
            </div>

            <!-- Card 3 -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <h3>Berita Sekolah</h3>
                <p>Dapatkan informasi terbaru seputar kegiatan, prestasi, dan pengumuman penting dari sekolah.</p>
            </div>
        </div>
    </section>

    <!-- FOOTER SEDERHANA -->
    <footer
        style="padding: 30px; text-align: center; border-top: 1px solid var(--border); color: var(--text-muted); font-size: 14px; position: relative; z-index: 1;">
        <p>&copy; {{ date('Y') }} SMP Negeri 1 Pulau Derawan. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>


@endsection
