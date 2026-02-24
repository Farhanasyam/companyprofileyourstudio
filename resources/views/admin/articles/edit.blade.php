@extends('admin.layout')

@section('title', 'Edit Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Artikel</h2>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <h6 class="alert-heading">Terdapat kesalahan dalam form:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Data untuk TinyMCE: isi editor di-set via JS setelah init (agar HTML dari TinyMCE tidak di-escape) --}}
        <script>
            window.__articleInitial = {
                excerpt: {!! json_encode(old('excerpt', $article->excerpt ?? '')) !!},
                content: {!! json_encode(old('content', $article->content ?? '')) !!}
            };
        </script>

        <form id="articleEditForm" action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $article->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Ringkasan</label>
                        <x-forms.tinymce-editor 
                            name="excerpt" 
                            id="excerpt"
                            value=""
                            placeholder="Masukkan ringkasan artikel..."
                        />
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                        <x-forms.tinymce-editor 
                            name="content" 
                            id="content"
                            value=""
                            placeholder="Masukkan konten artikel lengkap..."
                            :required="true"
                        />
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                               id="tags" name="tags" value="{{ old('tags', is_array($article->tags) ? implode(', ', $article->tags) : $article->tags) }}" 
                               placeholder="Pisahkan dengan koma (contoh: tutorial, tips, review)">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Pisahkan setiap tag dengan koma</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Gambar Utama</label>
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        
                        @if($article->featured_image)
                            <div class="mt-2">
                                <img src="{{ asset($article->featured_image_url) }}" 
                                     alt="{{ $article->title }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                                <p class="small text-muted mt-1">Gambar saat ini</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="published_at" class="form-label">Tanggal Publikasi</label>
                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                               id="published_at" name="published_at" 
                               value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kosongkan untuk publikasi langsung</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                   {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Artikel Unggulan
                            </label>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">SEO Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $article->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Informasi Artikel</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Slug:</strong> <code>{{ $article->slug }}</code></p>
                <p><strong>Penulis:</strong> {{ $article->user->name }}</p>
                <p><strong>Dibuat:</strong> {{ $article->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Diperbarui:</strong> {{ $article->updated_at->format('d M Y H:i') }}</p>
                <p><strong>ID:</strong> <code>{{ $article->id }}</code></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Isi TinyMCE dari data server setelah editor siap (referensi pola produk / HTML tidak di-escape di textarea)
    if (window.__articleInitial) {
        setTimeout(function() {
            if (typeof tinymce !== 'undefined') {
                var ex = tinymce.get('excerpt');
                var co = tinymce.get('content');
                if (ex) ex.setContent(window.__articleInitial.excerpt || '');
                if (co) co.setContent(window.__articleInitial.content || '');
            }
        }, 600);
    }

    // Initialize form validation and confirmation
    confirmSubmit('articleEditForm', 'Konfirmasi Update Artikel', 'Apakah Anda yakin ingin mengupdate artikel ini?');
    
    // Add real-time validation
    const form = document.getElementById('articleEditForm');
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(function(field) {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
                showWarningToast(`${this.name} wajib diisi`);
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Ensure TinyMCE content is saved before form submission
    form.addEventListener('submit', function(e) {
        // Save TinyMCE content before form submission
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }
        
        // Validate required fields
        let hasErrors = false;
        const titleField = document.getElementById('title');
        const statusField = document.getElementById('status');
        
        if (!titleField.value.trim()) {
            titleField.classList.add('is-invalid');
            hasErrors = true;
        }
        
        if (!statusField.value) {
            statusField.classList.add('is-invalid');
            hasErrors = true;
        }
        
        // Check TinyMCE content
        const contentField = document.getElementById('content');
        if (contentField && !contentField.value.trim()) {
            contentField.classList.add('is-invalid');
            hasErrors = true;
        }
        
        if (hasErrors) {
            e.preventDefault();
            showErrorToast('Mohon lengkapi semua field yang wajib diisi');
            return false;
        }
    });
});
</script>
@endsection
