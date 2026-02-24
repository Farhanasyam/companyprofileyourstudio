@extends('admin.layout')

@section('title', 'Detail Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Kategori</h2>
    <div>
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $category->name }}</h5>
            </div>
            <div class="card-body">
                @if($category->image)
                    <img src="{{ asset($category->image_url) }}" 
                         alt="{{ $category->name }}" 
                         class="img-fluid rounded mb-3" 
                         style="max-width: 300px;">
                @endif
                
                @if($category->description)
                    <div class="mb-3">
                        <h6>Deskripsi:</h6>
                        <p class="text-justify">{{ $category->description }}</p>
                    </div>
                @endif
                
                <div class="mb-3">
                    <h6>Produk dalam kategori ini:</h6>
                    @if($category->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->products as $product)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="text-decoration-none">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($product->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Belum ada produk dalam kategori ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Kategori</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Status:</label>
                    <p>
                        @if($category->is_active)
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
                    <label class="form-label fw-bold">Jumlah Produk:</label>
                    <p class="h5 text-primary">{{ $category->products->count() }} produk</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Urutan:</label>
                    <p>{{ $category->sort_order }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Dibuat:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-plus me-1"></i>
                        {{ $category->created_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $category->created_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Diperbarui:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ $category->updated_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $category->updated_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Slug:</label>
                    <p class="mb-0"><code>{{ $category->slug }}</code></p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Kategori
                    </a>
                    
                    @if($category->products->count() == 0)
                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Hapus Kategori
                            </button>
                        </form>
                    @else
                        <button class="btn btn-danger w-100" disabled title="Tidak dapat menghapus kategori yang memiliki produk">
                            <i class="bi bi-trash me-2"></i>Hapus Kategori
                        </button>
                    @endif
                    
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
