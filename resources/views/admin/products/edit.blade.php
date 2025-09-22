@extends('admin.layout')

@section('title', 'Edit Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Produk</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<!-- Error Display Section -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h6><i class="bi bi-exclamation-triangle me-2"></i>Validation Errors:</h6>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Debug Information -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Debug Information</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Product ID:</strong> <code>{{ $product->id }}</code></p>
                <p><strong>Product Slug:</strong> <code>{{ $product->slug }}</code></p>
                <p><strong>Form Action:</strong> <code>{{ route('admin.products.update', $product) }}</code></p>
            </div>
            <div class="col-md-6">
                <p><strong>CSRF Token:</strong> <code>{{ csrf_token() }}</code></p>
                <p><strong>Method Field:</strong> <code>PUT</code></p>
                <p><strong>Current User:</strong> {{ auth()->user()->name ?? 'Not logged in' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="productEditForm" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk (Indonesia) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name_en" class="form-label">Product Name (English)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                               id="name_en" name="name_en" value="{{ old('name_en', $product->name_en) }}">
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
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                            value="{{ old('description', $product->description) }}"
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
                            value="{{ old('description_en', $product->description_en) }}"
                            placeholder="Enter product description in English..."
                        />
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Deskripsi Singkat (Indonesia)</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description_en" class="form-label">Short Description (English)</label>
                        <textarea class="form-control @error('short_description_en') is-invalid @enderror" 
                                  id="short_description_en" name="short_description_en" rows="3">{{ old('short_description_en', $product->short_description_en) }}</textarea>
                        @error('short_description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px;">
                                <p class="text-muted small">Gambar saat ini</p>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                    </div>

                    <div class="mb-3">
                        <label for="video" class="form-label">Video Produk</label>
                        @if($product->video)
                            <div class="mb-2">
                                <video controls class="img-thumbnail" style="max-width: 200px;">
                                    <source src="{{ $product->video_url }}" type="video/mp4">
                                    Browser Anda tidak mendukung video.
                                </video>
                                <p class="text-muted small">Video saat ini</p>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('video') is-invalid @enderror" 
                               id="video" name="video" accept="video/*">
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: MP4, AVI, MOV, WMV, FLV, WEBM. Maksimal 50MB. Kosongkan jika tidak ingin mengubah video.</div>
                    </div>

                    <div class="mb-3">
                        <label for="shopee_url" class="form-label">URL Shopee</label>
                        <input type="url" class="form-control @error('shopee_url') is-invalid @enderror" 
                               id="shopee_url" name="shopee_url" value="{{ old('shopee_url', $product->shopee_url) }}" placeholder="https://shopee.co.id/your_____studio">
                        @error('shopee_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tiktok_url" class="form-label">URL TikTok Shop</label>
                        <input type="url" class="form-control @error('tiktok_url') is-invalid @enderror" 
                               id="tiktok_url" name="tiktok_url" value="{{ old('tiktok_url', $product->tiktok_url) }}" placeholder="https://www.tiktok.com/@your_____studio">
                        @error('tiktok_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Produk Unggulan
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
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
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Informasi Produk</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Slug:</strong> <code>{{ $product->slug }}</code></p>
                <p><strong>Dibuat:</strong> {{ $product->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Diperbarui:</strong> {{ $product->updated_at->format('d M Y H:i') }}</p>
                <p><strong>ID:</strong> <code>{{ $product->id }}</code></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation and confirmation
    confirmSubmit('productEditForm', 'Konfirmasi Update Produk', 'Apakah Anda yakin ingin mengupdate produk ini?');
    
    // Add real-time validation
    const form = document.getElementById('productEditForm');
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
