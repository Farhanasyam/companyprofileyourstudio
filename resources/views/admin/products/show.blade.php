@extends('admin.layout')

@section('title', 'Detail Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Produk</h2>
    <div>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Produk</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Produk:</label>
                        <p>{{ $product->name }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Kategori:</label>
                        <p>
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                            </span>
                        </p>
                    </div>
                    
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <p>
                            @if($product->is_active)
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-x-circle me-1"></i>Tidak Aktif
                                </span>
                            @endif
                            
                            @if($product->is_featured)
                                <span class="badge bg-warning fs-6 ms-2">
                                    <i class="bi bi-star me-1"></i>Unggulan
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Singkat:</label>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $product->short_description ?: '-' }}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Lengkap:</label>
                    <div class="bg-light p-3 rounded">
                        <div class="text-justify">{!! nl2br(e($product->description)) !!}</div>
                    </div>
                </div>
                
                @if($product->specifications && count($product->specifications) > 0)
                <div class="mb-3">
                    <label class="form-label fw-bold">Spesifikasi:</label>
                    <div class="bg-light p-3 rounded">
                        <ul class="list-unstyled mb-0">
                            @foreach($product->specifications as $key => $value)
                                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Gambar Produk</h5>
            </div>
            <div class="card-body">
                @if($product->main_image)
                    <img src="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($product->main_image)) }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded mb-3">
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-image fs-1"></i>
                        <p class="mt-2">Tidak ada gambar</p>
                    </div>
                @endif
                
                @if($product->images && count($product->images) > 0)
                    <h6>Gambar Tambahan:</h6>
                    <div class="row">
                        @foreach($product->images as $image)
                            <div class="col-6 mb-2">
                                <img src="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($image)) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-thumbnail">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Dibuat:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-plus me-1"></i>
                        {{ $product->created_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $product->created_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Diperbarui:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ $product->updated_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $product->updated_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Slug:</label>
                    <p class="mb-0"><code>{{ $product->slug }}</code></p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Produk
                    </a>
                    
                    <form action="{{ route('admin.products.destroy', $product) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Produk
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
