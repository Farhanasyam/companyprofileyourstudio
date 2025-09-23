@extends('admin.layout')

@section('title', 'Edit Section Tentang Kami')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Section Tentang Kami</h2>
    <a href="{{ route('admin.about-us.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<!-- Panduan -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Edit Section</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-lightbulb text-warning me-2"></i>Tips Edit:</h6>
                <ul class="small">
                    <li><strong>Section:</strong> Tidak bisa diubah setelah dibuat</li>
                    <li><strong>Judul:</strong> Gunakan judul yang menarik dan SEO-friendly</li>
                    <li><strong>Konten:</strong> Tulis dengan jelas dan mudah dipahami</li>
                    <li><strong>Features:</strong> Maksimal 3-4 features untuk "Why Choose Us"</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="bi bi-gear text-primary me-2"></i>Pengaturan:</h6>
                <ul class="small">
                    <li><strong>Urutan:</strong> 1-10 untuk urutan tampilan</li>
                    <li><strong>Aktif:</strong> Uncheck untuk menyembunyikan sementara</li>
                    <li><strong>Icon:</strong> Contoh: bi-award, bi-people, bi-heart, bi-star</li>
                    <li><strong>Warna:</strong> primary, success, warning, danger, info</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.about-us.update', $aboutUs) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="section" class="form-label">Section <span class="text-danger">*</span></label>
                        <select class="form-select @error('section') is-invalid @enderror" id="section" name="section" required>
                            <option value="">Pilih Section</option>
                            @foreach($sections as $key => $label)
                                <option value="{{ $key }}" {{ old('section', $aboutUs->section) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Urutan</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                               id="sort_order" name="sort_order" value="{{ old('sort_order', $aboutUs->sort_order) }}" min="0">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul (Indonesia)</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $aboutUs->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title_en" class="form-label">Judul (English)</label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                               id="title_en" name="title_en" value="{{ old('title_en', $aboutUs->title_en) }}">
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Sub Judul (Indonesia)</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" 
                               id="subtitle" name="subtitle" value="{{ old('subtitle', $aboutUs->subtitle) }}">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subtitle_en" class="form-label">Sub Judul (English)</label>
                        <input type="text" class="form-control @error('subtitle_en') is-invalid @enderror" 
                               id="subtitle_en" name="subtitle_en" value="{{ old('subtitle_en', $aboutUs->subtitle_en) }}">
                        @error('subtitle_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="content" class="form-label">Konten Utama (Indonesia)</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="4">{{ old('content', $aboutUs->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="content_en" class="form-label">Konten Utama (English)</label>
                        <textarea class="form-control @error('content_en') is-invalid @enderror" 
                                  id="content_en" name="content_en" rows="4">{{ old('content_en', $aboutUs->content_en) }}</textarea>
                        @error('content_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Indonesia)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $aboutUs->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description_en" class="form-label">Deskripsi (English)</label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                  id="description_en" name="description_en" rows="3">{{ old('description_en', $aboutUs->description_en) }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features-section" class="mb-3" style="display: none;">
                <label class="form-label">Features</label>
                <div id="features-container">
                    <!-- Features akan ditambahkan secara dinamis -->
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-feature">
                    <i class="bi bi-plus me-1"></i>Tambah Feature
                </button>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                           {{ old('is_active', $aboutUs->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.about-us.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('section');
    const featuresSection = document.getElementById('features-section');
    const featuresContainer = document.getElementById('features-container');
    const addFeatureBtn = document.getElementById('add-feature');
    
    let featureCount = 0;
    const existingFeatures = @json($aboutUs->features ?? []);
    
    // Toggle features section based on selected section
    sectionSelect.addEventListener('change', function() {
        if (this.value === 'why_choose_us') {
            featuresSection.style.display = 'block';
            loadExistingFeatures();
        } else {
            featuresSection.style.display = 'none';
            featuresContainer.innerHTML = '';
            featureCount = 0;
        }
    });
    
    // Load existing features
    function loadExistingFeatures() {
        if (existingFeatures && existingFeatures.length > 0) {
            existingFeatures.forEach((feature, index) => {
                const featureEn = @json($aboutUs->features_en ?? [])[index] || {};
                addFeature(feature, featureEn, index);
            });
        }
    }
    
    // Add feature
    function addFeature(feature = null, featureEn = null, index = null) {
        const currentIndex = index !== null ? index : featureCount;
        const featureData = feature || {};
        const featureEnData = featureEn || {};
        
        const featureHtml = `
            <div class="feature-item border p-3 mb-3 rounded">
                <h6 class="text-primary mb-3">Feature ${currentIndex + 1}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Judul Feature (Indonesia)</label>
                        <input type="text" class="form-control" name="features[${currentIndex}][title]" 
                               value="${featureData.title || ''}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Judul Feature (English)</label>
                        <input type="text" class="form-control" name="features_en[${currentIndex}][title]" 
                               value="${featureEnData.title || ''}" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Deskripsi (Indonesia)</label>
                        <textarea class="form-control" name="features[${currentIndex}][description]" rows="2" required>${featureData.description || ''}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Deskripsi (English)</label>
                        <textarea class="form-control" name="features_en[${currentIndex}][description]" rows="2" required>${featureEnData.description || ''}</textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label class="form-label">Icon (Bootstrap Icons)</label>
                        <input type="text" class="form-control" name="features[${currentIndex}][icon]" 
                               value="${featureData.icon || ''}" placeholder="bi-award">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Warna</label>
                        <select class="form-select" name="features[${currentIndex}][color]">
                            <option value="primary" ${featureData.color === 'primary' ? 'selected' : ''}>Primary</option>
                            <option value="secondary" ${featureData.color === 'secondary' ? 'selected' : ''}>Secondary</option>
                            <option value="success" ${featureData.color === 'success' ? 'selected' : ''}>Success</option>
                            <option value="danger" ${featureData.color === 'danger' ? 'selected' : ''}>Danger</option>
                            <option value="warning" ${featureData.color === 'warning' ? 'selected' : ''}>Warning</option>
                            <option value="info" ${featureData.color === 'info' ? 'selected' : ''}>Info</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-sm btn-outline-danger d-block w-100 remove-feature">
                            <i class="bi bi-trash"></i> Hapus Feature
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        featuresContainer.insertAdjacentHTML('beforeend', featureHtml);
        if (index === null) featureCount++;
    }
    
    // Add new feature button
    addFeatureBtn.addEventListener('click', function() {
        addFeature();
    });
    
    // Remove feature
    featuresContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });
    
    // Trigger change event on page load if section is already selected
    if (sectionSelect.value) {
        sectionSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
