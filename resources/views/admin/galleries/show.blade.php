@extends('admin.layout')

@section('title', 'Detail Galeri')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Galeri</h2>
    <div>
        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $gallery->title }}</h5>
            </div>
            <div class="card-body">
                @if($gallery->image)
                    <img src="{{ asset($gallery->image_url) }}" 
                         alt="{{ $gallery->title }}" 
                         class="img-fluid rounded mb-3">
                @endif
                
                @if($gallery->description)
                    <div class="mb-3">
                        <h6>Deskripsi:</h6>
                        <p class="text-justify">{{ $gallery->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Galeri</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tipe:</label>
                    <p>
                        <span class="badge bg-info fs-6">
                            @switch($gallery->type)
                                @case('hero')
                                    <i class="bi bi-image me-1"></i>Hero
                                    @break
                                @case('about')
                                    <i class="bi bi-info-circle me-1"></i>About
                                    @break
                                @case('product')
                                    <i class="bi bi-box me-1"></i>Product
                                    @break
                                @case('gallery')
                                    <i class="bi bi-images me-1"></i>Gallery
                                    @break
                                @default
                                    {{ ucfirst($gallery->type) }}
                            @endswitch
                        </span>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Status:</label>
                    <p>
                        @if($gallery->is_active)
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                            </span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Urutan:</label>
                    <p>{{ $gallery->sort_order }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Dibuat:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-plus me-1"></i>
                        {{ $gallery->created_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $gallery->created_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Diperbarui:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ $gallery->updated_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $gallery->updated_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">ID:</label>
                    <p class="mb-0"><code>{{ $gallery->id }}</code></p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Galeri
                    </a>
                    
                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus galeri ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Galeri
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
