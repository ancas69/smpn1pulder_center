@extends('templates.admin_templates')

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
                    <label>NIS / NISN</label>
                    <input type="text" id="inputNis" placeholder="Nomor Induk Siswa">
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

    <style>
        /* style khusus halaman pengguna, tambahkan ke admin.css jika perlu */
        .filter-btn {
            padding: 8px 20px;
            border-radius: 40px;
            border: 1px solid var(--border-light);
            background: var(--bg-card);
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .filter-btn:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        .filter-btn.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .user-table th {
            text-align: left;
            padding: 12px 14px;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 1px solid var(--border-light);
        }

        .user-table td {
            padding: 14px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-primary);
            vertical-align: middle;
        }

        .user-table tbody tr:hover {
            background: var(--hover-bg);
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
        }

        .role-admin {
            background: #eef2ff;
            color: #4f46e5;
        }

        .role-guru {
            background: #e0f5ec;
            color: #1f9d6e;
        }

        .role-murid {
            background: #e3efff;
            color: #2a7de1;
        }

        .role-wali {
            background: #f3e8ff;
            color: #9333ea;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #e6f9f0;
            color: #0b6e4f;
        }

        .status-inactive {
            background: #ffeae9;
            color: #b91c1c;
        }

        .action-btn {
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 14px;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .action-btn.danger:hover {
            background: #ffeae9;
            color: #b91c1c;
        }

        /* modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            background: var(--bg-card);
            border-radius: 24px;
            width: 100%;
            max-width: 480px;
            padding: 28px;
            box-shadow: var(--shadow-md);
            animation: modalIn 0.2s ease;
        }

        @keyframes modalIn {
            from {
                transform: translateY(10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .modal-close {
            border: none;
            background: var(--hover-bg);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid var(--border-light);
            background: var(--bg-body);
            font-size: 14px;
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--accent);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-primary,
        .btn-secondary,
        .btn-danger {
            padding: 11px 22px;
            border-radius: 12px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        .btn-secondary {
            background: var(--hover-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-light);
        }

        .btn-secondary:hover {
            background: var(--bg-body);
        }

        .btn-danger {
            background: #ef4444;
            color: #fff;
        }

        .btn-danger:hover {
            background: #dc2626;
        }
    </style>

    <script src="{{ asset('js/admin_pengguna.js') }}"></script>
@endsection
