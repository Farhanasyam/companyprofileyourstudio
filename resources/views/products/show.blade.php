@extends('layouts.app')


@section('content')
<!-- Product Header (ringkas) -->
<section class="product-breadcrumb-bar">
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
<section class="py-5 product-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                @php
                    $mediaItems = [];
                    
                    // Add main image
                    if($product->display_image_url) {
                        $mediaItems[] = [
                            'type' => 'image',
                            'url' => $product->display_image_url,
                            'alt' => $product->name
                        ];
                    }
                    
                    // Add additional images (URL di-encode seperti image_url)
                    if($product->images && is_array($product->images) && count($product->images) > 0) {
                        foreach($product->images as $image) {
                            $path = is_string($image) ? $image : ($image['path'] ?? $image['url'] ?? null);
                            if ($path) {
                                $encoded = \App\Helpers\ImageHelper::encodePathForUrl($path);
                                if ($encoded !== '') {
                                    $mediaItems[] = [
                                        'type' => 'image',
                                        'url' => '/storage/' . $encoded,
                                        'alt' => $product->localized_name
                                    ];
                                }
                            }
                        }
                    }
                    
                    // Gambar per varian: slide ini yang dituju saat pembeli memilih varian
                    $variantSlide = [];
                    foreach ($product->variants as $variant) {
                        if ($variant->image_url) {
                            $variantSlide[$variant->id] = count($mediaItems);
                            $mediaItems[] = [
                                'type' => 'image',
                                'url' => $variant->image_url,
                                'alt' => $product->localized_name . ' - ' . $variant->display_name,
                                'variant_id' => $variant->id,
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
                    $hasVariants = $product->variants->isNotEmpty();
                @endphp

                <!-- Media Carousel (tanpa autoplay bila ada varian: slide mengikuti varian yang dipilih) -->
                <div id="productMediaCarousel" class="carousel slide mb-4 product-media-panel" data-bs-ride="{{ $hasVariants ? 'false' : 'carousel' }}" data-bs-interval="{{ $hasVariants ? 'false' : '5000' }}">
                    <!-- Slide Counter -->
                    @if(count($mediaItems) > 1)
                        <div class="slide-counter">
                            <span id="currentSlide">1</span> / <span id="totalSlides">{{ count($mediaItems) }}</span>
                        </div>
                    @endif
                    <div class="carousel-inner">
                        
                        @if(count($mediaItems) > 0)
                            @foreach($mediaItems as $index => $item)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" @isset($item['variant_id']) data-variant-id="{{ $item['variant_id'] }}" @endisset>
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
                                    <div class="product-media-ph {{ $product->category?->placeholder_class ?? 'ph ph--default' }}">
                                        <i class="bi {{ $product->category?->placeholder_icon ?? 'bi-box-seam' }}"></i>
                                        <span>{{ $product->category?->localized_name }}</span>
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
            
            <div class="col-lg-6 product-detail-content">
                <h1 class="display-6 fw-bold mb-3 product-detail-title">{{ $product->localized_name }}</h1>
                
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-2 product-category-badge">{{ $product->category->localized_name }}</span>
                    @if($product->is_featured)
                        <span class="badge product-featured-badge">Featured</span>
                    @endif
                </div>
                
                
                <div class="mb-4 product-detail-block">
                    <h5 class="fw-bold mb-3 product-detail-heading">{{ __('common.product_description') }}</h5>
                    <div class="product-description-body">
                        {!! $product->localized_description_html !!}
                    </div>
                </div>
                
                @if($hasVariants)
                    <div class="mb-4 product-detail-block">
                        <h5 class="product-detail-heading">
                            {{ __('common.choose_variant') }}
                            <small class="text-muted fw-normal">({{ __('common.variants_count', ['count' => $product->variants->count()]) }})</small>
                        </h5>
                        <div class="variant-chips" role="radiogroup" aria-label="{{ __('common.choose_variant') }}">
                            @foreach($product->variants as $variant)
                                <button type="button" class="variant-chip {{ $variant->image_url ? 'has-thumb' : '' }}" role="radio" aria-checked="false"
                                        data-variant-id="{{ $variant->id }}" data-variant-name="{{ $variant->display_name }}"
                                        @isset($variantSlide[$variant->id]) data-slide="{{ $variantSlide[$variant->id] }}" @endisset>
                                    @if($variant->image_url)
                                        <img src="{{ $variant->image_url }}" alt="" loading="lazy" decoding="async">
                                    @endif
                                    <span>{{ $variant->display_name }}</span>
                                </button>
                            @endforeach
                        </div>
                        <small class="variant-hint text-muted d-block mt-2">
                            {{ __('common.variant_selected') }} <strong id="selectedVariantLabel">—</strong>
                        </small>
                    </div>
                @endif

                @if(!empty($product->specifications))
                    <div class="mb-4 product-detail-block">
                        <h5 class="product-detail-heading">{{ __('common.product_specifications') }}</h5>
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
                    <!-- Marketplace: Shopee & TikTok (warna brand resmi) -->
                    @if($product->shopee_url || $product->tiktok_url || \App\Models\Setting::get('shopee_url') || \App\Models\Setting::get('tiktok_url'))
                        <div class="product-marketplace">
                            <span class="product-marketplace__label">{{ __('common.buy_on_marketplace') }}</span>
                            <div class="product-marketplace__btns">
                                @if($product->shopee_url || \App\Models\Setting::get('shopee_url'))
                                    <a href="{{ $product->shopee_url ?: \App\Models\Setting::get('shopee_url') }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="btn-marketplace btn-marketplace--shopee">
                                        <span class="btn-marketplace__icon"><i class="bi bi-shop"></i></span>
                                        <span class="btn-marketplace__text">{{ __('common.shopee') }}</span>
                                    </a>
                                @endif
                                @if($product->tiktok_url || \App\Models\Setting::get('tiktok_url'))
                                    <a href="{{ $product->tiktok_url ?: \App\Models\Setting::get('tiktok_url') }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="btn-marketplace btn-marketplace--tiktok">
                                        <span class="btn-marketplace__icon"><i class="bi bi-tiktok"></i></span>
                                        <span class="btn-marketplace__text">{{ __('common.tiktok') }}</span>
                                    </a>
                                @endif
                            </div>
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
<section class="py-5 product-related-section">
    <div class="container">
        <h3 class="fw-bold mb-4">{{ __('common.related_products') }}</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-6 col-lg-3">
                    @include('partials.product-card', ['product' => $relatedProduct])
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
    
    // Pilihan varian: klik chip -> slider pindah ke gambar varian; geser slider ke gambar varian -> chip ikut terpilih
    var selectedVariantId = null;
    var variantChips = document.querySelectorAll('.variant-chip');
    var selectedLabel = document.getElementById('selectedVariantLabel');

    function markVariant(chip) {
        variantChips.forEach(function (c) { c.classList.remove('is-active'); c.setAttribute('aria-checked', 'false'); });
        chip.classList.add('is-active');
        chip.setAttribute('aria-checked', 'true');
        selectedVariantId = chip.getAttribute('data-variant-id');
        if (selectedLabel) selectedLabel.textContent = chip.getAttribute('data-variant-name');
        var chipsWrap = chip.closest('.variant-chips');
        if (chipsWrap) chipsWrap.classList.remove('needs-choice');
    }

    variantChips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            markVariant(chip);
            if (!carousel || typeof bootstrap === 'undefined') return;
            var slide = chip.getAttribute('data-slide');
            if (slide !== null) {
                bootstrap.Carousel.getOrCreateInstance(carousel).to(parseInt(slide, 10));
            } else {
                // Varian tanpa gambar: kembali ke gambar utama (bukan gambar varian lain)
                var first = carousel.querySelector('.carousel-item');
                if (first && !first.hasAttribute('data-variant-id')) {
                    bootstrap.Carousel.getOrCreateInstance(carousel).to(0);
                }
            }
        });
    });

    if (carousel && variantChips.length) {
        carousel.addEventListener('slid.bs.carousel', function (event) {
            var vid = event.relatedTarget && event.relatedTarget.getAttribute('data-variant-id');
            if (!vid || vid === selectedVariantId) return;
            var chip = document.querySelector('.variant-chip[data-variant-id="' + vid + '"]');
            if (chip) markVariant(chip);
        });
    }

    // Tombol Tambah ke Keranjang: buka modal pesan dan tambahkan produk ini
    var btnAddToCart = document.getElementById('btnAddProductToOrderCart');
    if (btnAddToCart) {
        btnAddToCart.addEventListener('click', function() {
            var productId = this.getAttribute('data-product-id');
            if (!productId) return;
            if (variantChips.length > 0 && !selectedVariantId) {
                var chipsWrap = document.querySelector('.variant-chips');
                if (chipsWrap) {
                    chipsWrap.classList.add('needs-choice');
                    chipsWrap.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'info', text: @json(__('common.variant_required')), confirmButtonColor: '#8e6337' });
                }
                return;
            }
            // Key keranjang: "produk:varian" agar varian tampil sebagai barang tersendiri di keranjang
            window.pendingAddToOrderProductId = selectedVariantId ? productId + ':' + selectedVariantId : productId;
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
