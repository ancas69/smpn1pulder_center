/**
 * admin_pengguna.js
 * Modul manajemen pengguna: Admin, Guru, Murid, Wali
 */

(function () {
  'use strict';

  // ==================== STATE ====================
  const state = {
    users: [],
    filteredRole: 'all',
    searchQuery: '',
    editingId: null,
    deletingId: null,
  };

  // ==================== MOCK DATA ====================
  // Ganti bagian ini dengan fetch ke API / route Laravel
  const mockUsers = [
    { id: 1, name: 'Budi Santoso', email: 'budi@admin.com', role: 'admin', status: 'active', nis: '-', wa: '-' },
    { id: 2, name: 'Siti Aminah', email: 'siti@admin.com', role: 'admin', status: 'active', nis: '-', wa: '-' },
    { id: 3, name: 'Ahmad Fauzi', email: 'ahmad@sekolah.sch.id', role: 'guru', status: 'active', nis: '19850101', wa: '081234567890' },
    { id: 4, name: 'Rina Wati', email: 'rina@sekolah.sch.id', role: 'guru', status: 'active', nis: '19870202', wa: '081298765432' },
    { id: 5, name: 'Dewi Lestari', email: 'dewi@sekolah.sch.id', role: 'guru', status: 'inactive', nis: '19900303', wa: '081211112222' },
    { id: 6, name: 'Rizky Pratama', email: 'rizky@siswa.sch.id', role: 'murid', status: 'active', nis: '2024001', wa: '-' },
    { id: 7, name: 'Putri Ayu', email: 'putri@siswa.sch.id', role: 'murid', status: 'active', nis: '2024002', wa: '-' },
    { id: 8, name: 'Fajar Nugroho', email: 'fajar@siswa.sch.id', role: 'murid', status: 'active', nis: '2024003', wa: '-' },
    { id: 9, name: 'Anisa Rahma', email: 'anisa@siswa.sch.id', role: 'murid', status: 'inactive', nis: '2024004', wa: '-' },
    { id: 10, name: 'Hendra Wijaya', email: 'hendra@wali.com', role: 'wali', status: 'active', nis: '-', wa: '081355556666' },
    { id: 11, name: 'Maria Ulfa', email: 'maria@wali.com', role: 'wali', status: 'active', nis: '-', wa: '081377778888' },
    { id: 12, name: 'Yusuf Hamid', email: 'yusuf@wali.com', role: 'wali', status: 'active', nis: '-', wa: '081399990000' },
  ]; // sudah gak di pakek , udah dari database


  // ==================== DOM CACHE ====================
  const els = {
    searchInput: document.getElementById('searchInput'),
    userTableBody: document.getElementById('userTableBody'),
    userCount: document.getElementById('userCount'),
    filterBtns: document.querySelectorAll('.filter-btn'),
    btnAddUser: document.getElementById('btnAddUser'),
    // stats
    totalAdmin: document.getElementById('totalAdmin'),
    totalGuru: document.getElementById('totalGuru'),
    totalMurid: document.getElementById('totalMurid'),
    totalWali: document.getElementById('totalWali'),
    // modal form
    userModal: document.getElementById('userModal'),
    modalTitle: document.getElementById('modalTitle'),
    modalClose: document.getElementById('modalClose'),
    userForm: document.getElementById('userForm'),
    userId: document.getElementById('userId'),
    inputName: document.getElementById('inputName'),
    inputEmail: document.getElementById('inputEmail'),
    inputRole: document.getElementById('inputRole'),
    inputNisn: document.getElementById('inputNisn'),
    inputWa: document.getElementById('inputWa'),
    inputPassword: document.getElementById('inputPassword'),
    nisGroup: document.getElementById('nisGroup'),
    waGroup: document.getElementById('waGroup'),
    idModal: document.getElementById('idModal'),
    inputChild: document.getElementById('inputChild'),
    childGroup: document.getElementById('childGroup'),
    btnCancel: document.getElementById('btnCancel'),
    // delete modal
    deleteModal: document.getElementById('deleteModal'),
    deleteModalClose: document.getElementById('deleteModalClose'),
    deleteName: document.getElementById('deleteName'),
    btnCancelDelete: document.getElementById('btnCancelDelete'),
    btnConfirmDelete: document.getElementById('btnConfirmDelete'),
    i:0,
  };

  // ==================== API HELPER ====================

  function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }

  /** Ambil data pengguna dari server. @returns {Promise<Array>} */
  async function fetchUsers() {
    // Jika untuk mengambil data juga pakai POST, tambahkan method: 'POST' dan X-CSRF-TOKEN
    const res = await fetch('/allUsers', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      }
    });

    if (!res.ok) {
      throw new Error(`Gagal mengambil data (${res.status})`);
    }

    return await res.json();
  }

  /** Simpan pengguna (create / update) menggunakan POST. @param {Object} payload @returns {Promise<Object>} */
  async function saveUser(payload) {
    // Tentukan URL berbeda untuk Edit dan Tambah Baru, namun tetap pakai method POST
    const url = payload.id ? `/api/pengguna/update/${payload.id}` : '/api/pengguna/store';

    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      },
      body: JSON.stringify(payload)
    });

    if (!res.ok) {
      const errorData = await res.json().catch(() => ({}));
      throw new Error(errorData.message || `Gagal menyimpan (${res.status})`);
    }

    return await res.json();
    }

    /** Ambil daftar murid dari server (belum punya wali untuk tambah, seluruh anak untuk update) */
    async function fetchChildren() {
        try {
        const isUpdate = state.editingId != null;
        const apiUrl = isUpdate ? '/api/seluruh-anak' : '/api/murid';
        const res = await fetch(apiUrl, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
          }
        });
        if (!res.ok) {
          throw new Error(`Gagal ambil data murid (${res.status})`);
        }
        return await res.json();
        } catch (err) {
        console.error('fetchChildren error:', err);
        return [];
        }
    }

    /** Hapus pengguna menggunakan POST. @param {number} id @returns {Promise<void>} */
    async function deleteUser(id) {
    const res = await fetch(`/api/pengguna/delete/${id}`, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken()
    }
    });
    const data = await res.json();
    await init();
    // console.log(data);
    if (!data.success) {
    throw new Error(`(${data.message || 'Gagal menghapus'})`);
    }
    }

    /** Isi dropdown pilihan anak dengan daftar murid dari server
    *  @param {Array} muridList
    *  @param {string} selectedNISN opsional: NISN yang sudah di-assign ke wali, akan di-highlight
    */
  function renderChildrenOptions(muridList, selectedNISN = '') {
    els.inputChild.innerHTML = '';
    if (!muridList || muridList.length === 0) {
      const opt = document.createElement('option');
      opt.value = '';
      opt.textContent = 'Tidak ada murid yang belum di-assign wali.';
      opt.disabled = true;
      els.inputChild.appendChild(opt);
      return;
    }
    
    muridList.forEach((m) => {
      els.i=0;
      const opt = document.createElement('option');
      opt.value = m.nisn;
      opt.textContent = `${m.name}  (${m.nisn})`;
      while(els.i < selectedNISN.length)
      {
        
        // console.log(selectedNISN[els.i]+" -- "+m.nisn);
        if (m.nisn === selectedNISN[els.i]) {
          // console.log(selectedNISN[els.i]+" sama");
          opt.selected = true; // highlight/anego relasi
        }
        
        els.i++;
      }
      els.inputChild.appendChild(opt);
      
      
    });
  }

  // ==================== FILTERING ====================
  function getFilteredUsers() {
    return state.users.filter((u) => {
      const matchRole = state.filteredRole === 'all' || u.role === state.filteredRole;
      const q = state.searchQuery.toLowerCase();
      const matchSearch =
        !q ||
        u.name.toLowerCase().includes(q) ||
        u.email.toLowerCase().includes(q) ||
        (u.nis && u.nis.toLowerCase().includes(q));
      return matchRole && matchSearch;
    });
  }

  // ==================== RENDER ====================
  function renderStats() {
    const count = (role) => state.users.filter((u) => u.role === role).length;
    els.totalAdmin.textContent = count('admin');
    els.totalGuru.textContent = count('guru');
    els.totalMurid.textContent = count('murid');
    els.totalWali.textContent = count('wali');
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/\"/g, '&quot;');
  }

  function roleLabel(role) {
    const map = { admin: 'Admin', guru: 'Guru', murid: 'Murid', wali: 'Wali' };
    return map[role] || role;
  }

  function roleClass(role) {
    return `role-badge role-${role}`;
  }

  function statusClass(status) {
    return status === false ? 'status-badge status-active' : 'status-badge status-inactive';
  }

  function statusLabel(status) {
    return status === false ? 'Aktif' : 'Nonaktif';
  }

  function renderTable() {
    const filtered = getFilteredUsers();
    els.userCount.textContent = `${filtered.length} Pengguna`;

    if (filtered.length === 0) {
      els.userTableBody.innerHTML = `
        <tr>
          <td colspan="5" style="text-align:center; padding: 40px; color: var(--text-secondary);">
            <i class="fas fa-user-slash" style="font-size: 28px; display:block; margin-bottom: 10px; opacity: 0.4;"></i>
            Tidak ada pengguna ditemukan
          </td>
        </tr>`;
      return;
    }

    els.userTableBody.innerHTML = filtered
      .map(
        (u) => `
      <tr>
        <td>
          <div style="display:flex; align-items:center; gap:10px;">
            <div class="avatar-img" style="width:32px;height:32px;font-size:13px;">
              ${u.name.charAt(0).toUpperCase()}
            </div>
            <span style="font-weight:500;">${escapeHtml(u.name)}</span>
          </div>
        </td>
        <td>
          <div style="font-weight:500;">${escapeHtml(u.email)}</div>
          ${(u.student?.nisn || u.guru?.nip) ? `<div class="text-muted" style="font-size:12px;">ID: ${escapeHtml(u.student?.nisn || u.guru?.nip)}</div>` : ''}
        </td>
        <td><span class="${roleClass(u.role)}">${roleLabel(u.role)}</span></td>
        <td><span class="${statusClass(u.blokir)}">${statusLabel(u.blokir)}</span></td>
        <td>
          <button class="action-btn" data-action="edit" data-id="${u.id}" title="Edit">
            <i class="fas fa-pen"></i>
          </button>
          <button class="action-btn danger" data-action="delete" data-id="${u.id}" title="Hapus">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>`
      )
      .join('');
  }

  function render() {
    renderStats();
    renderTable();
  }

  // ==================== MODAL ====================
  function openUserModal(user = null) {
      state.editingId = user ? user.id : null;
      els.modalTitle.textContent =  user? 'Edit Pengguna' : 'Tambah Pengguna';
      els.userId.value = user ? user.id : '';
      els.inputName.value = user ? user.name : '';
      els.inputEmail.value = user ? user.email : '';
      els.inputRole.value = user ? user.role : '';
      els.inputPassword.placeholder = user? 'kosongkan jika tidak berubah' : 'masukkan password baru';
      // console.log('user : '+user.user);
      // console.log('guru :'+user.guru);
      // console.log('murid :'+user.students);
      // console.log(user);
      // Jika role wali, ambil daftar murid dari server untuk dipilih
      if (els.inputRole.value === 'wali') {
        (async () => {
          const muridList = await fetchChildren();
          
          // ambil NISN anak yang sudah punya relasi wali (untuk highlight)
          const childrenNISN = new Set();
          if (user && user.wali && user.wali.students) {
            user.wali.students.forEach((s) => childrenNISN.add(s.nisn));
          }
          renderChildrenOptions(muridList, Array.from(childrenNISN));
          els.childGroup.style.display = 'block';
        })();
      } else {
        els.childGroup.style.display = 'none';
        els.inputChild.innerHTML = '';
      }

      // PERBAIKAN DI SINI
      if (user) {
          // Karena relasi 'student', 'guru', 'wali' mungkin bernilai null,
          // gunakan optional chaining (?.) agar tidak error
          const nisn = user.student?.nisn || '';
          const nip  = user.guru?.nip || '';

          // WA bisa ada di tabel guru atau wali
          const wa   = user.guru?.no_whatsapp || user.wali?.no_whatsapp || '';

          // Logika inputNisn (karena di form HTML hanya ada 1 input untuk NISN/NIP)
          if (user.role === 'murid') {
              els.inputNisn.value = nisn;
          } else if (user.role === 'guru') {
              els.inputNisn.value = nip;
          } else {
              els.inputNisn.value = '';
          }

          els.inputWa.value = wa;
      } else {
          // Jika tambah user baru, kosongkan
          els.inputNisn.value = '';
          els.inputWa.value = '';
      }

      toggleExtraFields(user ? user.role : '');

      

      els.userModal.classList.add('show');
      setTimeout(() => els.inputName.focus(), 100);
  }

  function closeUserModal() {
    els.userModal.classList.remove('show');
    els.userForm.reset();
    state.editingId = null;
  }

  function toggleExtraFields(role) {
    els.nisGroup.style.display = role === 'murid' || role === 'guru' ? 'block' : 'none'; // block = show
    if(role === 'murid')
    {
        els.idModal.innerHTML = 'NISN';
    }else if(role === 'guru')
    {
        els.idModal.innerHTML = 'NIP/NUPTK'
    }

    els.waGroup.style.display = role === 'wali' || role === 'guru' ? 'block' : 'none';
  }

  function openDeleteModal(user) {
    state.deletingId = user.id;
    els.deleteName.textContent = user.name;
    els.deleteModal.classList.add('show');
  }

  function closeDeleteModal() {
    els.deleteModal.classList.remove('show');
    state.deletingId = null;
  }

  // ==================== EVENT HANDLERS ====================
  function bindEvents() {
    // Search
    els.searchInput.addEventListener('input', (e) => {
      state.searchQuery = e.target.value.trim();
      renderTable();
    });

    // Filter role
    els.filterBtns.forEach((btn) => {
      btn.addEventListener('click', () => {
        els.filterBtns.forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
        state.filteredRole = btn.dataset.role;
        renderTable();
      });
    });

    // Tambah pengguna
    els.btnAddUser.addEventListener('click', () => openUserModal());

    // Modal form
    els.modalClose.addEventListener('click', closeUserModal);
    els.btnCancel.addEventListener('click', closeUserModal);
    els.userModal.addEventListener('click', (e) => {
      if (e.target === els.userModal) closeUserModal();
    });

    // Role change -> tampilkan field tambahan
      // Role change -> tampilkan field tambahan
    els.inputRole.addEventListener('change', async (e) => {
        const selectedRole = e.target.value;
        
        // 1. Munculkan/sembunyikan field standar (NISN, WA, dll)
        toggleExtraFields(selectedRole);

        // 2. Logika untuk memunculkan List Anak jika memilih Wali
        if (selectedRole === 'wali') {
            els.childGroup.style.display = 'block';
            
            // Tampilkan loading sementara (opsional)
            els.inputChild.innerHTML = '<option value="">Memuat data murid...</option>';
            
            const muridList = await fetchChildren();
            renderChildrenOptions(muridList);
        } else {
            els.childGroup.style.display = 'none';
            els.inputChild.innerHTML = '';
        }
    });

    // Submit form
    els.userForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const payload = {
        id: state.editingId,
        name: els.inputName.value.trim(),
        email: els.inputEmail.value.trim(),
        role: els.inputRole.value,
        anak: els.inputChild.value,
        nisn: els.inputNisn.value.trim() || '-',
        wa: els.inputWa.value.trim() || '-',
        password: els.inputPassword.value,
        status: 'active',
        // Kirim daftar NISN murid yang dipilih untuk role wali
        children: Array.from(els.inputChild.selectedOptions).map(opt => opt.value),
      };

      if (!payload.name || !payload.email || !payload.role) {
        alert('Nama, email, dan role wajib diisi.');
        return;
      }

      try {
        const saved = await saveUser(payload);
        if (state.editingId) {
          // update existing
          const idx = state.users.findIndex((u) => u.id === state.editingId);
          if (idx !== -1) state.users[idx] = { ...state.users[idx], ...payload };
        } else {
          // create new
          const newId = state.users.length ? Math.max(...state.users.map((u) => u.id)) + 1 : 1;
          state.users.unshift({ ...payload, id: newId });
        }
        closeUserModal();
        await init();
      } catch (err) {
        alert('Gagal menyimpan pengguna: ' + err.message);
      }
    });

    // Aksi tabel (delegasi)
    els.userTableBody.addEventListener('click', (e) => {
      const btn = e.target.closest('button[data-action]');
      if (!btn) return;
      const id = Number(btn.dataset.id);
      const user = state.users.find((u) => u.id === id);
      if (!user) return;

      if (btn.dataset.action === 'edit') {
        openUserModal(user);
      } else if (btn.dataset.action === 'delete') {
        openDeleteModal(user);
      }
    });

    // Delete modal
    els.deleteModalClose.addEventListener('click', closeDeleteModal);
    els.btnCancelDelete.addEventListener('click', closeDeleteModal);
    els.deleteModal.addEventListener('click', (e) => {
      
      if (e.target === els.deleteModal) closeDeleteModal();
    });

    els.btnConfirmDelete.addEventListener('click', async () => {
      if (!state.deletingId) return;
      
      try {
        await deleteUser(state.deletingId);
        state.users = state.users.filter((u) => u.id !== state.deletingId);
        
        closeDeleteModal();
        render();
      } catch (err) {
        alert('Gagal menghapus pengguna: ' + err.message);
      }
    });

    // Tutup modal dengan ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeUserModal();
        closeDeleteModal();
      }
    });
  }

  // ==================== INIT ================
// 1. Panggil bindEvents() HANYA SEKALI di luar
document.addEventListener('DOMContentLoaded', () => {
    bindEvents();
    init(); // init hanya untuk fetch data dan render
});

// 2. Hapus bindEvents() dari dalam init()
async function init() {
    try {
        state.users = await fetchUsers();
    } catch (err) {
        console.error('Gagal memuat data pengguna:', err);
        state.users = [];
    }
    render();
}

document.addEventListener('DOMContentLoaded', init);
})();
