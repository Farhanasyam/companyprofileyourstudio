@extends('admin.layout')

@section('title', 'Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Artikel</h2>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Artikel
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($articles->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Views</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($article->featured_image)
                                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                                 alt="{{ $article->title }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $article->title }}</strong>
                                            @if($article->tags)
                                                <br>
                                                <small class="text-muted">
                                                    @foreach($article->tags as $tag)
                                                        <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                                                    @endforeach
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $article->user->name }}</td>
                                <td>
                                    <span class="badge {{ $article->status === 'published' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($article->is_featured)
                                        <span class="badge bg-warning">Featured</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $article->views }}</span>
                                </td>
                                <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.articles.show', $article) }}" 
                                           class="btn btn-sm btn-outline-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
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
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-newspaper fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">Belum ada artikel</h4>
                <p class="text-muted">Mulai dengan menambahkan artikel pertama Anda</p>
                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Artikel
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Panduan CRUD Artikel -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan CRUD Artikel
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="articleGuideAccordion">
                    <!-- Cara Membuat Artikel -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createArticle">
                                <i class="bi bi-plus-circle me-2"></i>Cara Membuat Artikel Baru
                            </button>
                        </h2>
                        <div id="createArticle" class="accordion-collapse collapse" data-bs-parent="#articleGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik tombol <strong>"Tambah Artikel"</strong> di halaman ini</li>
                                    <li>Isi <strong>Judul Artikel</strong> (wajib) - judul yang menarik dan SEO-friendly</li>
                                    <li>Isi <strong>Slug</strong> (otomatis) - URL artikel, akan dibuat otomatis dari judul</li>
                                    <li>Isi <strong>Excerpt</strong> - ringkasan artikel dalam 1-2 paragraf</li>
                                    <li>Isi <strong>Konten</strong> (wajib) - isi lengkap artikel menggunakan editor</li>
                                    <li>Upload <strong>Gambar Featured</strong> (opsional) - gambar utama artikel</li>
                                    <li>Set <strong>Status</strong> - pilih "Published" untuk tampil di website</li>
                                    <li>Centang <strong>"Featured"</strong> jika artikel unggulan</li>
                                    <li>Klik <strong>"Simpan Artikel"</strong></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Edit Artikel -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editArticle">
                                <i class="bi bi-pencil me-2"></i>Cara Edit Artikel
                            </button>
                        </h2>
                        <div id="editArticle" class="accordion-collapse collapse" data-bs-parent="#articleGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>pensil (✏️)</strong> pada artikel yang ingin diedit</li>
                                    <li>Ubah informasi yang diperlukan</li>
                                    <li>Untuk mengganti gambar, upload file baru (akan mengganti yang lama)</li>
                                    <li>Klik <strong>"Update Artikel"</strong> untuk menyimpan perubahan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Hapus Artikel -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#deleteArticle">
                                <i class="bi bi-trash me-2"></i>Cara Hapus Artikel
                            </button>
                        </h2>
                        <div id="deleteArticle" class="accordion-collapse collapse" data-bs-parent="#articleGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>tempat sampah (🗑️)</strong> pada artikel yang ingin dihapus</li>
                                    <li>Konfirmasi penghapusan di popup yang muncul</li>
                                    <li>Artikel dan gambar terkait akan dihapus permanen</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Trik -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#articleTips">
                                <i class="bi bi-lightbulb me-2"></i>Tips & Trik
                            </button>
                        </h2>
                        <div id="articleTips" class="accordion-collapse collapse" data-bs-parent="#articleGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Judul:</strong> Gunakan kata kunci yang relevan dan menarik</li>
                                    <li><strong>Excerpt:</strong> Buat ringkasan yang menarik untuk preview artikel</li>
                                    <li><strong>Konten:</strong> Gunakan editor untuk format teks, gambar, dan link</li>
                                    <li><strong>Gambar:</strong> Gunakan gambar berkualitas tinggi (min 800x600px)</li>
                                    <li><strong>Status:</strong> "Draft" untuk menyimpan sementara, "Published" untuk tampil di website</li>
                                    <li><strong>Featured:</strong> Gunakan untuk artikel unggulan yang ingin ditonjolkan</li>
                                    <li><strong>SEO:</strong> Judul yang baik akan membantu SEO website</li>
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
