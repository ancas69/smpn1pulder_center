# Rencana Aksi Proyek SMPN 1 Pulder Center

Dokumen ini berisi rencana pengembangan dan pembaharuan sistem aplikasi SMPN 1 Pulder Center yang mencakup Fitur Absensi QR Code, Notifikasi WhatsApp Wali, Sistem Bimbel Guru, Pembayaran Application, dan Manajemen Multi-Role User (Admin, Guru, Murid, Wali).

---

## 🎯 Ringkasan Arsitektur & Peran Pengguna (User Roles)
Sistem memiliki 4 peran utama:
1. **Admin**: Pengelolaan master data (siswa, guru, wali), konfigurasi QR Code, integrasi WA Gateway & Payment Gateway, serta rekapitulasi umum.
2. **Guru**: Manajemen absensi/scanning, pembuatan program Bimbel & penentuan tarif bimbel, manajemen materi/kegiatan.
3. **Murid**: Akses kartu QR Code digital, pendaftaran bimbel, melihat jadwal/kegiatan, dan status absensi.
4. **Wali Murid**: Menerima notifikasi otomatis WhatsApp atas aktivitas & absensi anak, serta pembayaran tagihan bimbel/sekolah.

---

## 📌 Fase 1: Fondasi Sistem, Auth & Manajemen User Multi-Role
- **Tujuan**: Menyiapkan struktur role-permission dan relasi antar pengguna (Admin, Guru, Murid, Wali).
- **Langkah Kerja**:
  1. **Autentikasi & Authorization**:
     - Tambahkan enum/role pada tabel `users` atau paket RBAC (Admin, Guru, Murid, Wali).
     - Perbaiki & mantapkan fitur autentikasi yang sudah ada (Google Auth & Email Account Unlock/Verifikasi).
  2. **Relasi & Master Data**:
     - Tabel `students` (NIS/NISN, kelas, wali_id, qr_code_token).
     - Tabel `guardians` / `wali` (nama, nomor_wa, alamat).
     - Tabel `teachers` (NIP, bidang studi).
  3. **Generasi Kartu Student QR Code**:
     - Generator QR Code unik per siswa yang dapat diunduh/dicetak sebagai kartu pelajar.

---

## 📌 Fase 2: Sistem Absensi Online QR Code & WhatsApp Gateway
- **Tujuan**: Pencatatan presensi siswa via scan kartu QR Code dan pengiriman notifikasi instan ke WhatsApp Wali Murid.
- **Langkah Kerja**:
  1. **Modul Absensi QR Code**:
     - Skema tabel `attendances` (student_id, date, time_in, time_out, status [hadir, izin, sakit, alfa], scanned_by).
     - Interface Web Scanner QR Code (kamera hp/webcam) untuk Guru/Petugas/Gate.
  2. **Integrasi WhatsApp Gateway**:
     - Integrasi API WhatsApp Service (Fonnte / Wablas / Whacenter / Custom Gateway).
     - Modul Helper/Service Notification WA (`WAService`).
  3. **Notifikasi Otomatis Aktivitas & Presensi**:
     - Trigger pesan WA otomatis saat siswa berhasil dipresensi masuk/pulang ("Ananda [Nama] telah hadir di sekolah pukul 07:15 WITA").
     - Trigger pesan WA untuk ketidakhadiran (alfa/sakit/izin) dan pengumuman aktivitas sekolah.

---

## 📌 Fase 3: Modul Bimbingan Belajar (Bimbel) oleh Guru
- **Tujuan**: Memungkinkan guru membuka kelas bimbel mandiri dengan penentuan tarif sendiri.
- **Langkah Kerja**:
  1. **Manajemen Kelas Bimbel**:
     - Skema tabel `bimbel_classes` (teacher_id, title, description, price, schedule, max_students, status).
     - Dashboard Guru untuk membuat, mengubah, dan memantau program bimbel serta harganya.
  2. **Pendaftaran Bimbel oleh Murid**:
     - Catalog/Katalog Bimbel yang tersedia untuk dipilih murid.
     - Skema tabel `bimbel_enrollments` (student_id, bimbel_class_id, status_payment, joined_at).
  3. **Manajemen Aktivitas Bimbel**:
     - Absensi khusus sesi bimbel dan pencatatan perkembangan siswa.
     - Notifikasi WA ke wali murid jika murid mengikuti sesi bimbel.

---

## 📌 Fase 4: Sistem Pembayaran Aplikasi (Payment Gateway)
- **Tujuan**: Memproses pembayaran bimbel dan biaya sekolah secara transparan & otomatis melalui aplikasi.
- **Langkah Kerja**:
  1. **Integrasi Payment Gateway**:
     - Integrasi Midtrans / Xendit / Tripay (mendukung QRIS, E-Wallet, Transfer Bank, VA, Alfamart/Indomaret).
  2. **Manajemen Tagihan & Invoice**:
     - Skema tabel `invoices` & `payments` (user_id, amount, payment_type, payment_status, snap_token/checkout_url, paid_at).
     - Generate Invoice pembayaran bimbel otomatis setelah registrasi.
  3. **Notifikasi WA Status Pembayaran**:
     - Kirim link pembayaran ke WA Wali/Murid.
     - Webhook handler untuk auto-update status pembayaran dan kirim resi ke WA Wali.
  4. **Laporan & Rekapitulasi Keuangan Guru & Admin**:
     - Dashboard pendapatan bimbel untuk masing-masing guru.
     - Rekap transaksi bulanan bagi Admin.

---

## 📌 Fase 5: Dashboard Multi-User, Pengujian & Deployment Docker
- **Tujuan**: Menyatukan seluruh modul dalam UI/UX yang informatif, pengujian komprehensif, dan siap rilis dengan Docker.
- **Langkah Kerja**:
  1. **UI/UX Dashboard Spesifik Role**:
     - Dashboard Admin (Statistik Presensi Total, Keuangan, User Management).
     - Dashboard Guru (Kelola Bimbel, Scan QR, Input Nilai/Aktivitas).
     - Dashboard Murid & Wali (Lihat Kartu QR, Riwayat Presensi, Kelas Bimbel, Tagihan).
  2. **Pengujian (Testing & UAT)**:
     - Uji coba simulasi scan QR Code secara simultan.
     - Uji coba antrean (queue) pengiriman pesan WhatsApp agar tidak kena rate limit.
     - Uji coba callback/webhook Payment Gateway di environment sandbox/production.
  3. **Dockerization & CI/CD**:
     - Optimasi `compose.yaml` (PHP-FPM, Nginx, MySQL/PostgreSQL, Redis, Queue Worker untuk WA & Mail).
