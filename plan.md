# Rencana Aksi Proyek SMPN 1 Pulder Center

Dokumen ini berisi rencana pengembangan dan perbaikan sistem aplikasi SMPN 1 Pulder Center (Sistem Ujian dan Manajemen Sekolah).

---

## Fase 1: Perbaikan Masalah Pengiriman Email (Prioritas Utama)
- Tujuan: Memastikan fitur pengiriman email (untuk buka blokir akun / unlock account) berfungsi dengan baik via SMTP/Gmail.
- Langkah Kerja:
  1. Periksa dan sesuaikan konfigurasi MAIL_* pada file .env (MAIL_MAILER=smtp, host, port, username, password).
  2. Jika menggunakan Gmail, pastikan menggunakan App Password (bukan password akun Google utama) dan port yang sesuai (587 TLS atau 465 SSL).
  3. Uji pengiriman email menggunakan Laravel Tinker atau trigger form blokir login.
  4. Tangani error log pada storage/logs/laravel.log jika terjadi kegagalan koneksi SMTP.

---

## Fase 2: Pengembangan Modul Ujian dan Bank Soal (Core Sistem)
- Tujuan: Membangun fondasi sistem ujian online/offline yang direncanakan.
- Langkah Kerja:
  1. Buat migrasi database untuk tabel exams (ujian), questions (soal), dan exam_packages (paket soal siswa).
  2. Buat Model dan Controller terkait manajemen ujian oleh guru/admin.
  3. Rancang sistem pengunduhan paket soal terenkripsi untuk siswa.

---

## Fase 3: Dashboard Admin / Guru & Rekapitulasi Nilai
- Tujuan: Memudahkan guru dalam memantau jalannya ujian dan merekap nilai siswa.
- Langkah Kerja:
  1. Buat interface dashboard admin/guru.
  2. Fitur pemantauan status pengerjaan ujian siswa secara real-time / berkala.
  3. Fitur rekapitulasi nilai dan ekspor hasil ujian (format CSV/Excel).
  4. Log pelanggaran (aktivitas mencurigakan / keluar dari area ujian).

---

## Fase 4: Pengujian, Docker, dan Deployment
- Tujuan: Memastikan aplikasi stabil, aman, dan siap digunakan di lingkungan sekolah.
- Langkah Kerja:
  1. Uji skenario keamanan login (blokir setelah 3x salah sandi, verifikasi token email unlock).
  2. Uji coba container Docker menggunakan compose.yaml.
  3. UAT (User Acceptance Testing) bersama guru dan pihak sekolah.
