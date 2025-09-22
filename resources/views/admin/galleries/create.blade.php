@extends('admin.layout')

@section('title', 'Tambah Galeri')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Galeri</h2>
    <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="hero" {{ old('type') === 'hero' ? 'selected' : '' }}>Hero Section</option>
                            <option value="about" {{ old('type') === 'about' ? 'selected' : '' }}>About Page</option>
                            <option value="product" {{ old('type') === 'product' ? 'selected' : '' }}>Product Page</option>
                            <option value="gallery" {{ old('type') === 'gallery' ? 'selected' : '' }}>Gallery</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Urutan</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                               id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Preview -->
            <div class="mb-3">
                <label class="form-label">Preview Gambar</label>
                <div id="imagePreview" class="border rounded p-3 text-center" style="min-height: 200px; display: none;">
                    <img id="previewImg" src="" alt="Preview" class="img-fluid" style="max-height: 200px;">
                </div>
                <div id="noPreview" class="border rounded p-3 text-center text-muted" style="min-height: 200px;">
                    <i class="bi bi-image fs-1"></i>
                    <p class="mt-2">Pilih gambar untuk melihat preview</p>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Simpan Galeri
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Info Card -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Informasi Tipe Galeri</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-star me-2"></i>Hero Section</h6>
                <p class="text-muted small">Gambar untuk hero section di homepage. Direkomendasikan ukuran 1200x600px.</p>
                
                <h6><i class="bi bi-info-circle me-2"></i>About Page</h6>
                <p class="text-muted small">Gambar untuk halaman tentang kami. Direkomendasikan ukuran 800x600px.</p>
            </div>
            <div class="col-md-6">
                <h6><i class="bi bi-box me-2"></i>Product Page</h6>
                <p class="text-muted small">Gambar untuk halaman produk. Direkomendasikan ukuran 600x600px.</p>
                
                <h6><i class="bi bi-images me-2"></i>Gallery</h6>
                <p class="text-muted small">Gambar untuk galeri umum. Direkomendasikan ukuran 800x600px.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const noPreview = document.getElementById('noPreview');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
                noPreview.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
            noPreview.style.display = 'block';
        }
    });
});
</script>
@endsection
