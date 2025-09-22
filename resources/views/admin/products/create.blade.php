@extends('admin.layout')

@section('title', 'Tambah Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Produk Baru</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk (Indonesia) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name_en" class="form-label">Product Name (English)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                               id="name_en" name="name_en" value="{{ old('name_en') }}">
                        @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Indonesia) <span class="text-danger">*</span></label>
                        <x-forms.tinymce-editor 
                            name="description" 
                            id="description"
                            value="{{ old('description') }}"
                            placeholder="Masukkan deskripsi produk dalam bahasa Indonesia..."
                            :required="true"
                        />
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description_en" class="form-label">Description (English)</label>
                        <x-forms.tinymce-editor 
                            name="description_en" 
                            id="description_en"
                            value="{{ old('description_en') }}"
                            placeholder="Enter product description in English..."
                        />
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Deskripsi Singkat (Indonesia)</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description_en" class="form-label">Short Description (English)</label>
                        <textarea class="form-control @error('short_description_en') is-invalid @enderror" 
                                  id="short_description_en" name="short_description_en" rows="3">{{ old('short_description_en') }}</textarea>
                        @error('short_description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label for="video" class="form-label">Video Produk</label>
                        <input type="file" class="form-control @error('video') is-invalid @enderror" 
                               id="video" name="video" accept="video/*">
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: MP4, AVI, MOV, WMV, FLV, WEBM. Maksimal 50MB.</div>
                    </div>

                    <div class="mb-3">
                        <label for="shopee_url" class="form-label">URL Shopee</label>
                        <input type="url" class="form-control @error('shopee_url') is-invalid @enderror" 
                               id="shopee_url" name="shopee_url" value="{{ old('shopee_url') }}" placeholder="https://shopee.co.id/your_____studio">
                        @error('shopee_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tiktok_url" class="form-label">URL TikTok Shop</label>
                        <input type="url" class="form-control @error('tiktok_url') is-invalid @enderror" 
                               id="tiktok_url" name="tiktok_url" value="{{ old('tiktok_url') }}" placeholder="https://www.tiktok.com/@your_____studio">
                        @error('tiktok_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Produk Unggulan
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-save me-2"></i>Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation and confirmation
    confirmSubmit('productForm', 'Konfirmasi Simpan Produk', 'Apakah Anda yakin ingin menyimpan produk ini?');
    
    // Add real-time validation
    const form = document.getElementById('productForm');
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
});
</script>
@endsection
