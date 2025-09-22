@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary py-5" style="color: var(--dark-brown);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    {{ \App\Helpers\SettingHelper::getCompanyTagline() }}
                </h1>
                <p class="lead mb-4">
                    {{ \App\Helpers\SettingHelper::getCompanyDescription() }}
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg">
                        <i class="bi bi-box me-2"></i>Lihat Produk
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-telephone me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                @if($heroImages->count() > 0)
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($heroImages as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->image_url }}" 
                                         class="d-block w-100 rounded shadow" 
                                         alt="{{ $image->title }}"
                                         style="height: 450px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if($heroImages->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-image fs-1"></i>
                            <p class="mt-2">Hero Image</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--dark-brown);">Kategori Produk</h2>
            <p style="color: var(--medium-brown);">Temukan produk sesuai kebutuhan kreativitas Anda</p>
        </div>
        
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4 col-lg-2 mb-4">
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
                                <h6 class="card-title category-title">{{ $category->localized_name }}</h6>
                                <small class="text-muted category-count">{{ $category->products_count ?? 0 }} produk</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                Lihat Semua Kategori
            </a>
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="py-5 bg-gradient-secondary">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--dark-brown);">Produk Unggulan</h2>
            <p style="color: var(--medium-brown);">Produk terbaik pilihan kami untuk Anda</p>
        </div>
        
        <div class="row">
            @foreach($featuredProducts as $product)
                <div class="col-md-6 col-lg-4 mb-4">
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
                                <span class="badge product-category">{{ $product->category->localized_name }}</span>
                            </div>
                            <h5 class="card-title product-title">{{ $product->localized_name }}</h5>
                            <p class="card-text product-description">
                                {{ Str::limit($product->localized_short_description ?: $product->localized_description, 100) }}
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
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                {{ __('common.view_all') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- Featured Articles Section -->
@if($featuredArticles->count() > 0)
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--dark-brown);">Artikel Terbaru</h2>
            <p style="color: var(--medium-brown);">Tips dan inspirasi untuk kreativitas Anda</p>
        </div>
        
        <div class="row">
            @foreach($featuredArticles as $article)
                <div class="col-md-4 mb-4">
                    <div class="card article-card h-100 border-0 shadow-sm">
                        <div class="article-image-container">
                            @if($article->featured_image)
                                <img src="{{ $article->featured_image_url }}" 
                                     class="article-image" 
                                     alt="{{ $article->title }}"
                                     style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 220px;">
                                    <i class="bi bi-newspaper text-muted fs-1"></i>
                                </div>
                            @endif
                            <div class="article-overlay">
                                <div class="article-badge">
                                    <i class="bi bi-newspaper me-1"></i>Artikel
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title article-title">{{ $article->localized_title }}</h5>
                            <p class="card-text text-muted article-excerpt">
                                {!! Str::limit(trim(strip_tags($article->localized_excerpt ?: $article->localized_content, '<strong><b><em><i><u><span>')), 120) !!}
                            </p>
                            
                            <div class="article-stats d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $article->created_at->format('d M Y') }}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-eye me-1"></i>{{ $article->views }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary w-100">
                                Baca Artikel
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">
                Lihat Semua Artikel
            </a>
        </div>
    </div>
</section>
@endif

<!-- Upcoming Events Section -->
@if($upcomingEvents->count() > 0)
<section class="py-5 bg-gradient-success">
    <div class="container">
        <div class="text-center mb-5">
                    <h2 class="fw-bold" style="color: var(--dark-brown);">{{ __('common.upcoming_events') }}</h2>
                    <p style="color: var(--medium-brown);">{{ __('common.upcoming_events') }}</p>
        </div>
        
        <div class="row">
            @foreach($upcomingEvents as $event)
                <div class="col-md-4 mb-4">
                    <div class="card event-card h-100 border-0 shadow-sm">
                        <div class="event-image-container">
                            @if($event->image)
                                <img src="{{ $event->image_url }}" 
                                     class="event-image" 
                                     alt="{{ $event->title }}"
                                     style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 220px;">
                                    <i class="bi bi-calendar-event text-muted fs-1"></i>
                                </div>
                            @endif
                            <div class="event-overlay">
                                <div class="event-badge">
                                    <i class="bi bi-calendar-event me-1"></i>Event
                                </div>
                                <div class="event-status-badge">
                                    <i class="bi bi-clock me-1"></i>Mendatang
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="event-meta mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $event->start_date->format('d M Y') }}
                                </small>
                            </div>
                            <h5 class="card-title event-title">{{ $event->localized_title }}</h5>
                            <p class="card-text text-muted event-description">
                                {{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}
                            </p>
                            
                            <div class="event-info mb-3">
                                <div class="event-location">
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $event->localized_location ?: 'Lokasi TBA' }}
                                    </small>
                                </div>
                            </div>

                            <!-- Ultra-Simplified Countdown Timer (Days Only) -->
                            <div class="countdown-timer mb-3" data-event-id="{{ $event->id }}" data-date="{{ $event->start_date->format('Y-m-d H:i:s') }}">
                                <div class="text-center">
                                    <div class="countdown-item-single">
                                        <span class="countdown-number days">00</span>
                                                    <small class="countdown-label">{{ __('common.days') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="card-footer bg-transparent">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-primary w-100">
                                        {{ __('common.view_event') }}
                                    </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
                    <a href="{{ route('events.index') }}" class="btn btn-primary">
                        {{ __('common.all_events') }}
                    </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 bg-gradient-warning" style="color: var(--dark-brown);">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Memulai Kreativitas Anda?</h2>
        <p class="lead mb-4">Temukan produk terbaik dan dapatkan inspirasi dari artikel kami</p>
        <div class="homepage-cta-buttons">
            <a href="{{ route('products.index') }}" class="homepage-btn homepage-btn-primary">
                <i class="bi bi-box me-2"></i>Belanja Sekarang
            </a>
        </div>
    </div>
</section>
@endsection

{{-- Countdown styles and scripts are now loaded from external files --}}

