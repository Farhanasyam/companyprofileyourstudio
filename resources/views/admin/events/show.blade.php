@extends('admin.layout')

@section('title', 'Detail Event')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-eye me-2"></i>Detail Event: {{ $event->title }}
    </h2>
    <div>
        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Event Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Event
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Judul (Indonesia)</h6>
                        <p>{{ $event->title }}</p>
                        
                        <h6 class="fw-bold">Deskripsi Singkat (Indonesia)</h6>
                        <p>{{ $event->short_description ?: '-' }}</p>
                        
                        <h6 class="fw-bold">Deskripsi Lengkap (Indonesia)</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Title (English)</h6>
                        <p>{{ $event->title_en ?: '-' }}</p>
                        
                        <h6 class="fw-bold">Short Description (English)</h6>
                        <p>{{ $event->short_description_en ?: '-' }}</p>
                        
                        <h6 class="fw-bold">Full Description (English)</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($event->description_en ?: '-')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date and Location -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>Tanggal & Lokasi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Tanggal Mulai</h6>
                        <p>
                            <i class="bi bi-calendar-event me-2"></i>
                            {{ $event->start_date->format('d F Y, H:i') }}
                        </p>
                        
                        <h6 class="fw-bold">Tanggal Selesai</h6>
                        <p>
                            <i class="bi bi-calendar-check me-2"></i>
                            {{ $event->end_date ? $event->end_date->format('d F Y, H:i') : '-' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Lokasi (Indonesia)</h6>
                        <p>
                            <i class="bi bi-geo-alt me-2"></i>
                            {{ $event->location ?: '-' }}
                        </p>
                        
                        <h6 class="fw-bold">Location (English)</h6>
                        <p>
                            <i class="bi bi-geo-alt me-2"></i>
                            {{ $event->location_en ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Media -->
        @if($event->image || $event->video || $event->gallery)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-image me-2"></i>Media
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($event->image)
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Gambar Utama</h6>
                            <img src="{{ $event->image_url }}" 
                                 alt="{{ $event->title }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 300px; object-fit: cover;">
                        </div>
                    @endif
                    
                    @if($event->video)
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Video</h6>
                            <video controls class="img-fluid rounded" style="max-height: 300px;">
                                <source src="{{ $event->video_url }}" type="video/mp4">
                                Browser Anda tidak mendukung video.
                            </video>
                        </div>
                    @endif
                </div>
                
                @if($event->gallery && count($event->gallery) > 0)
                    <div class="mt-4">
                        <h6 class="fw-bold">Galeri</h6>
                        <div class="row">
                            @foreach($event->gallery as $index => $media)
                                <div class="col-md-4 mb-3">
                                    @if(pathinfo($media, PATHINFO_EXTENSION) === 'mp4' || 
                                        pathinfo($media, PATHINFO_EXTENSION) === 'avi' || 
                                        pathinfo($media, PATHINFO_EXTENSION) === 'mov')
                                        <video controls class="img-fluid rounded" style="height: 200px; object-fit: cover;">
                                            <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ asset('storage/' . $media) }}" 
                                             alt="Gallery {{ $index + 1 }}" 
                                             class="img-fluid rounded" 
                                             style="height: 200px; object-fit: cover;">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- SEO Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-search me-2"></i>SEO Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Meta Title (Indonesia)</h6>
                        <p>{{ $event->meta_title ?: '-' }}</p>
                        
                        <h6 class="fw-bold">Meta Description (Indonesia)</h6>
                        <p>{{ $event->meta_description ?: '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Meta Title (English)</h6>
                        <p>{{ $event->meta_title_en ?: '-' }}</p>
                        
                        <h6 class="fw-bold">Meta Description (English)</h6>
                        <p>{{ $event->meta_description_en ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Event Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-flag me-2"></i>Status Event
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Status:</strong>
                    @if($event->status === 'published')
                        <span class="badge bg-success ms-2">Published</span>
                    @elseif($event->status === 'draft')
                        <span class="badge bg-warning ms-2">Draft</span>
                    @else
                        <span class="badge bg-danger ms-2">Cancelled</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <strong>Active:</strong>
                    @if($event->is_active)
                        <span class="badge bg-success ms-2">Active</span>
                    @else
                        <span class="badge bg-secondary ms-2">Inactive</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <strong>Featured:</strong>
                    @if($event->is_featured)
                        <span class="badge bg-primary ms-2">
                            <i class="bi bi-star-fill"></i> Featured
                        </span>
                    @else
                        <span class="badge bg-secondary ms-2">Not Featured</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <strong>Event Status:</strong>
                    @if($event->is_upcoming)
                        <span class="badge bg-primary ms-2">Upcoming</span>
                    @elseif($event->is_ongoing)
                        <span class="badge bg-success ms-2">Ongoing</span>
                    @else
                        <span class="badge bg-secondary ms-2">Past</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Event Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Slug:</strong> 
                    <code>{{ $event->slug }}</code>
                </div>
                <div class="mb-2">
                    <strong>ID:</strong> 
                    <code>{{ $event->id }}</code>
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
                    <strong>Dibuat oleh:</strong> 
                    {{ $event->created_at->diffForHumans() }}
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Aksi
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Event
                    </a>
                    
                    @if($event->status === 'published')
                        <a href="{{ route('events.show', $event) }}" 
                           class="btn btn-info" 
                           target="_blank">
                            <i class="bi bi-eye me-2"></i>Lihat di Website
                        </a>
                    @endif
                    
                    <button type="button" 
                            class="btn btn-danger" 
                            onclick="confirmDelete('{{ route('admin.events.destroy', $event) }}', 'Event', 'Apakah Anda yakin ingin menghapus event ini?')">
                        <i class="bi bi-trash me-2"></i>Hapus Event
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Durasi Event:</strong>
                    @if($event->end_date)
                        @php
                            $duration = $event->start_date->diffInDays($event->end_date);
                        @endphp
                        {{ $duration }} hari
                    @else
                        Single day event
                    @endif
                </div>
                
                <div class="mb-2">
                    <strong>Hari ke Event:</strong>
                    @if($event->is_upcoming)
                        @php
                            $daysToEvent = now()->diffInDays($event->start_date, false);
                        @endphp
                        {{ $daysToEvent }} hari lagi
                    @elseif($event->is_ongoing)
                        <span class="text-success">Sedang berlangsung</span>
                    @else
                        <span class="text-muted">Event selesai</span>
                    @endif
                </div>
                
                <div class="mb-2">
                    <strong>Media Count:</strong>
                    @php
                        $mediaCount = 0;
                        if($event->image) $mediaCount++;
                        if($event->video) $mediaCount++;
                        if($event->gallery) $mediaCount += count($event->gallery);
                    @endphp
                    {{ $mediaCount }} file
                </div>
            </div>
        </div>

        <!-- Panduan Detail Event -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan Detail Event
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>Informasi Event:</h6>
                    <p class="mb-0">Halaman ini menampilkan detail lengkap event. Anda dapat melihat semua informasi, media, dan statistik event.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-gear me-2"></i>Aksi yang Tersedia:</h6>
                        <ul class="small mb-0">
                            <li><strong>Edit:</strong> Ubah informasi event</li>
                            <li><strong>Lihat Website:</strong> Preview di halaman public</li>
                            <li><strong>Hapus:</strong> Hapus event permanen</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-eye me-2"></i>Status Event:</h6>
                        <ul class="small mb-0">
                            <li><strong>Upcoming:</strong> Event akan datang</li>
                            <li><strong>Ongoing:</strong> Event sedang berlangsung</li>
                            <li><strong>Past:</strong> Event sudah selesai</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
