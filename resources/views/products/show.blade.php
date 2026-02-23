@extends('layouts.app')

@section('favicon')
{!! \App\Helpers\FaviconHelper::renderFaviconTags($product) !!}
@endsection

@section('content')
<!-- Product Header -->
<section class="py-5">
    <div class="container">
        <div class="modern-breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i>{{ __('common.home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index') }}">
                            <i class="bi bi-box"></i>{{ __('common.products') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.category', $product->category) }}">
                            <i class="bi bi-tag"></i>{{ $product->category->localized_name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-box-seam"></i>{{ $product->localized_name }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- Product 
 Details -->
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                @php
                    $mediaItems = [];
                    
                    // Add main image
                    if($product->image) {
                        $mediaItems[] = [
                            'type' => 'image',
                            'url' => $product->image_url,
                            'alt' => $product->name
                        ];
                    }
                    
                    // Add additional images
                    if($product->images && is_array($product->images) && count($product->images) > 0) {
                        foreach($product->images as $image) {
                            $mediaItems[] = [
                                'type' => 'image',
                                'url' => asset('storage/' . $image),
                                'alt' => $product->localized_name
                            ];
                        }
                    }
                    
                    // Add video
                    if($product->video) {
                        $mediaItems[] = [
                            'type' => 'video',
                            'url' => $product->video_url,
                            'alt' => $product->localized_name . ' Video'
                        ];
                    }
                @endphp
                
                <!-- Media Carousel -->
                <div id="productMediaCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <!-- Slide Counter -->
                    @if(count($mediaItems) > 1)
                        <div class="slide-counter">
                            <span id="currentSlide">1</span> / <span id="totalSlides">{{ count($mediaItems) }}</span>
                        </div>
                    @endif
                    <div class="carousel-inner">
                        
                        @if(count($mediaItems) > 0)
                            @foreach($mediaItems as $index => $item)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <!-- Container dengan ukuran tetap untuk semua media -->
                                    <div class="media-container">
                                        @if($item['type'] === 'image')
                                            <img src="{{ $item['url'] }}" 
                                                 class="media-content" 
                                                 alt="{{ $item['alt'] }}">
                                        @elseif($item['type'] === 'video')
                                            <video controls class="media-content">
                                                <source src="{{ $item['url'] }}" type="video/mp4">
                                                Browser Anda tidak mendukung video.
                                            </video>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="media-container">
                                    <div class="bg-light d-flex align-items-center justify-content-center">
                                        <div class="text-center text-muted">
                                            <i class="bi bi-image fs-1"></i>
                                            <p class="mt-2">Tidak ada media</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @if(count($mediaItems) > 1)
                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#productMediaCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productMediaCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        
                    @endif
                </div>
                
            </div>
            
            <div class="col-lg-6">
                <h1 class="display-6 fw-bold mb-3" style="color: var(--dark-brown) !important;">{{ $product->localized_name }}</h1>
                
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2" style="background: var(--light-pink); color: var(--dark-brown); border: 1px solid var(--light-brown);">{{ $product->category->localized_name }}</span>
                    @if($product->is_featured)
                        <span class="badge bg-warning">Featured</span>
                    @endif
                </div>
                
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-3" style="color: var(--dark-brown);">{{ __('common.product_description') }}</h5>
                    <div class="product-description-body" style="color: var(--dark-grey); line-height: 1.7;">
                        {!! $product->localized_description !!}
                    </div>
                </div>
                
                @if($product->specifications)
                    <div class="mb-4">
                        <h5>{{ __('common.product_specifications') }}</h5>
                        <ul class="list-unstyled">
                            @foreach($product->specifications as $key => $value)
                                <li class="mb-2">
                                    <strong>{{ $key }}:</strong> {{ $value }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                
                <div class="d-grid gap-3">
                    <button type="button" class="btn btn-lg btn-success btn-add-to-order-cart" id="btnAddProductToOrderCart" data-product-id="{{ $product->id }}">
                        <i class="bi bi-cart-plus me-2"></i>{{ __('common.add_to_cart') }}
                    </button>
                    <!-- Shopping Platform Buttons -->
                    @if($product->shopee_url || $product->tiktok_url || \App\Models\Setting::get('shopee_url') || \App\Models\Setting::get('tiktok_url'))
                        <div class="row g-3">
                            @if($product->shopee_url || \App\Models\Setting::get('shopee_url'))
                            <div class="col-6">
                                <a href="{{ $product->shopee_url ?: \App\Models\Setting::get('shopee_url') }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="btn btn-lg w-100 btn-shopee">
                                    <i class="bi bi-shop me-2"></i>{{ __('common.shopee') }}
                                </a>
                            </div>
                            @endif
                            @if($product->tiktok_url || \App\Models\Setting::get('tiktok_url'))
                            <div class="col-6">
                                <a href="{{ $product->tiktok_url ?: \App\Models\Setting::get('tiktok_url') }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="btn btn-lg w-100 btn-tiktok">
                                    <i class="bi bi-tiktok me-2"></i>{{ __('common.tiktok') }}
                                </a>
                            </div>
                            @endif
                        </div>
                    @endif
                    <a href="{{ route('products.index') }}" class="btn btn-lg btn-back-catalog">
                        <i class="bi bi-arrow-left me-2"></i>{{ __('common.back_to_catalog') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="py-5 bg-gradient-secondary">
    <div class="container">
        <h3 class="fw-bold mb-4">{{ __('common.related_products') }}</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($relatedProduct->image)
                            <img src="{{ $relatedProduct->image_url }}" 
                                 class="card-img-top" 
                                 alt="{{ $relatedProduct->name }}"
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 220px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h6 class="card-title fw-bold" style="color: #000 !important;">{{ $relatedProduct->name }}</h6>
                            <p class="card-text small" style="color: #333 !important;">
                                {{ Str::limit($relatedProduct->short_description ?: $relatedProduct->description, 60) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background: var(--light-pink); color: var(--dark-brown); border: 1px solid var(--light-brown);">{{ $relatedProduct->category->localized_name }}</span>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary w-100">
                                {{ __('common.view_detail') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('productMediaCarousel');
    
    if (carousel) {
        // Update slide counter when carousel changes
        carousel.addEventListener('slide.bs.carousel', function (event) {
            const activeIndex = event.to;
            
            // Update slide counter
            const currentSlideElement = document.getElementById('currentSlide');
            if (currentSlideElement) {
                currentSlideElement.textContent = activeIndex + 1;
            }
        });
        
        // Auto-pause video when carousel changes
        carousel.addEventListener('slide.bs.carousel', function (event) {
            const currentSlide = event.target.querySelector('.carousel-item.active');
            const videos = currentSlide.querySelectorAll('video');
            
            videos.forEach(video => {
                video.pause();
            });
        });
        
        // Pause all videos when carousel is not in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) {
                    const videos = entry.target.querySelectorAll('video');
                    videos.forEach(video => {
                        video.pause();
                    });
                }
            });
        });
        
        observer.observe(carousel);
    }
    
    // Tombol Tambah ke Keranjang: buka modal pesan dan tambahkan produk ini
    var btnAddToCart = document.getElementById('btnAddProductToOrderCart');
    if (btnAddToCart) {
        btnAddToCart.addEventListener('click', function() {
            var productId = this.getAttribute('data-product-id');
            if (!productId) return;
            window.pendingAddToOrderProductId = productId;
            var orderModalEl = document.getElementById('orderManualModal');
            if (orderModalEl && typeof bootstrap !== 'undefined') {
                var orderModal = new bootstrap.Modal(orderModalEl);
                orderModal.show();
            }
        });
    }
});
</script>
@endpush
