@extends('admin.layout')

@section('title', 'Tambah Section Tentang Kami')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Section Tentang Kami</h2>
    <a href="{{ route('admin.about-us.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<!-- Panduan -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Membuat Section</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-lightbulb text-warning me-2"></i>Tips Section:</h6>
                <ul class="small">
                    <li><strong>Hero:</strong> Judul dan subtitle utama halaman</li>
                    <li><strong>History:</strong> Sejarah dan latar belakang perusahaan</li>
                    <li><strong>Vision:</strong> Visi perusahaan (singkat dan jelas)</li>
                    <li><strong>Mission:</strong> Misi perusahaan (singkat dan jelas)</li>
                    <li><strong>Why Choose Us:</strong> Keunggulan dengan multiple features</li>
                    <li><strong>Contact Info:</strong> Informasi kontak dan ajakan</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><i class="bi bi-gear text-primary me-2"></i>Pengaturan:</h6>
                <ul class="small">
                    <li><strong>Urutan:</strong> Angka kecil = tampil di atas</li>
                    <li><strong>Aktif:</strong> Centang untuk menampilkan di public</li>
                    <li><strong>Features:</strong> Hanya untuk section "Why Choose Us"</li>
                    <li><strong>Icon:</strong> Gunakan Bootstrap Icons (bi-award, bi-people, dll)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.about-us.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="section" class="form-label">Section <span class="text-danger">*</span></label>
                        <select class="form-select @error('section') is-invalid @enderror" id="section" name="section" required>
                            <option value="">Pilih Section</option>
                            @foreach($sections as $key => $label)
                                <option value="{{ $key }}" {{ old('section') == $key ? 'selected' : '' }}>
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
                               id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
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
                               id="title" name="title" value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title_en" class="form-label">Judul (English)</label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                               id="title_en" name="title_en" value="{{ old('title_en') }}">
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
                               id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subtitle_en" class="form-label">Sub Judul (English)</label>
                        <input type="text" class="form-control @error('subtitle_en') is-invalid @enderror" 
                               id="subtitle_en" name="subtitle_en" value="{{ old('subtitle_en') }}">
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
                                  id="content" name="content" rows="4">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="content_en" class="form-label">Konten Utama (English)</label>
                        <textarea class="form-control @error('content_en') is-invalid @enderror" 
                                  id="content_en" name="content_en" rows="4">{{ old('content_en') }}</textarea>
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
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description_en" class="form-label">Deskripsi (English)</label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                  id="description_en" name="description_en" rows="3">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Features Section (akan ditampilkan jika section = why_choose_us) -->
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
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.about-us.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Simpan
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
    
    // Toggle features section based on selected section
    sectionSelect.addEventListener('change', function() {
        if (this.value === 'why_choose_us') {
            featuresSection.style.display = 'block';
        } else {
            featuresSection.style.display = 'none';
            featuresContainer.innerHTML = '';
            featureCount = 0;
        }
    });
    
    // Add feature
    addFeatureBtn.addEventListener('click', function() {
        const featureHtml = `
            <div class="feature-item border p-3 mb-3 rounded">
                <h6 class="text-primary mb-3">Feature ${featureCount + 1}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Judul Feature (Indonesia)</label>
                        <input type="text" class="form-control" name="features[${featureCount}][title]" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Judul Feature (English)</label>
                        <input type="text" class="form-control" name="features_en[${featureCount}][title]" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Deskripsi (Indonesia)</label>
                        <textarea class="form-control" name="features[${featureCount}][description]" rows="2" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Deskripsi (English)</label>
                        <textarea class="form-control" name="features_en[${featureCount}][description]" rows="2" required></textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label class="form-label">Icon (Bootstrap Icons)</label>
                        <input type="text" class="form-control" name="features[${featureCount}][icon]" placeholder="bi-award">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Warna</label>
                        <select class="form-select" name="features[${featureCount}][color]">
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="success">Success</option>
                            <option value="danger">Danger</option>
                            <option value="warning">Warning</option>
                            <option value="info">Info</option>
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
        featureCount++;
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
