/* ============================================================
   admin_berita.js — Halaman Manajemen Berita
   - Render & filter kartu
   - Modal tambah/edit dengan Quill editor
   - Upload thumbnail + upload gambar di editor
   - Submit ke Laravel via fetch (FormData)
   ============================================================ */
(function () {
    'use strict';

    /* ---------- Meta dari Blade ---------- */
    const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
    const ROUTE_STORE  = document.querySelector('meta[name="route-store"]').content;
    const ROUTE_UPDATE = document.querySelector('meta[name="route-update"]').content;
    const ROUTE_DELETE = document.querySelector('meta[name="route-delete"]').content;
    const ROUTE_UPLOAD = document.querySelector('meta[name="route-upload"]').content;

    /* ---------- Elemen ---------- */
    const grid        = document.getElementById('beritaGrid');
    const emptyState  = document.getElementById('beritaEmpty');
    const searchInput = document.getElementById('searchBerita');
    const filterBtns  = document.querySelectorAll('.filter-btn');

    const modalBerita      = document.getElementById('modalBerita');
    const modalBeritaTitle = document.getElementById('modalBeritaTitle');
    const formBerita       = document.getElementById('formBerita');
    const btnTambah        = document.getElementById('btnTambahBerita');
    const closeModalBerita = document.getElementById('closeModalBerita');
    const cancelModalBerita= document.getElementById('cancelModalBerita');
    const btnSimpan        = document.getElementById('btnSimpanBerita');

    const inputId        = document.getElementById('beritaId');
    const inputJudul     = document.getElementById('judulBerita');
    const inputKategori  = document.getElementById('kategoriBerita');
    const inputStatus    = document.getElementById('statusBerita');
    const inputRingkasan = document.getElementById('ringkasanBerita');
    const inputIsi       = document.getElementById('isiBerita');
    const inputThumb     = document.getElementById('thumbnailBerita');
    const thumbPreview   = document.getElementById('thumbPreview');

    const modalHapus      = document.getElementById('modalHapusBerita');
    const hapusTitle      = document.getElementById('hapusBeritaTitle');
    const closeModalHapus = document.getElementById('closeModalHapus');
    const cancelModalHapus= document.getElementById('cancelModalHapus');
    const confirmHapus    = document.getElementById('confirmHapusBerita');

    if (!grid) return;

    /* ---------- Quill Editor ---------- */
    let quill = null;
    if (window.Quill && document.getElementById('editorBerita')) {
        quill = new Quill('#editorBerita', {
            theme: 'snow',
            placeholder: 'Tulis isi berita di sini...',
            modules: {
                toolbar: {
                    container: '#editorToolbar',
                    handlers: {
                        image: () => selectLocalImage(),
                    },
                },
            },
        });
    }

    /* Upload gambar dari toolbar Quill */
    function selectLocalImage() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch(ROUTE_UPLOAD, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: formData,
                });
                if (!res.ok) throw new Error('Upload gagal');
                const data = await res.json();

                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', data.url, 'user');
                quill.setSelection(range.index + 1);
            } catch (err) {
                alert('Gagal mengunggah gambar: ' + err.message);
            }
        };
    }

    /* ---------- Thumbnail Preview ---------- */
    inputThumb.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            thumbPreview.src = e.target.result;
            thumbPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    /* ---------- Filter & Search (client-side) ---------- */
    let activeFilter = 'all';
    let searchQuery = '';

    function applyFilter() {
        const cards = grid.querySelectorAll('.berita-card');
        let visible = 0;

        cards.forEach((card) => {
            const status = card.dataset.status || '';
            const text   = card.textContent.toLowerCase();
            const matchStatus = activeFilter === 'all' || status === activeFilter;
            const matchSearch = !searchQuery || text.includes(searchQuery);
            const show = matchStatus && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        emptyState.style.display = visible === 0 ? 'block' : 'none';
    }

    filterBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            filterBtns.forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            activeFilter = btn.dataset.filter;
            applyFilter();
        });
    });

    let searchTimer = null;
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            searchQuery = this.value.trim().toLowerCase();
            applyFilter();
        }, 200);
    });

    /* ---------- Buka Modal ---------- */
    function openModal(berita = null) {
        formBerita.reset();
        thumbPreview.style.display = 'none';
        thumbPreview.src = '';
        if (quill) quill.setContents([]);

        if (berita) {
            modalBeritaTitle.textContent = 'Edit Berita';
            inputId.value       = berita.id;
            inputJudul.value    = berita.judul || '';
            inputKategori.value = berita.kategori || 'pengumuman';
            inputStatus.value   = berita.status || 'draft';
            inputRingkasan.value= berita.ringkasan || '';
            if (quill) quill.clipboard.dangerouslyPasteHTML(berita.isi || '');
            if (berita.thumbnail_url) {
                thumbPreview.src = berita.thumbnail_url;
                thumbPreview.style.display = 'block';
            }
        } else {
            modalBeritaTitle.textContent = 'Tambah Berita Baru';
            inputId.value = '';
        }

        modalBerita.classList.add('show');
        setTimeout(() => inputJudul.focus(), 60);
    }

    function closeModal() {
        modalBerita.classList.remove('show');
    }

    /* ---------- Klik tombol tambah/edit/hapus ---------- */
    btnTambah.addEventListener('click', () => openModal());
    closeModalBerita.addEventListener('click', closeModal);
    cancelModalBerita.addEventListener('click', closeModal);
    modalBerita.addEventListener('click', (e) => {
        if (e.target === modalBerita) closeModal();
    });

    grid.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;

        const card = btn.closest('.berita-card');
        const id   = btn.dataset.id;

        if (btn.dataset.action === 'edit') {
            const jsonEl = card.querySelector('.berita-data-json');
            const data = jsonEl ? JSON.parse(jsonEl.textContent) : null;
            if (data) openModal(data);
        } else if (btn.dataset.action === 'delete') {
            hapusTitle.textContent =
                card.querySelector('.berita-card-title')?.textContent || '';
            modalHapus.dataset.id = id;
            modalHapus.classList.add('show');
        }
    });

    /* ---------- Submit Form (store / update) ---------- */
    formBerita.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!inputJudul.value.trim()) {
            alert('Judul berita wajib diisi.');
            return;
        }

        // Sinkronkan isi Quill ke hidden input
        if (quill) inputIsi.value = quill.root.innerHTML;

        const id = inputId.value;
        const url = id
            ? ROUTE_UPDATE.replace('__ID__', id)
            : ROUTE_STORE;

        const formData = new FormData(formBerita);
        if (id) formData.append('_method', 'PUT');  // method spoofing

        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        try {
            const res = await fetch(url, {
                method: 'POST', // selalu POST, _method menangani PUT
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(err.message || 'Gagal menyimpan berita');
            }

            // Reload untuk menampilkan data terbaru dari server
            window.location.reload();
        } catch (err) {
            alert('Error: ' + err.message);
        } finally {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="fas fa-save"></i> Simpan';
        }
    });

    /* ---------- Konfirmasi Hapus ---------- */
    function closeModalHapusFn() {
        modalHapus.classList.remove('show');
        delete modalHapus.dataset.id;
    }
    closeModalHapus.addEventListener('click', closeModalHapusFn);
    cancelModalHapus.addEventListener('click', closeModalHapusFn);
    modalHapus.addEventListener('click', (e) => {
        if (e.target === modalHapus) closeModalHapusFn();
    });

    confirmHapus.addEventListener('click', async () => {
        const id = modalHapus.dataset.id;
        if (!id) return;
        const url = ROUTE_DELETE.replace('__ID__', id);

        confirmHapus.disabled = true;
        confirmHapus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: '_method=DELETE',
            });
            if (!res.ok) throw new Error('Gagal menghapus berita');
            window.location.reload();
        } catch (err) {
            alert('Error: ' + err.message);
            confirmHapus.disabled = false;
            confirmHapus.innerHTML = '<i class="fas fa-trash"></i> Hapus';
        }
    });
})();