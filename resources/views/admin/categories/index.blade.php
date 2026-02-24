@extends('admin.layout')

@section('title', 'Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Kategori</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Jumlah Produk</th>
                            <th>Status</th>
                            <th>Urutan</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($category->image)
                                            <img src="{{ asset($category->image_url) }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <strong>{{ $category->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $category->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->products_count }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>{{ $category->sort_order }}</td>
                                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.categories.show', $category) }}" 
                                           class="btn btn-sm btn-outline-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tags fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">Belum ada kategori</h4>
                <p class="text-muted">Mulai dengan menambahkan kategori pertama Anda</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Panduan CRUD Kategori -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan CRUD Kategori
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="categoryGuideAccordion">
                    <!-- Cara Membuat Kategori -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createCategory">
                                <i class="bi bi-plus-circle me-2"></i>Cara Membuat Kategori Baru
                            </button>
                        </h2>
                        <div id="createCategory" class="accordion-collapse collapse" data-bs-parent="#categoryGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik tombol <strong>"Tambah Kategori"</strong> di halaman ini</li>
                                    <li>Isi <strong>Nama Kategori</strong> (wajib) - nama yang jelas dan deskriptif</li>
                                    <li>Isi <strong>Deskripsi</strong> (opsional) - penjelasan tentang kategori</li>
                                    <li>Upload <strong>Gambar</strong> (opsional) - ikon atau gambar representatif</li>
                                    <li>Set <strong>Status</strong> - pilih "Active" untuk mengaktifkan kategori</li>
                                    <li>Klik <strong>"Simpan Kategori"</strong></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Edit Kategori -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editCategory">
                                <i class="bi bi-pencil me-2"></i>Cara Edit Kategori
                            </button>
                        </h2>
                        <div id="editCategory" class="accordion-collapse collapse" data-bs-parent="#categoryGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>pensil (✏️)</strong> pada kategori yang ingin diedit</li>
                                    <li>Ubah informasi yang diperlukan</li>
                                    <li>Untuk mengganti gambar, upload file baru (akan mengganti yang lama)</li>
                                    <li>Klik <strong>"Update Kategori"</strong> untuk menyimpan perubahan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Hapus Kategori -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#deleteCategory">
                                <i class="bi bi-trash me-2"></i>Cara Hapus Kategori
                            </button>
                        </h2>
                        <div id="deleteCategory" class="accordion-collapse collapse" data-bs-parent="#categoryGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>tempat sampah (🗑️)</strong> pada kategori yang ingin dihapus</li>
                                    <li>Konfirmasi penghapusan di popup yang muncul</li>
                                    <li><strong>PERHATIAN:</strong> Pastikan tidak ada produk yang menggunakan kategori ini</li>
                                    <li>Kategori dan gambar terkait akan dihapus permanen</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Trik -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoryTips">
                                <i class="bi bi-lightbulb me-2"></i>Tips & Trik
                            </button>
                        </h2>
                        <div id="categoryTips" class="accordion-collapse collapse" data-bs-parent="#categoryGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Nama:</strong> Gunakan nama yang singkat, jelas, dan mudah diingat</li>
                                    <li><strong>Deskripsi:</strong> Jelaskan jenis produk yang termasuk dalam kategori</li>
                                    <li><strong>Gambar:</strong> Gunakan ikon atau gambar yang representatif</li>
                                    <li><strong>Status:</strong> "Active" untuk menampilkan kategori di website</li>
                                    <li><strong>Organisasi:</strong> Buat kategori yang logis untuk memudahkan navigasi</li>
                                    <li><strong>Hapus:</strong> Pastikan tidak ada produk yang menggunakan kategori sebelum dihapus</li>
                                    <li><strong>Hierarki:</strong> Pertimbangkan untuk membuat sub-kategori jika diperlukan</li>
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
