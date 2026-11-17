/* ============================================================
   global.js — Dipakai di SEMUA halaman admin
   Berisi: manajemen tema warna + highlight menu aktif otomatis
   ============================================================ */
(function () {
  'use strict';

  /* ---------- 1. MANAJEMEN TEMA WARNA ---------- */
  const themeDots = document.querySelectorAll('.theme-dot');
  const htmlElement = document.documentElement;

  function setTheme(themeName) {
    // Hapus atribut data-theme dulu (default pakai :root)
    htmlElement.removeAttribute('data-theme');

    // Jika bukan default, set data-theme
    if (themeName && themeName !== 'default') {
      htmlElement.setAttribute('data-theme', themeName);
    }

    // Update kelas active pada dot
    themeDots.forEach((dot) => {
      dot.classList.remove('active');
      if (dot.dataset.themeVal === themeName) {
        dot.classList.add('active');
      }
    });

    // Simpan preferensi
    try {
      localStorage.setItem('admin-theme', themeName);
    } catch (e) {}
  }

  // Event listener klik pada setiap dot tema
  themeDots.forEach((dot) => {
    dot.addEventListener('click', function () {
      setTheme(this.dataset.themeVal);
    });
  });

  // Load tema tersimpan saat halaman dibuka
  try {
    const savedTheme = localStorage.getItem('admin-theme');
    setTheme(savedTheme || 'default');
  } catch (e) {
    setTheme('default');
  }

  /* ---------- 2. HIGHLIGHT MENU AKTIF OTOMATIS ---------- */
  // Menentukan menu aktif dari nama file URL saat ini.
  // Contoh: /pengguna.html → menu dengan href="pengguna.html" jadi active.
  const currentPage =
    location.pathname.split('/').pop() || 'index.html';

  document.querySelectorAll('.nav-item').forEach((item) => {
    const href = item.getAttribute('href');
    if (href === currentPage) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });
})();