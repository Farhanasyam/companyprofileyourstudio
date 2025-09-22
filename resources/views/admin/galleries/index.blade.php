@extends('admin.layout')

@section('title', 'Galeri')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Galeri</h2>
    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Galeri
    </a>
</div>

<!-- Filter Tabs -->
<div class="card mb-4">
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ request()->get('type') === null ? 'active' : '' }}" 
                   href="{{ route('admin.galleries.index') }}">
                    Semua ({{ \App\Models\Gallery::count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('type') === 'hero' ? 'active' : '' }}" 
                   href="{{ route('admin.galleries.index', ['type' => 'hero']) }}">
                    Hero ({{ \App\Models\Gallery::byType('hero')->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('type') === 'about' ? 'active' : '' }}" 
                   href="{{ route('admin.galleries.index', ['type' => 'about']) }}">
                    About ({{ \App\Models\Gallery::byType('about')->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('type') === 'product' ? 'active' : '' }}" 
                   href="{{ route('admin.galleries.index', ['type' => 'product']) }}">
                    Product ({{ \App\Models\Gallery::byType('product')->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('type') === 'gallery' ? 'active' : '' }}" 
                   href="{{ route('admin.galleries.index', ['type' => 'gallery']) }}">
                    Gallery ({{ \App\Models\Gallery::byType('gallery')->count() }})
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($galleries->count() > 0)
            <div class="row">
                @foreach($galleries as $gallery)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $gallery->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $gallery->title }}"
                                 style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $gallery->title }}</h5>
                                @if($gallery->description)
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($gallery->description, 100) }}
                                    </p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary">{{ ucfirst($gallery->type) }}</span>
                                    <span class="badge {{ $gallery->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $gallery->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                                
                                <small class="text-muted">
                                    Urutan: {{ $gallery->sort_order }} | 
                                    Dibuat: {{ $gallery->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('admin.galleries.show', $gallery) }}" 
                                       class="btn btn-sm btn-outline-info" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.galleries.edit', $gallery) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus galeri ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $galleries->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-images fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">Belum ada galeri</h4>
                <p class="text-muted">Mulai dengan menambahkan gambar galeri pertama Anda</p>
                <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Galeri
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Panduan CRUD Galeri -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan CRUD Galeri
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="galleryGuideAccordion">
                    <!-- Cara Membuat Galeri -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createGallery">
                                <i class="bi bi-plus-circle me-2"></i>Cara Membuat Galeri Baru
                            </button>
                        </h2>
                        <div id="createGallery" class="accordion-collapse collapse" data-bs-parent="#galleryGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik tombol <strong>"Tambah Galeri"</strong> di halaman ini</li>
                                    <li>Isi <strong>Judul Galeri</strong> (wajib) - nama yang menarik dan deskriptif</li>
                                    <li>Isi <strong>Deskripsi</strong> (opsional) - penjelasan tentang galeri</li>
                                    <li>Upload <strong>Gambar</strong> (wajib) - pilih satu atau multiple gambar</li>
                                    <li>Set <strong>Status</strong> - pilih "Published" untuk tampil di website</li>
                                    <li>Centang <strong>"Featured"</strong> jika galeri unggulan</li>
                                    <li>Klik <strong>"Simpan Galeri"</strong></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Edit Galeri -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editGallery">
                                <i class="bi bi-pencil me-2"></i>Cara Edit Galeri
                            </button>
                        </h2>
                        <div id="editGallery" class="accordion-collapse collapse" data-bs-parent="#galleryGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>pensil (✏️)</strong> pada galeri yang ingin diedit</li>
                                    <li>Ubah informasi yang diperlukan</li>
                                    <li>Untuk mengganti gambar, upload file baru (akan mengganti yang lama)</li>
                                    <li>Klik <strong>"Update Galeri"</strong> untuk menyimpan perubahan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Hapus Galeri -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#deleteGallery">
                                <i class="bi bi-trash me-2"></i>Cara Hapus Galeri
                            </button>
                        </h2>
                        <div id="deleteGallery" class="accordion-collapse collapse" data-bs-parent="#galleryGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>tempat sampah (🗑️)</strong> pada galeri yang ingin dihapus</li>
                                    <li>Konfirmasi penghapusan di popup yang muncul</li>
                                    <li>Galeri dan semua gambar terkait akan dihapus permanen</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Trik -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#galleryTips">
                                <i class="bi bi-lightbulb me-2"></i>Tips & Trik
                            </button>
                        </h2>
                        <div id="galleryTips" class="accordion-collapse collapse" data-bs-parent="#galleryGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Judul:</strong> Gunakan nama yang menarik dan mudah diingat</li>
                                    <li><strong>Deskripsi:</strong> Jelaskan isi galeri dan konteks gambar</li>
                                    <li><strong>Gambar:</strong> Gunakan gambar berkualitas tinggi (min 800x600px)</li>
                                    <li><strong>Format:</strong> JPEG, PNG, JPG, GIF (maksimal 2MB per file)</li>
                                    <li><strong>Multiple Upload:</strong> Pilih beberapa gambar sekaligus untuk efisiensi</li>
                                    <li><strong>Status:</strong> "Draft" untuk menyimpan sementara, "Published" untuk tampil di website</li>
                                    <li><strong>Featured:</strong> Gunakan untuk galeri unggulan yang ingin ditonjolkan</li>
                                    <li><strong>Organisasi:</strong> Buat galeri berdasarkan tema atau kategori</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
