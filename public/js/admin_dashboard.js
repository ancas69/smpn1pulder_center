/* ============================================================
   admin_home.js — KHUSUS halaman Dashboard (index.html)
   Berisi logika yang hanya relevan di dashboard.
   ============================================================ */
(function () {
  'use strict';

  // Pastikan kita benar-benar di dashboard (index.html)
  const isDashboard =
    !location.pathname.split('/').pop() ||
    location.pathname.split('/').pop() === 'index.html';

  if (!isDashboard) return;

  /* ---------- Contoh: animasi angka statistik ---------- */
  // (Aktifkan jika elemen .stat-value punya data-target)
  /*
  const statValues = document.querySelectorAll('.stat-value[data-target]');
  statValues.forEach((el) => {
    const target = parseFloat(el.dataset.target);
    const duration = 800;
    const start = performance.now();

    function tick(now) {
      const progress = Math.min((now - start) / duration, 1);
      const current = Math.floor(target * progress);
      el.textContent = current.toLocaleString('id-ID');
      if (progress < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  });
  */

  /* ---------- Contoh: log kunjungan dashboard ---------- */
  console.log('[Dashboard] Halaman dashboard dimuat.');
})();