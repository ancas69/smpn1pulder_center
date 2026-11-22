@extends('templates.admin_templates')

@section('title', 'Manajemen Berita')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/admin_berita.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <div class="header-area">
        <div class="page-title">
            <h1>Manajemen Berita</h1>
            <p>Kelola berita, pengumuman, dan artikel sekolah</p>
        </div>
        <div class="header-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchBerita" placeholder="Cari berita...">
            </div>
            <button class="btn-primary" id="btnTambahBerita">
                <i class="fas fa-plus"></i> Tambah Berita
            </button>
        </div>
    </div>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <div class="berita-filter-bar">
        <button class="filter-btn active" data-filter="all">Semua</button>
        <button class="filter-btn" data-filter="published">Terbit</button>
        <button class="filter-btn" data-filter="draft">Draft</button>
        <button class="filter-btn" data-filter="archived">Arsip</button>
    </div>

    {{-- Grid Berita --}}
    <div class="berita-grid" id="beritaGrid">
        @forelse ($beritas as $b)
            <div class="berita-card" data-id="{{ $b->id }}" data-status="{{ $b->status }}">
                @if ($b->thumbnail)
                    <img src="{{ Storage::disk('public')->url($b->thumbnail) }}" alt="Thumbnail" class="berita-thumb">
                @endif

                <div class="berita-card-header">
                    <div class="berita-card-title">{{ $b->judul }}</div>
                    <span class="status-badge status-badge-{{ $b->status }}">
                        {{ ['published' => 'Terbit', 'draft' => 'Draft', 'archived' => 'Arsip'][$b->status] ?? $b->status }}
                    </span>
                </div>

                <div class="berita-card-meta">
                    <span class="kategori-badge kategori-{{ $b->kategori }}">{{ $b->kategori }}</span>
                    <span><i class="far fa-calendar"></i>
                        {{ optional($b->published_at ?? $b->created_at)->format('d M Y') }}
                    </span>
                </div>

                <div class="berita-card-summary">
                    {{ $b->ringkasan ?? Str::limit(strip_tags($b->isi), 100) }}
                </div>

                <div class="berita-card-footer">
                    <span class="text-muted">
                        <i class="fas fa-user-pen"></i> {{ $b->penulis->name ?? 'Anonim' }}
                    </span>
                    <div class="berita-card-actions">
                        <button class="action-btn" data-action="edit" data-id="{{ $b->id }}" title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="action-btn danger" data-action="delete" data-id="{{ $b->id }}"
                            title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                {{-- Data untuk JS (edit) --}}
                <script type="application/json" class="berita-data-json">
                    {!! json_encode([
                        'id' => $b->id,
                        'judul' => $b->judul,
                        'kategori' => $b->kategori,
                        'status' => $b->status,
                        'ringkasan' => $b->ringkasan,
                        'isi' => $b->isi,
                        'thumbnail_url' => $b->thumbnail ? Storage::disk('public')->url($b->thumbnail) : null,
                    ]) !!}
                </script>
            </div>
        @empty
            <div class="berita-empty" style="grid-column:1/-1;">
                <i class="fas fa-newspaper"></i>
                <p>Belum ada berita. Klik "Tambah Berita" untuk mulai.</p>
            </div>
        @endforelse
    </div>

    {{-- Empty state (untuk filter/pencarian JS) --}}
    <div class="berita-empty" id="beritaEmpty" style="display:none;">
        <i class="fas fa-newspaper"></i>
        <p>Tidak ada berita yang cocok dengan filter/pencarian.</p>
    </div>

    {{-- ================= MODAL EDITOR ================= --}}
    <div class="modal-overlay" id="modalBerita">
        <div class="modal-box berita-modal-box">
            <div class="modal-header">
                <h3 id="modalBeritaTitle">Tambah Berita Baru</h3>
                <button class="modal-close" id="closeModalBerita"><i class="fas fa-times"></i></button>
            </div>

            <form id="formBerita" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="beritaId" name="id">
                <input type="hidden" id="isiBerita" name="isi">

                <div class="form-group">
                    <label for="judulBerita">Judul Berita</label>
                    <input type="text" id="judulBerita" name="judul" placeholder="Masukkan judul berita" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kategoriBerita">Kategori</label>
                        <select id="kategoriBerita" name="kategori">
                            <option value="pengumuman">Pengumuman</option>
                            <option value="prestasi">Prestasi</option>
                            <option value="kegiatan">Kegiatan</option>
                            <option value="akademik">Akademik</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statusBerita">Status</label>
                        <select id="statusBerita" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Terbit</option>
                            <option value="archived">Arsip</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ringkasanBerita">Ringkasan Singkat</label>
                    <input type="text" id="ringkasanBerita" name="ringkasan"
                        placeholder="Ringkasan singkat (opsional)">
                </div>

                {{-- Thumbnail --}}
                <div class="form-group">
                    <label>Thumbnail Berita</label>
                    <div class="upload-thumb-wrapper">
                        <img id="thumbPreview" src="" alt="Preview" style="display:none;">
                        <label for="thumbnailBerita" class="upload-thumb-label">
                            <i class="fas fa-image"></i>
                            <span>Pilih Gambar</span>
                        </label>
                        <input type="file" id="thumbnailBerita" name="thumbnail" accept="image/*"
                            style="display:none;">
                    </div>
                </div>

                {{-- Rich Text Editor --}}
                <div class="form-group">
                    <label>Isi Berita</label>
                    <div id="editorToolbar">
                        <span class="ql-formats">
                            <select class="ql-header">
                                <option value="1"></option>
                                <option value="2"></option>
                                <option value="3"></option>
                                <option selected></option>
                            </select>
                        </span>
                        <span class="ql-formats">
                            <button class="ql-bold"></button>
                            <button class="ql-italic"></button>
                            <button class="ql-underline"></button>
                            <button class="ql-strike"></button>
                        </span>
                        <span class="ql-formats">
                            <button class="ql-list" value="ordered"></button>
                            <button class="ql-list" value="bullet"></button>
                        </span>
                        <span class="ql-formats">
                            <button class="ql-blockquote"></button>
                            <button class="ql-link"></button>
                            <button class="ql-image"></button>
                        </span>
                        <span class="ql-formats">
                            <button class="ql-clean"></button>
                        </span>
                    </div>
                    <div id="editorBerita"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" id="cancelModalBerita">Batal</button>
                    <button type="submit" class="btn-primary" id="btnSimpanBerita">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div class="modal-overlay" id="modalHapusBerita">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Hapus Berita?</h3>
                <button class="modal-close" id="closeModalHapus"><i class="fas fa-times"></i></button>
            </div>
            <p class="text-muted" style="margin-bottom: 8px;">
                Berita yang dihapus tidak dapat dikembalikan.
            </p>
            <p id="hapusBeritaTitle" style="font-weight: 600;"></p>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancelModalHapus">Batal</button>
                <button type="button" class="btn-danger" id="confirmHapusBerita">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Meta untuk JS --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="route-store" content="{{ route('admin.berita.store') }}">
    <meta name="route-update" content="{{ route('admin.berita.update', ['berita' => '__ID__']) }}">
    <meta name="route-delete" content="{{ route('admin.berita.destroy', ['berita' => '__ID__']) }}">
    <meta name="route-upload" content="{{ route('admin.berita.uploadImage') }}">
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="{{ asset('js/admin_berita.js') }}"></script>
@endpush
