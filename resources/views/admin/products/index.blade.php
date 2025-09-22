@extends('admin.layout')

@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Produk</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Produk
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->is_featured)
                                        <span class="badge bg-warning ms-2">Featured</span>
                                    @endif
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="btn btn-sm btn-outline-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Hapus"
                                                onclick="confirmDelete('{{ route('admin.products.destroy', $product) }}', 'Produk', 'Apakah Anda yakin ingin menghapus produk {{ $product->name }}?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-box fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">Belum ada produk</h4>
                <p class="text-muted">Mulai dengan menambahkan produk pertama Anda</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Panduan CRUD Produk -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan CRUD Produk
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="productGuideAccordion">
                    <!-- Cara Membuat Produk -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createProduct">
                                <i class="bi bi-plus-circle me-2"></i>Cara Membuat Produk Baru
                            </button>
                        </h2>
                        <div id="createProduct" class="accordion-collapse collapse" data-bs-parent="#productGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik tombol <strong>"Tambah Produk"</strong> di halaman ini</li>
                                    <li>Isi <strong>Nama Produk</strong> (wajib) - nama yang menarik dan deskriptif</li>
                                    <li>Pilih <strong>Kategori</strong> (wajib) - pilih kategori yang sesuai</li>
                                    <li>Isi <strong>Deskripsi Singkat</strong> - ringkasan produk dalam 1-2 kalimat</li>
                                    <li>Isi <strong>Deskripsi Lengkap</strong> (wajib) - detail lengkap tentang produk</li>
                                    <li>Set <strong>Harga</strong> - harga produk dalam Rupiah</li>
                                    <li>Upload <strong>Gambar Utama</strong> (opsional) - gambar representatif produk</li>
                                    <li>Upload <strong>Galeri</strong> (opsional) - multiple gambar produk</li>
                                    <li>Set <strong>Status</strong> - pilih "Published" untuk tampil di website</li>
                                    <li>Centang <strong>"Featured"</strong> jika produk unggulan</li>
                                    <li>Klik <strong>"Simpan Produk"</strong></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Edit Produk -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editProduct">
                                <i class="bi bi-pencil me-2"></i>Cara Edit Produk
                            </button>
                        </h2>
                        <div id="editProduct" class="accordion-collapse collapse" data-bs-parent="#productGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>pensil (✏️)</strong> pada produk yang ingin diedit</li>
                                    <li>Ubah informasi yang diperlukan</li>
                                    <li>Untuk mengganti gambar, upload file baru (akan mengganti yang lama)</li>
                                    <li>Klik <strong>"Update Produk"</strong> untuk menyimpan perubahan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Hapus Produk -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#deleteProduct">
                                <i class="bi bi-trash me-2"></i>Cara Hapus Produk
                            </button>
                        </h2>
                        <div id="deleteProduct" class="accordion-collapse collapse" data-bs-parent="#productGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>tempat sampah (🗑️)</strong> pada produk yang ingin dihapus</li>
                                    <li>Konfirmasi penghapusan di popup yang muncul</li>
                                    <li>Produk dan semua gambar terkait akan dihapus permanen</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Trik -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#productTips">
                                <i class="bi bi-lightbulb me-2"></i>Tips & Trik
                            </button>
                        </h2>
                        <div id="productTips" class="accordion-collapse collapse" data-bs-parent="#productGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Nama:</strong> Gunakan kata kunci yang menarik dan mudah diingat</li>
                                    <li><strong>Deskripsi:</strong> Jelaskan fitur, manfaat, dan spesifikasi produk</li>
                                    <li><strong>Harga:</strong> Format harga dalam Rupiah (contoh: 100000)</li>
                                    <li><strong>Gambar:</strong> Gunakan gambar berkualitas tinggi (min 800x600px)</li>
                                    <li><strong>Kategori:</strong> Pilih kategori yang tepat untuk memudahkan pencarian</li>
                                    <li><strong>Status:</strong> "Draft" untuk menyimpan sementara, "Published" untuk tampil di website</li>
                                    <li><strong>Featured:</strong> Gunakan untuk produk unggulan yang ingin ditonjolkan</li>
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
