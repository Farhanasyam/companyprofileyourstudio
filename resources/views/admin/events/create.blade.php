@extends('admin.layout')

@section('title', 'Tambah Event')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-plus-circle me-2"></i>Tambah Event Baru
    </h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>Informasi Event
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" id="eventForm">
                    @csrf
                    
                    <!-- Hidden field to ensure status is always sent -->
                    <input type="hidden" name="status" value="published" id="hiddenStatus">
                    
                    <!-- Hidden fields for boolean values -->
                    <input type="hidden" name="is_featured" value="0" id="hiddenFeatured">
                    <input type="hidden" name="is_active" value="1" id="hiddenActive">

                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Judul Event (Indonesia) <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_en" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Event Title (English)
                                </label>
                                <input type="text" 
                                       class="form-control @error('title_en') is-invalid @enderror" 
                                       id="title_en" 
                                       name="title_en" 
                                       value="{{ old('title_en') }}">
                                @error('title_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="short_description" class="form-label">
                                    <i class="bi bi-text-paragraph me-1"></i>Deskripsi Singkat (Indonesia)
                                </label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                          id="short_description" 
                                          name="short_description" 
                                          rows="3" 
                                          maxlength="500">{{ old('short_description') }}</textarea>
                                <div class="form-text">Maksimal 500 karakter</div>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="short_description_en" class="form-label">
                                    <i class="bi bi-text-paragraph me-1"></i>Short Description (English)
                                </label>
                                <textarea class="form-control @error('short_description_en') is-invalid @enderror" 
                                          id="short_description_en" 
                                          name="short_description_en" 
                                          rows="3" 
                                          maxlength="500">{{ old('short_description_en') }}</textarea>
                                <div class="form-text">Maksimal 500 karakter</div>
                                @error('short_description_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="bi bi-file-text me-1"></i>Deskripsi Lengkap (Indonesia) <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="6" 
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description_en" class="form-label">
                                    <i class="bi bi-file-text me-1"></i>Full Description (English)
                                </label>
                                <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                          id="description_en" 
                                          name="description_en" 
                                          rows="6">{{ old('description_en') }}</textarea>
                                @error('description_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">
                                    <i class="bi bi-calendar-event me-1"></i>Tanggal & Waktu Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="{{ old('start_date') }}" 
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">
                                    <i class="bi bi-calendar-check me-1"></i>Tanggal & Waktu Selesai
                                </label>
                                <input type="datetime-local" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location" class="form-label">
                                    <i class="bi bi-geo-alt me-1"></i>Lokasi (Indonesia)
                                </label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location_en" class="form-label">
                                    <i class="bi bi-geo-alt me-1"></i>Location (English)
                                </label>
                                <input type="text" 
                                       class="form-control @error('location_en') is-invalid @enderror" 
                                       id="location_en" 
                                       name="location_en" 
                                       value="{{ old('location_en') }}">
                                @error('location_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <!-- Media Upload -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-image me-2"></i>Media
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Main Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="bi bi-image me-1"></i>Gambar Utama
                                </label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video -->
                            <div class="mb-3">
                                <label for="video" class="form-label">
                                    <i class="bi bi-play-circle me-1"></i>Video
                                </label>
                                <input type="file" 
                                       class="form-control @error('video') is-invalid @enderror" 
                                       id="video" 
                                       name="video" 
                                       accept="video/*">
                                <div class="form-text">Format: MP4, AVI, MOV. Maksimal 10MB</div>
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gallery -->
                            <div class="mb-3">
                                <label for="gallery" class="form-label">
                                    <i class="bi bi-images me-1"></i>Galeri
                                </label>
                                <input type="file" 
                                       class="form-control @error('gallery.*') is-invalid @enderror" 
                                       id="gallery" 
                                       name="gallery[]" 
                                       multiple 
                                       accept="image/*,video/*">
                                <div class="form-text">Pilih multiple file untuk galeri. Format: JPEG, PNG, JPG, GIF, MP4, AVI, MOV. Maksimal 10MB per file</div>
                                @error('gallery.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-search me-2"></i>SEO Settings
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title (Indonesia)</label>
                                        <input type="text" 
                                               class="form-control @error('meta_title') is-invalid @enderror" 
                                               id="meta_title" 
                                               name="meta_title" 
                                               value="{{ old('meta_title') }}" 
                                               maxlength="255">
                                        @error('meta_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="meta_title_en" class="form-label">Meta Title (English)</label>
                                        <input type="text" 
                                               class="form-control @error('meta_title_en') is-invalid @enderror" 
                                               id="meta_title_en" 
                                               name="meta_title_en" 
                                               value="{{ old('meta_title_en') }}" 
                                               maxlength="255">
                                        @error('meta_title_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description (Indonesia)</label>
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                  id="meta_description" 
                                                  name="meta_description" 
                                                  rows="3" 
                                                  maxlength="500">{{ old('meta_description') }}</textarea>
                                        @error('meta_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="meta_description_en" class="form-label">Meta Description (English)</label>
                                        <textarea class="form-control @error('meta_description_en') is-invalid @enderror" 
                                                  id="meta_description_en" 
                                                  name="meta_description_en" 
                                                  rows="3" 
                                                  maxlength="500">{{ old('meta_description_en') }}</textarea>
                                        @error('meta_description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Pengaturan Event
                </h6>
            </div>
            <div class="card-body">
                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">
                        <i class="bi bi-flag me-1"></i>Status <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="draft" {{ old('status', 'published') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="cancelled" {{ old('status', 'published') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Options -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1" 
                               @if(old('is_featured')) checked @endif>
                        <label class="form-check-label" for="is_featured">
                            <i class="bi bi-star me-1"></i>Featured Event
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               @if(old('is_active', true)) checked @endif>
                        <label class="form-check-label" for="is_active">
                            <i class="bi bi-toggle-on me-1"></i>Active
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panduan Membuat Event -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan Membuat Event
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>Langkah-langkah Membuat Event:</h6>
                    <ol class="mb-0">
                        <li><strong>Judul Event:</strong> Wajib diisi, gunakan judul yang menarik dan deskriptif</li>
                        <li><strong>Deskripsi:</strong> Jelaskan detail event, manfaat, dan apa yang akan dipelajari</li>
                        <li><strong>Tanggal:</strong> Pastikan tanggal mulai di masa depan</li>
                        <li><strong>Lokasi:</strong> Isi tempat event diadakan</li>
                        <li><strong>Media:</strong> Upload gambar/video untuk menarik perhatian</li>
                        <li><strong>Status:</strong> Pilih "Published" agar tampil di website</li>
                    </ol>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-lightbulb text-warning me-2"></i>Tips Sukses:</h6>
                        <ul class="small mb-0">
                            <li>Gunakan gambar berkualitas tinggi (min 800x600px)</li>
                            <li>Judul maksimal 255 karakter</li>
                            <li>Deskripsi singkat maksimal 500 karakter</li>
                            <li>Format gambar: JPEG, PNG, JPG, GIF</li>
                            <li>Format video: MP4, AVI, MOV</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-exclamation-triangle text-danger me-2"></i>Perhatian:</h6>
                        <ul class="small mb-0">
                            <li>File gambar maksimal 2MB</li>
                            <li>File video maksimal 10MB</li>
                            <li>Upload file baru akan mengganti yang lama</li>
                            <li>Event dengan status "Draft" tidak tampil di website</li>
                            <li>Centang "Featured" untuk event unggulan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Set minimum date for start_date
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const statusSelect = document.getElementById('status');
        const hiddenStatus = document.getElementById('hiddenStatus');
        const featuredCheckbox = document.getElementById('is_featured');
        const activeCheckbox = document.getElementById('is_active');
        const hiddenFeatured = document.getElementById('hiddenFeatured');
        const hiddenActive = document.getElementById('hiddenActive');
        
        // Set minimum date to current date
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        startDateInput.min = minDateTime;
        
        // Update end_date minimum when start_date changes
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });
        
        // Sync status select with hidden field
        statusSelect.addEventListener('change', function() {
            hiddenStatus.value = this.value;
        });
        
        // Sync checkbox with hidden fields
        featuredCheckbox.addEventListener('change', function() {
            hiddenFeatured.value = this.checked ? '1' : '0';
        });
        
        activeCheckbox.addEventListener('change', function() {
            hiddenActive.value = this.checked ? '1' : '0';
        });
        
        // Initialize hidden fields with current values
        hiddenStatus.value = statusSelect.value;
        hiddenFeatured.value = featuredCheckbox.checked ? '1' : '0';
        hiddenActive.value = activeCheckbox.checked ? '1' : '0';
    });
</script>
@endsection
