# Catatan Pemeriksaan Kode & Edit File

Dokumen ini berisi lesson learned dari sesi kerja Bersama, khususnya terkait
edit terhadap file JavaScript, PHP, dan file lainnya. Tujuan: mencegah
kode yang sudah ada ikut terhapus saat melakukan perubahan.

---

## 1. Masalah yang Terjadi

### 1.1. `write_file` mengganti seluruh file

Saat ingin menambah/modifikasi sebagian kecil dalam file besar (misalnya
`public/js/admin_pengguna.js`), banyak kali dilakukan `write_file` yang
mengganti **seluruh isi file** dari nol.

**Akibat:**

- Semua fungsi yang **tidak disebutkan** dalam `new_string` ikut terhapus.
- Beberapa fungsi penting (misal `render`, `renderStats`, `escapeHtml`,
  `getFilteredUsers`, `roleLabel`, `roleClass`, `statusClass`, `statusLabel`,
  `deleteUser`) lenyap tanpa bubble error.
- File jadi partial – hanya sisah bagian yang baru ditulis.

**Contoh:**

```
Tulis ulang admin_pengguna.js dari ingatan → file jadi 450 baris, padahal
seharusnya 503 baris. Fungsi "render()", "renderStats()", "renderTable()",
"escapeHtml()", "getFilteredUsers()", "roleLabel()", "roleClass()",
"statusClass()", "statusLabel()", "deleteUser()" tidak ikut disalin.
```

### 1.2. `patch` keluar dari blok yang tidak mewakili file

Saat `patch` ditulis manual dari output bacaan yang kurang lengkap, blok yang
dicocokkan (`old_string`) kadang **tidak sama persis** dengan isi file saat itu.
Apabila `old_string` tidak cocok, operasi `patch` gagal, atau malah menulis
kode yang salah ke lokasi yang berbeda.

**Gambaran:**

```
Baca file (baris 1–60) → patch (blok 20–30) → hasilnya berubah salah baris,
karena blok yang dicocokkan tidak mencerminkan isi sebenarnya.
```

### 1.3. Tidak pernah verifikasi setelah edit

Sering kali langsung lanjut ke operasi berikutnya tanpa mengeksekusi command
verifikasi:

```bash
node -c public/js/admin_pengguna.js
php -l app/Http/Controllers/AdminController.php
```

Melanggar aturan ini membuat kesalahan struktural tidak ketahuan sampai
beberapa operasi kemudian, saat fungsi yang hilang baru dijumpai pengguna.

---

## 2. Aturan & Praktik Sudah Dijalankan

### 2.1. Baca dulu, baru edit

- **Sebelum** memanggil `patch` atau `write_file`, **baca file** tersebut.
- Cek kelengkapan isi, baris, struktur, dan fungsi yang akan dimodifikasi.
- Catat **persis** `old_string` yang cocok.

### 2.2. Prefer `patch` terhadap `write_file`

- Untuk perubahan kecil → `patch` pilih.
- Hanya padukan fungsi / blok yang perlu diubah. Jangan tulis ulang seluruh
  file kecuali memang permintaan awal yang menarget seluruh konten.

### 2.3. Verifikasi setelah setiap koreksi

- JavaScript → `node -c <file.js>`
- PHP → `php -l <file.php>`
- Konfirmasi fungsi yang perlu ada masih ada → `grep -n "function <name>" <file>`

### 2.4. Saat mengerjakan banyak bagian, gabungkan ke satu patch

- Lebih baik `patch` satu blok yang mencakup semua perubahan daripada
  serangkaian `patch` kecil yang saling tumpang tindih.

---

## 3. Checklist Sebelum & Sesudah Edit

### Sebelum memulai

- [ ] Baca file yang akan dimodifikasi.
- [ ] Catat baris awal, jumlah baris, dan fungsi yang ada.
- [ ] Identifikasi target `old_string` yang unik & akurat.
- [ ] Keluarkan skema/konvensi penulisan (indents, tanda kutip, spasi).

