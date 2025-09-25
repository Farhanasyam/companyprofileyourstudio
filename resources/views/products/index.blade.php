@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="text-center">
            <h1 class="display-5 fw-bold mb-3" style="color: var(--dark-brown) !important;">Katalog Produk</h1>
            <p class="lead" style="color: var(--medium-brown) !important;">Temukan berbagai produk alat lukis dan clay berkualitas tinggi</p>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="py-4 bg-gradient-primary border-bottom">
    <div class="container">
        <form method="GET" action="{{ route('products.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Produk</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nama produk, merek...">
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label">Kategori</label>
                <select class="form-select" id="category" name="category">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label">Urutkan</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama A-Z</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Products Grid -->
<section class="py-5">
    <div class="container">
        @if($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="product-image-container">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" 
                                         class="product-image" 
                                         alt="{{ $product->name }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    </div>
                                @endif
                                <div class="product-overlay">
                                    <div class="product-badge">
                                        <i class="bi bi-box me-1"></i>Produk
                                    </div>
                                    @if($product->is_featured)
                                        <div class="featured-badge">
                                            <i class="bi bi-star-fill me-1"></i>Featured
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="product-meta mb-2">
                                    <span class="badge product-category" style="background: var(--light-pink); color: var(--dark-brown); border: 1px solid var(--light-brown);">{{ $product->category->name }}</span>
                                </div>
                                <h5 class="card-title product-title fw-bold" style="color: var(--dark-brown) !important;">{{ $product->name }}</h5>
                                <p class="card-text product-description" style="color: var(--dark-grey) !important;">
                                    {{ Str::limit($product->short_description ?: $product->description, 80) }}
                                </p>
                                
                                <div class="product-stats d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $product->created_at->format('d M Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-eye me-1"></i>{{ $product->views ?? 0 }}
                                    </small>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary product-btn w-100">
                                    <i class="bi bi-eye me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-box fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">Produk tidak ditemukan</h4>
                <p class="text-muted">Coba ubah filter pencarian Anda</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-gradient-secondary">
    <div class="container">
        <h3 class="fw-bold text-center mb-4" style="color: var(--dark-brown) !important;">Kategori Produk</h3>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-3 col-sm-6 mb-3">
                    <a href="{{ route('products.category', $category) }}" class="text-decoration-none">
                        <div class="card category-card h-100 text-center border-0 shadow-sm">
                            <div class="card-body">
                                <div class="category-image-container">
                                    @if($category->image)
                                        <img src="{{ $category->image_url }}" 
                                             alt="{{ $category->name }}" 
                                             class="category-image">
                                    @else
                                        <div class="category-placeholder">
                                            <i class="bi bi-tag fs-4"></i>
                                        </div>
                                    @endif
                                    <div class="category-overlay">
                                        <div class="category-badge">
                                            <i class="bi bi-tag me-1"></i>Kategori
                                        </div>
                                    </div>
                                </div>
                                <h6 class="card-title category-title fw-bold" style="color: var(--dark-brown) !important;">{{ $category->name }}</h6>
                                <small class="category-count" style="color: var(--dark-grey) !important; background: var(--light-pink); padding: 4px 12px; border-radius: 15px; border: 1px solid var(--light-brown); font-weight: 500;">{{ $category->products_count ?? 0 }} produk</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

