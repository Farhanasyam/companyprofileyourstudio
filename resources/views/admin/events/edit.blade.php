@extends('admin.layout')

@section('title', 'Edit Event')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-pencil me-2"></i>Edit Event: {{ $event->title }}
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

                <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" id="eventForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden field to ensure status is always sent -->
                    <input type="hidden" name="status" value="{{ $event->status }}" id="hiddenStatus">
                    
                    <!-- Hidden fields for boolean values -->
                    <input type="hidden" name="is_featured" value="0" id="hiddenFeatured">
                    <input type="hidden" name="is_active" value="0" id="hiddenActive">

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
                                       value="{{ old('title', $event->title) }}" 
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
                                       value="{{ old('title_en', $event->title_en) }}">
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
                                          maxlength="500">{{ old('short_description', $event->short_description) }}</textarea>
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
                                          maxlength="500">{{ old('short_description_en', $event->short_description_en) }}</textarea>
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
                                          required>{{ old('description', $event->description) }}</textarea>
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
                                          rows="6">{{ old('description_en', $event->description_en) }}</textarea>
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
                                       value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" 
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
                                       value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}">
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
                                       value="{{ old('location', $event->location) }}">
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
                                       value="{{ old('location_en', $event->location_en) }}">
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
                            <!-- Current Images -->
                            @if($event->image || $event->video || $event->gallery)
                                <div class="mb-4">
                                    <h6>Media Saat Ini:</h6>
                                    <div class="row">
                                        @if($event->image)
                                            <div class="col-md-4 mb-3">
                                                <div class="position-relative">
                                                    <img src="{{ $event->image_url }}" 
                                                         alt="{{ $event->title }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 100%; height: 150px; object-fit: cover;">
                                                    <div class="position-absolute top-0 end-0 p-1">
                                                        <span class="badge bg-primary">Gambar Utama</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($event->video)
                                            <div class="col-md-4 mb-3">
                                                <div class="position-relative">
                                                    <video class="img-thumbnail" 
                                                           style="width: 100%; height: 150px; object-fit: cover;" 
                                                           controls>
                                                        <source src="{{ $event->video_url }}" type="video/mp4">
                                                    </video>
                                                    <div class="position-absolute top-0 end-0 p-1">
                                                        <span class="badge bg-info">Video</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($event->gallery)
                                            @foreach($event->gallery as $index => $media)
                                                <div class="col-md-4 mb-3">
                                                    <div class="position-relative">
                                                        @if(pathinfo($media, PATHINFO_EXTENSION) === 'mp4' || 
                                                            pathinfo($media, PATHINFO_EXTENSION) === 'avi' || 
                                                            pathinfo($media, PATHINFO_EXTENSION) === 'mov')
                                                            <video class="img-thumbnail" 
                                                                   style="width: 100%; height: 150px; object-fit: cover;" 
                                                                   controls>
                                                                <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                                            </video>
                                                        @else
                                                            <img src="{{ asset('storage/' . $media) }}" 
                                                                 alt="Gallery {{ $index + 1 }}" 
                                                                 class="img-thumbnail" 
                                                                 style="width: 100%; height: 150px; object-fit: cover;">
                                                        @endif
                                                        <div class="position-absolute top-0 end-0 p-1">
                                                            <span class="badge bg-secondary">Galeri</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Catatan:</strong> Upload file baru akan mengganti media yang ada.
                                    </div>
                                </div>
                            @endif

                            <!-- Main Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="bi bi-image me-1"></i>Gambar Utama Baru
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
                                    <i class="bi bi-play-circle me-1"></i>Video Baru
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
                                    <i class="bi bi-images me-1"></i>Galeri Baru
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
                                               value="{{ old('meta_title', $event->meta_title) }}" 
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
                                               value="{{ old('meta_title_en', $event->meta_title_en) }}" 
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
                                                  maxlength="500">{{ old('meta_description', $event->meta_description) }}</textarea>
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
                                                  maxlength="500">{{ old('meta_description_en', $event->meta_description_en) }}</textarea>
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
                            <i class="bi bi-check-circle me-2"></i>Update Event
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
                        <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                               @if(old('is_featured', $event->is_featured)) checked @endif>
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
                               @if(old('is_active', $event->is_active)) checked @endif>
                        <label class="form-check-label" for="is_active">
                            <i class="bi bi-toggle-on me-1"></i>Active
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Info Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Event
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Slug:</strong> 
                    <code>{{ $event->slug }}</code>
                </div>
                <div class="mb-2">
                    <strong>Dibuat:</strong> 
                    {{ $event->created_at->format('d M Y, H:i') }}
                </div>
                <div class="mb-2">
                    <strong>Diperbarui:</strong> 
                    {{ $event->updated_at->format('d M Y, H:i') }}
                </div>
                <div class="mb-2">
                    <strong>Status Event:</strong> 
                    @if($event->is_upcoming)
                        <span class="badge bg-primary">Upcoming</span>
                    @elseif($event->is_ongoing)
                        <span class="badge bg-success">Ongoing</span>
                    @else
                        <span class="badge bg-secondary">Past</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panduan Edit Event -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan Edit Event
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Perhatian Saat Edit:</h6>
                    <ul class="mb-0">
                        <li><strong>Media:</strong> Upload file baru akan mengganti media yang ada</li>
                        <li><strong>Slug:</strong> Akan berubah otomatis jika judul diubah</li>
                        <li><strong>Status:</strong> Pastikan "Published" agar tampil di website</li>
                        <li><strong>Tanggal:</strong> Perubahan tanggal akan mempengaruhi status event</li>
                    </ul>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Yang Bisa Diubah:</h6>
                        <ul class="small mb-0">
                            <li>Judul dan deskripsi event</li>
                            <li>Tanggal dan waktu event</li>
                            <li>Lokasi event</li>
                            <li>Gambar, video, dan galeri</li>
                            <li>Status dan pengaturan</li>
                            <li>SEO settings</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-info-circle text-info me-2"></i>Tips Edit:</h6>
                        <ul class="small mb-0">
                            <li>Preview perubahan sebelum menyimpan</li>
                            <li>Backup media lama jika diperlukan</li>
                            <li>Test tampilan di website setelah edit</li>
                            <li>Update SEO jika judul berubah</li>
                            <li>Periksa tanggal untuk event status</li>
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
    // Update end_date minimum when start_date changes
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const statusSelect = document.getElementById('status');
        const hiddenStatus = document.getElementById('hiddenStatus');
        const featuredCheckbox = document.getElementById('is_featured');
        const activeCheckbox = document.getElementById('is_active');
        const hiddenFeatured = document.getElementById('hiddenFeatured');
        const hiddenActive = document.getElementById('hiddenActive');
        
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
