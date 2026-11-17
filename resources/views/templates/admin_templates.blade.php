<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard • Desktop</title>
  <!-- Google Font & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}"
</head>
<body>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
<div class="app-container" id="appContainer">
  <!-- SIDEBAR MODERN (TEMA PENDIDIKAN) -->
  <aside class="sidebar">
    <div class="logo-area">
      <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="logo-text">Edu<span>Admin</span></div>
    </div>

    <nav class="nav-menu">
      <a href="#" class="nav-item active"><i class="fas fa-chart-pie"></i> Dashboard</a>
      <a href="#" class="nav-item"><i class="fas fa-user-group"></i> Pengguna</a>
      <a href="#" class="nav-item"><i class="fas fa-user-graduate"></i> Siswa</a>
      <a href="#" class="nav-item"><i class="fas fa-chalkboard-user"></i> PTK</a>
      <a href="#" class="nav-item"><i class="fas fa-calendar-check"></i> Daftar Hadir</a>
      <a href="#" class="nav-item"><i class="fas fa-book-open"></i> Bimbel</a>
      <a href="#" class="nav-item"><i class="fas fa-futbol"></i> Ekstrakurikuler</a>
      <a href="#" class="nav-item"><i class="fas fa-bell"></i> Notifikasi</a>
    </nav>

    <div style="padding: 0 16px; margin-bottom: 10px;">
        <!-- Hapus onclick, ganti dengan id -->
        <a href="#" class="nav-item" id="btn-logout" style="color: var(--danger);">
            <i class="fas fa-sign-out-alt" style="color: var(--danger);"></i> Keluar / Logout
        </a>
    </div>

    <!-- TEMA WARNA -->
    <div class="theme-section">
      <div class="theme-label"><i class="fas fa-palette"></i> Tema Warna</div>
      <div class="theme-options" id="themeOptions">
        <div class="theme-dot active" data-theme-val="default" title="Default (Indigo)"></div>
        <div class="theme-dot" data-theme-val="blue" title="Biru"></div>
        <div class="theme-dot" data-theme-val="green" title="Hijau"></div>
        <div class="theme-dot" data-theme-val="purple" title="Ungu"></div>
        <div class="theme-dot" data-theme-val="rose" title="Rose"></div>
      </div>
    </div>
  </aside>

  <!-- KONTEN UTAMA (CONTOH DATA PENDIDIKAN) -->
  <main class="main-content">
    @yield('content')
  </main>
</div>

<script src="{{ asset('js/admin_global.js')}}"></script>
<script src="{{ asset('js/admin_dashboard.js')}}"></script>
</body>
</html>