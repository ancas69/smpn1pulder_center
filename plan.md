
# Rencana Proyek: Aplikasi Asesmen SMPN 1 Pulder (Offline Exam + Web Admin)

> Dokumen ini adalah sumber kebenaran tunggal untuk AI agent.
> Agent WAJIB membaca seluruh dokumen ini sebelum mengeksekusi task apa pun.
> Jika ada ambiguitas, agent WAJIB berhenti dan bertanya, bukan mengasumsikan.

---

## 1. Ringkasan Proyek

Sistem ujian dua komponen:

- **Web admin/guru**: mengelola user, bank soal, jadwal ujian, dan mengolah nilai.
- **Aplikasi mobile siswa (Android)**: mengunduh paket soal, menjalankan ujian secara offline, mengunci perangkat selama ujian, menyimpan jawaban lokal, lalu mengirim hasil saat internet dinyalakan.

Prinsip utama: **kurangi permukaan serangan dengan offline, bukan mengejar deteksi sempurna.**

---

## 2. Tujuan dan Non-Tujuan

### Tujuan

- Siswa dapat mengerjakan ujian tanpa internet setelah paket soal terunduh.
- Jawaban tidak hilang jika koneksi putus atau aplikasi tertutup.
- Hasil tidak bisa dikirim/dikerjakan ulang setelah submit.
- Guru dapat melihat status pengerjaan dan nilai di web.

### Non-Tujuan (jangan dikerjakan di versi ini)

- Deteksi sempurna terhadap floating window atau aplikasi lain.
- AI yang mengawasi perilaku siswa secara real-time.
- Dukungan iOS di fase awal.
- Ujian berbasis pengawasan kamera/microphone.

---

## 3. Arsitektur Tingkat Tinggi

[Web Admin/Guru] <--HTTPS--> [Backend API + DB]
				^
				| (unduh paket soal, kirim hasil)
				|
			[Aplikasi Android Siswa]

SQLite lokal (soal terenkripsi, jawaban)

WorkManager (antrean pengiriman)

Kiosk Mode (Lock Task)

ConnectivityMonitor

### Komponen backend

- Auth (siswa, guru, admin) — JWT + refresh token.
- Manajemen ujian: buat ujian, generate paket soal terenkripsi per siswa.
- Endpoint unduh paket: mengembalikan file terenkripsi + kunci sesi (hanya valid selama jendela ujian).
- Endpoint submit hasil: idempoten, menolak duplikat berdasarkan `exam_id + student_id`.
- Manajemen nilai dan ekspor.

### Komponen mobile

- Modul unduh & dekripsi paket soal.
- Modul ujian: timer berbasis waktu server + monotonic clock, auto-save jawaban.
- Modul penguncian: Lock Task Mode + deteksi status koneksi + deteksi overlay (lapisan tambahan).
- Modul penyimpanan: SQLite terenkripsi + status submit yang tahan clear data.
- Modul pengiriman: WorkManager dengan retry dan status terkirim.

---

## 4. Keputusan yang Harus Dikonfirmasi Manusia Sebelum Coding

Agent **tidak boleh** mengasumsikan hal-hal ini. Minta konfirmasi:

1. **Format paket soal**: JSON terenkripsi (AES-GCM) dengan kunci per sesi, atau format lain?
2. **Izin Device Administrator / Kiosk Mode**: apakah aplikasi akan meminta izin ini? Bagaimana penjelasan ke siswa/orang tua?
3. **Kebijakan pelanggaran**: jika siswa menyalakan internet saat ujian, apakah dicatat sebagai pelanggaran yang memengaruhi nilai, atau hanya log?
4. **Batas waktu pengiriman hasil**: berapa lama hasil boleh tertahan di perangkat sebelum dianggap gagal?
5. **Teknologi**: Flutter atau Android native (Kotlin)? Backend: Node.js, Laravel, atau lain?
6. **Kebijakan privasi**: data apa yang dikumpulkan (status koneksi, waktu, device ID) dan berapa lama disimpan?

> **Status keputusan:** [BELUM DIKONFIRMASI]
> Agent harus berhenti di sini sampai keenam poin dijawab.

---

## 5. Fase Kerja

### Fase 0 — Fondasi dan Konfirmasi (1–2 minggu)

- Kumpulkan jawaban atas 6 keputusan di atas.
- Siapkan repo, CI, environment staging.
- Definisikan skema DB: `users`, `exams`, `exam_packages`, `questions`, `submissions`, `submission_answers`, `violation_logs`.

**Kriteria selesai:** dokumen keputusan tertulis, skema DB disetujui, repo berjalan.

---

### Fase 1 — Backend Inti (2–3 minggu)

