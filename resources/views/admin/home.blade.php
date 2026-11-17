@extends('templates.admin_templates')

@section('content')
    <!-- HEADER -->
    <div class="header-area">
      <div class="page-title">
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, Admin Sekolah! Berikut ringkasan data pendidikan hari ini.</p>
      </div>
      <div class="header-actions">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Cari siswa, PTK, atau kelas...">
        </div>
        <div class="avatar">
          <div class="avatar-img">AD</div>
          <span class="avatar-name">Admin</span>
        </div>
      </div>
    </div>

    <!-- STATS CARDS (Disesuaikan dengan konteks pendidikan) -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-value">1,248</div>
        <div class="stat-label">Total Siswa</div>
        <div class="stat-trend"><i class="fas fa-arrow-up"></i> +32 siswa baru</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-chalkboard-user"></i></div>
        <div class="stat-value">86</div>
        <div class="stat-label">PTK (Guru & Staf)</div>
        <div class="stat-trend"><i class="fas fa-arrow-up"></i> +5 bulan ini</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-value">94.5%</div>
        <div class="stat-label">Kehadiran Hari Ini</div>
        <div class="stat-trend"><i class="fas fa-arrow-up"></i> +2.1%</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-star"></i></div>
        <div class="stat-value">4.8</div>
        <div class="stat-label">Rating Bimbel</div>
        <div class="stat-trend"><i class="fas fa-arrow-up"></i> +0.2</div>
      </div>
    </div>

    <!-- AKTIVITAS TERBARU (Disesuaikan dengan konteks pendidikan) -->
    <div class="section-card">
      <div class="section-header">
        <h3>Aktivitas Terbaru</h3>
        <span class="badge">Lihat semua</span>
      </div>
      <div class="activity-list">
        <div class="activity-item">
          <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
          <div class="activity-detail">
            <div class="activity-title">Siswa baru mendaftar</div>
            <div class="activity-time">2 menit yang lalu • Rina Wulandari (Kelas X)</div>
          </div>
          <span class="activity-status status-success">Sukses</span>
        </div>
        <div class="activity-item">
          <div class="activity-icon"><i class="fas fa-clipboard-list"></i></div>
          <div class="activity-detail">
            <div class="activity-title">Daftar hadir kelas XI IPA 2 telah diisi</div>
            <div class="activity-time">15 menit yang lalu • 32 siswa hadir</div>
          </div>
          <span class="activity-status status-success">Sukses</span>
        </div>
        <div class="activity-item">
          <div class="activity-icon"><i class="fas fa-book-open"></i></div>
          <div class="activity-detail">
            <div class="activity-title">Jadwal bimbel Matematika ditambahkan</div>
            <div class="activity-time">42 menit yang lalu • Senin, 14:00 WIB</div>
          </div>
          <span class="activity-status status-warning">Baru</span>
        </div>
        <div class="activity-item">
          <div class="activity-icon"><i class="fas fa-futbol"></i></div>
          <div class="activity-detail">
            <div class="activity-title">Pendaftaran ekstrakurikuler Futsal dibuka</div>
            <div class="activity-time">1 jam yang lalu • Kuota 20 siswa</div>
          </div>
          <span class="activity-status status-warning">Menunggu</span>
        </div>
        <div class="activity-item">
          <div class="activity-icon"><i class="fas fa-bell"></i></div>
          <div class="activity-detail">
            <div class="activity-title">Pengumuman: Rapat orang tua siswa</div>
            <div class="activity-time">2 jam yang lalu • Sabtu, 09:00 WIB</div>
          </div>
          <span class="activity-status status-danger">Penting</span>
        </div>
      </div>
    </div>
@endsection