### Saat melakukan edit

- [ ] Pastikan `old_string` cocok dengan isi file (bukan dari ingatan).
- [ ] Setelah `patch` selesai, baca kembali `summary` (diff).
- [ ] Cek fungsi kunci yang relevan masih ada (grep).
- [ ] Verifikasi sintaks (node -c / php -l).

### Setelah perbaikan

- [ ] Baca file yang diperbaiki untuk konfirmasi perubahan.
- [ ] Jalankan percobaan (test fungsi / build / lint).
- [ ] Simpan catatan: jika tool sudah diwajibkan, versi/sha file.

---

## 4. Regulasi Harian

1. **Selalu baca dulu.** Jangan menulis kode tanpa membaca file terkait.
2. **Gunakan `patch` untuk perubahan kecil.** Hanya ubah blok yang diperlukan.
3. **Verifikasi setelah setiap edit.** `node -c` / `php -l`.
4. **Gunakan `grep` untuk memastikan fungsi yang diminta masih ada.**
5. **Jangan pernah mengubah kode dari hafalan.** Selalu berdasarkan teks asli.

---

## 5. Kegagalan Autopilot

Autopilot dapat menghasilkan perilaku yang tidak diinginkan apabila:

- tugas diberikan secara terpisah tanpa konteks penuh;
- file besar dimodifikasi tanpa dulu dibaca keseluruhan;
- verifikasi sintaks tidak dilakukan;
- instruksi "cuo aja" / "saja mungkin" / "kita coba" dipakai tanpa dasar.

Oleh karena itu, aturan terpenting yang dipegang: **baca sebelum menulis**,
dan **verifikasi setiap perubahan**.

---

## 6. Rujukan

- Laravel Blade templating conventions.
- JavaScript ES6 module / IIFE patterns.
- PHP Laravel controller conventions.
- File system exploration (`read_file`, `search_files`, `terminal`).
- Client-side rendering & event delegation patterns.

## 7. Optimasi Token & Konteks (Hemat Biaya)

Membaca seluruh file berukuran besar secara berulang-ulang menghabiskan token
yang sangat banyak. Untuk menghemat token namun tetap aman, ikuti aturan ini:

### 7.1. Gunakan Pencarian Spesifik (Grep/Search) daripada Baca Seluruh File

- **Jangan** langsung membaca 1000 baris file jika hanya ingin mengubah 1 fungsi.
- Gunakan `grep -n "function nama_fungsi" <file>` atau `search_files` untuk
  menemukan nomor barisnya.
- Baca file **hanya pada rentang baris tersebut** (misal: baris 150-200).

### 7.2. Gunakan `patch` Berbasis Konteks (Minimalis)

- Saat menggunakan `patch`, buat `old_string` sependek mungkin (cukup 2-3 baris
  unik yang mewakili blok tersebut), asalkan dijamin **unik** (tidak muncul
  di tempat lain).
- Jangan menyalin 50 baris hanya untuk mengubah 1 baris di tengahnya.

### 7.3. Ringkasan File (File Outline)

- Jika file sangat besar (misal > 500 baris) dan AI perlu memahami strukturnya,
  mintalah AI untuk membuat _outline_ (daftar fungsi/class saja) terlebih dahulu
  menggunakan grep, alih-alih memuat seluruh isi file ke dalam memori.

### 7.4. Batasi Output Verifikasi

- Saat menjalankan `node -c` atau `php -l`, pastikan hanya menampilkan output
  jika terjadi _error_. Jika sukses, cukup abaikan output agar tidak memakan
  token jawaban.

### 7.5. Hindari "Membaca Ulang" File yang Sama

- Jika AI baru saja membaca file di langkah sebelumnya, jangan minta AI membaca
  ulang file yang sama di langkah berikutnya kecuali ada perubahan dari _tools_
  lain. Simpan hasil bacaan di konteks.

---