- Auth siswa/guru/admin.
- CRUD ujian dan bank soal.
- Generator paket soal terenkripsi per siswa.
- Endpoint unduh paket (dengan validasi jendela waktu).
- Endpoint submit hasil (idempoten, tolak duplikat).

**Kriteria selesai:** guru bisa membuat ujian, siswa bisa mengunduh paket lewat Postman, submit duplikat ditolak.

---

### Fase 2 — Aplikasi Mobile: Unduh dan Ujian Offline (3–4 minggu)

- Login siswa, unduh paket, dekripsi saat ujian dimulai.
- UI ujian: navigasi soal, auto-save jawaban ke SQLite, timer berbasis waktu server.
- Verifikasi status koneksi sebelum mulai (aplikasi, bukan instruksi ke siswa).

**Kriteria selesai:** ujian bisa dikerjakan 100% offline, jawaban tersimpan walau aplikasi ditutup paksa, timer tidak bisa dimanipulasi dengan mengubah jam perangkat.

---

### Fase 3 — Penguncian dan Deteksi (2–3 minggu)

- Lock Task Mode (Screen Pinning) dengan izin yang disepakati.
- Monitor koneksi: peringatan + log pelanggaran saat internet menyala.
- Deteksi overlay dasar (`FLAG_WINDOW_IS_OBSCURED`) pada layar kunci ujian.

**Kriteria selesai:** siswa tidak bisa keluar aplikasi tanpa keluar dari mode kunci; penyalaan internet tercatat dengan timestamp; overlay yang menutupi area soal memicu peringatan.

---

### Fase 4 — Kunci Submit dan Antrean Pengiriman (2 minggu)

- Status submit disimpan di `EncryptedSharedPreferences` + flag di server.
- WorkManager untuk retry pengiriman saat koneksi tersedia.
- UI status: "belum terkirim / sedang mengirim / terkirim".

**Kriteria selesai:** clear data tidak membuka kesempatan submit ulang (server menolak); hasil terkirim otomatis saat internet dinyalakan; guru melihat status terkirim.

---

### Fase 5 — Web Guru/Admin (2 minggu)

- Dashboard status ujian per siswa.
- Lihat nilai, ekspor CSV.
- Log pelanggaran (jika disepakati).

**Kriteria selesai:** guru bisa memantau dan mengekspor nilai tanpa bantuan developer.

---

### Fase 6 — Pengujian dan UAT (2 minggu)

- Uji skenario: koneksi putus di tengah, aplikasi ditutup paksa, jam perangkat diubah, submit duplikat, paket soal dibuka manual.
- UAT dengan guru dan beberapa siswa.

**Kriteria selesai:** semua skenario uji lulus, tidak ada data hilang, tidak ada submit ganda.

---

## 6. Risiko dan Mitigasi

| Risiko | Mitigasi |
|---|---|
| Soal bisa dibuka sebelum ujian | Enkripsi paket, kunci hanya diberikan saat jendela ujian |
| Siswa mengubah jam perangkat | Timer berbasis waktu server + monotonic clock |
| Clear data untuk submit ulang | Flag di server + `EncryptedSharedPreferences` |
| Kiosk Mode ditolak pengguna | Dokumentasi izin, fallback ke peringatan + log |
| Pengiriman hasil gagal diam-diam | WorkManager + UI status eksplisit |
| Kepatuhan privasi | Kebijakan privasi, persetujuan sekolah/orang tua, retensi data jelas |

---

## 7. Definition of Done Keseluruhan

- Siswa dapat menyelesaikan ujian offline tanpa kehilangan jawaban.
- Hasil tidak dapat dikirim dua kali dan tidak dapat dikerjakan ulang setelah submit.
- Guru dapat melihat dan mengekspor nilai dari web.
- Semua keputusan pada Bagian 4 terdokumentasi dan disetujui.
- Tidak ada fitur yang bergantung pada "deteksi sempurna" sebagai satu-satunya pertahanan.

---

## 8. Instruksi untuk AI Agent

Saat mengeksekusi rencana ini:

- **Jangan** mulai coding Fase 1 sebelum 6 keputusan di Bagian 4 dikonfirmasi.
- **Jangan** menambahkan fitur AI monitoring, kamera, atau deteksi floating window tingkat lanjut tanpa persetujuan eksplisit.
- **Selalu** tulis tes untuk: idempotensi submit, timer anti-manipulasi, dan recovery jawaban setelah aplikasi ditutup paksa.
- **Selalu** pisahkan logika kriptografi ke modul terpisah dengan tes unit.
- Jika ada ambiguitas, **berhenti dan tanya**, jangan mengasumsikan.

---

## 9. Riwayat Keputusan

| Tanggal | Keputusan | Diputuskan oleh |
|---|---|---|
| - | - | - |