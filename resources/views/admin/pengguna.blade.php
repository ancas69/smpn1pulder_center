@extends('templates.admin_templates')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/admin_pengguna.css') }}">
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="header-area">
        <div class="page-title">
            <h1>Manajemen Pengguna</h1>
            <p>Kelola data Admin, Guru, Murid, dan Wali Murid</p>
        </div>
        <div class="header-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama, email, atau NIS...">
            </div>
            <div class="avatar">
                <div class="avatar-img">A</div>
                <div class="avatar-name">Admin</div>
            </div>
        </div>
    </div>

    <!-- STATS RINGKASAN -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
            <div class="stat-value" id="totalAdmin">0</div>
            <div class="stat-label">Admin</div>
            <div class="stat-trend"><i class="fas fa-circle"></i> Aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chalkboard-user"></i></div>
            <div class="stat-value" id="totalGuru">0</div>
            <div class="stat-label">Guru / PTK</div>
            <div class="stat-trend"><i class="fas fa-circle"></i> Aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-value" id="totalMurid">0</div>
            <div class="stat-label">Murid</div>
            <div class="stat-trend"><i class="fas fa-circle"></i> Aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-group"></i></div>
            <div class="stat-value" id="totalWali">0</div>
            <div class="stat-label">Wali Murid</div>
            <div class="stat-trend"><i class="fas fa-circle"></i> Aktif</div>
        </div>
    </div>

    <!-- FILTER ROLE -->
    <div class="section-card" style="margin-bottom: 24px;">
        <div class="section-header">
            <h3>Filter & Aksi</h3>
            <div style="display: flex; gap: 10px;">
                <button class="badge" id="btnAddUser" style="border: none; cursor: pointer;">
                    <i class="fas fa-plus"></i> Tambah Pengguna
                </button>
            </div>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button class="filter-btn active" data-role="all">Semua</button>
            <button class="filter-btn" data-role="admin">Admin</button>
            <button class="filter-btn" data-role="guru">Guru</button>
            <button class="filter-btn" data-role="murid">Murid</button>
            <button class="filter-btn" data-role="wali">Wali</button>
        </div>
    </div>

    <!-- TABEL PENGGUNA -->
    <div class="section-card">
        <div class="section-header">
            <h3>Daftar Pengguna</h3>
            <span class="badge" id="userCount">0 Pengguna</span>
        </div>
        <div style="overflow-x: auto;">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email / NIS</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 40px; color: var(--text-secondary);">Memuat
                            data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH / EDIT PENGGUNA -->
    <div class="modal-overlay" id="userModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Pengguna</h3>
                <button class="modal-close" id="modalClose"><i class="fas fa-times"></i></button>
            </div>
            <form id="userForm">
                @csrf
                <input type="hidden" id="userId">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="inputName" required placeholder="Masukkan nama lengkap">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="inputEmail" required placeholder="contoh@email.com">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select id="inputRole" required>
                        <option value="">Pilih role</option>
                        <option value="admin">Admin</option>
                        <option value="guru">Guru / PTK</option>
                        <option value="murid">Murid</option>
                        <option value="wali">Wali Murid</option>
                    </select>
                </div>
                <div class="form-group" id="nisGroup" style="display: none;">
                    <label id="idModal">NIS / NISN</label>
                    <input type="text" id="inputNisn" placeholder="Nomor Induk Siswa">
                </div>
                <div class="form-group" id="childGroup" style="display: none;">
                    <label>Anak (Pilih dari daftar)</label>
                    <select id="inputChild" multiple size="5" style="width: 100%;"></select>
                </div>
                <div class="form-group" id="waGroup" style="display: none;">
                    <label>Nomor WhatsApp</label>
                    <input type="text" id="inputWa" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="inputPassword" placeholder="Kosongkan jika tidak diubah">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="btnCancel">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Hapus Pengguna</h3>
                <button class="modal-close" id="deleteModalClose"><i class="fas fa-times"></i></button>
            </div>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                Apakah Anda yakin ingin menghapus <strong id="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="btnCancelDelete">Batal</button>
                <button type="button" class="btn-danger" id="btnConfirmDelete">Hapus</button>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="{{ asset('js/admin_pengguna.js') }}"></script>
@endpush
