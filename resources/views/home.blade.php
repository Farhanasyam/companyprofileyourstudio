@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary py-5" style="color: var(--dark-brown);">
    <div class="container">
        <div class="row align-items-center hero-row">
            <div class="col-lg-6 hero-col-text">
                <h1 class="display-4 fw-bold mb-3 hero-title">
                    {{ \App\Helpers\SettingHelper::getCompanyTagline() }}
                </h1>
                <p class="lead mb-4 hero-desc">
                    {{ \App\Helpers\SettingHelper::getCompanyDescription() }}
                </p>
                <div class="hero-btns d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg">
                        <i class="bi bi-box me-2"></i>{{ __('common.view_products') }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-telephone me-2"></i>{{ __('common.contact_us') }}
                    </a>
                </div>
            </div>
            <div class="col-lg-6 hero-col-image">
                @if($heroImages->count() > 0)
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($heroImages as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->image_url }}" 
                                         class="d-block w-100 rounded shadow" 
                                         alt="{{ $image->title }}"
                                         width="800" height="450"
                                         fetchpriority="{{ $index === 0 ? 'high' : 'low' }}"
                                         loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
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
                            <p class="mt-2">{{ __('common.hero_image') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Categories Section (3 item) -->
@if($categories->count() > 0)
<section class="py-5 bg-gradient-primary homepage-categories-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: var(--dark-brown);">{{ __('common.product_categories') }}</h2>
            <p class="lead mb-0" style="color: var(--dark-brown); opacity: 0.85;">{{ __('common.category_tagline') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($categories->take(3) as $category)
                <div class="col-6 col-md-4">
                    <a href="{{ route('products.category', $category) }}" class="text-decoration-none">
                        <div class="card category-card category-card-home h-100 text-center border-0 shadow rounded-4 overflow-hidden">
                            <div class="card-body p-3">
                                <div class="home-img-box home-img-box--category">
                                    @if($category->image)
                                        <img src="{{ $category->image_url }}" alt="{{ $category->localized_name }}" class="home-img-box__img" loading="lazy" decoding="async">
                                    @else
                                        <div class="home-img-box__placeholder"><i class="bi bi-tag fs-1"></i></div>
                                    @endif
                                    <div class="category-overlay"><span class="category-badge">{{ __('common.view') }}</span></div>
                                </div>
                                <h6 class="card-title category-title mt-2 mb-0">{{ $category->localized_name }}</h6>
                                <small class="text-muted">{{ $category->products_count ?? 0 }} {{ __('common.products_count') }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-dark rounded-pill px-4 py-2 btn-home-section">
                <i class="bi bi-grid-3x3-gap me-2"></i>{{ __('common.view_all_categories') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- Produk Section (3 item) - Kartu produk dari awal -->
@if($featuredProducts->count() > 0)
<section class="py-5 bg-gradient-secondary homepage-products-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: var(--dark-brown);">{{ __('common.featured_products') }}</h2>
            <p class="lead mb-0" style="color: rgba(255,255,255,0.9);">{{ __('common.featured_products_sub') }}</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts->take(3) as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="prod-card prod-card--home h-100">
                        <a href="{{ route('products.show', $product) }}" class="prod-card__img-wrap">
                            @if($product->display_image_url)
                                <img src="{{ $product->display_image_url }}" alt="{{ $product->name }}" class="prod-card__img" loading="lazy">
                            @else
                                <span class="prod-card__noimg"><i class="bi bi-box-seam"></i></span>
                            @endif
                            @if($product->is_featured)
                                <span class="prod-card__badge"><i class="bi bi-star-fill"></i> {{ __('common.featured') }}</span>
                            @endif
                        </a>
                        <div class="prod-card__body">
                            <span class="prod-card__cat">{{ $product->category->localized_name }}</span>
                            <h3 class="prod-card__title"><a href="{{ route('products.show', $product) }}">{{ $product->localized_name }}</a></h3>
                            <p class="prod-card__desc">{{ Str::limit($product->localized_short_description ?: $product->localized_description, 80) }}</p>
                            <div class="prod-card__actions">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-light rounded-pill">{{ __('common.view_detail') }}</a>
                                <button type="button" class="btn btn-sm btn-success rounded-pill btn-add-to-order-cart" data-product-id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#orderManualModal" title="{{ __('common.order_via_wa') }}"><i class="bi bi-cart-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill px-4 py-2">
                <i class="bi bi-box-seam me-2"></i>{{ __('common.view_all') }} {{ __('common.products') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- Event Section (3 item) -->
@if($upcomingEvents->count() > 0)
<section class="py-5 bg-gradient-success">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: var(--dark-brown);">{{ __('common.upcoming_events') }}</h2>
            <p class="lead mb-0" style="color: var(--medium-brown);">{{ __('common.upcoming_events_sub') }}</p>
        </div>
        <div class="row g-4">
            @foreach($upcomingEvents->take(3) as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card event-card h-100 border-0 shadow rounded-4 overflow-hidden">
                        <a href="{{ route('events.show', $event) }}" class="text-decoration-none">
                            <div class="home-img-box home-img-box--event position-relative">
                                @if($event->image)
                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="home-img-box__img" loading="lazy" decoding="async">
                                @else
                                    <div class="home-img-box__placeholder"><i class="bi bi-calendar-event text-muted fs-1"></i></div>
                                @endif
                                <div class="event-overlay">
                                    <div class="event-badge"><i class="bi bi-calendar-event me-1"></i>{{ __('common.event_badge') }}</div>
                                    <div class="event-status-badge"><i class="bi bi-clock me-1"></i>{{ __('common.upcoming_badge') }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="card-body">
                            <div class="event-meta mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $event->start_date->format('d M Y') }}
                                </small>
                            </div>
                            <h5 class="card-title event-title fw-bold" style="color: #000 !important;">{{ $event->localized_title }}</h5>
                            <p class="card-text event-description small" style="color: #333 !important;">
                                {{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}
                            </p>
                            <div class="event-info mb-3">
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $event->localized_location ?: __('common.location_tba') }}</small>
                            </div>
                            <div class="countdown-timer mb-3" data-event-id="{{ $event->id }}" data-date="{{ $event->start_date->format('Y-m-d H:i:s') }}">
                                <div class="text-center">
                                    <div class="countdown-item-single">
                                        <span class="countdown-number days">00</span>
                                        <small class="countdown-label">{{ __('common.days') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-primary rounded-pill w-100">{{ __('common.view_event') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('events.index') }}" class="btn btn-dark rounded-pill px-4 py-2 btn-home-section">
                <i class="bi bi-calendar3-event me-2"></i>{{ __('common.all_events') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- Artikel Section (3 item) -->
@if($featuredArticles->count() > 0)
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-2" style="color: var(--dark-brown);">{{ __('common.latest_articles') }}</h2>
            <p class="lead mb-0" style="color: var(--medium-brown);">{{ __('common.articles_sub') }}</p>
        </div>
        <div class="row g-4">
            @foreach($featuredArticles->take(3) as $article)
                <div class="col-md-6 col-lg-4">
                    <div class="card article-card h-100 border-0 shadow rounded-4 overflow-hidden">
                        <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                            <div class="home-img-box home-img-box--article position-relative">
                                @if($article->featured_image)
                                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="home-img-box__img" loading="lazy" decoding="async">
                                @else
                                    <div class="home-img-box__placeholder"><i class="bi bi-newspaper text-muted fs-1"></i></div>
                                @endif
                                <div class="article-overlay"><div class="article-badge"><i class="bi bi-newspaper me-1"></i>{{ __('common.articles') }}</div></div>
                            </div>
                        </a>
                        <div class="card-body">
                            <h5 class="card-title article-title fw-bold" style="color: #000 !important;">{{ $article->localized_title }}</h5>
                            <div class="card-text article-excerpt small" style="color: #333 !important;">
                                {!! $article->excerpt_html ?: Str::limit(strip_tags($article->content_html), 120) !!}
                            </div>
                            <div class="article-stats d-flex justify-content-between align-items-center small text-muted">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $article->created_at->format('d M Y') }}</span>
                                <span><i class="bi bi-eye me-1"></i>{{ $article->views }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary rounded-pill w-100">{{ __('common.read_article') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('articles.index') }}" class="btn btn-dark rounded-pill px-4 py-2 btn-home-section">
                <i class="bi bi-journal-text me-2"></i>{{ __('common.view_all_articles') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 cta-section-home">
    <div class="container text-center">
        <h2 class="cta-section-home__title">{{ __('common.cta_ready_title') }}</h2>
        <p class="cta-section-home__subtitle">{{ __('common.cta_ready_sub') }}</p>
        <div class="homepage-cta-buttons">
            <button type="button" class="homepage-btn homepage-btn-primary border-0" data-bs-toggle="modal" data-bs-target="#orderManualModal" title="{{ __('common.order_via_wa') }}">
                <i class="bi bi-cart-plus me-2"></i>{{ __('common.shop_now') }}
            </button>
            <a href="{{ route('products.index') }}" class="homepage-btn homepage-btn-outline">
                <i class="bi bi-box me-2"></i>{{ __('common.view_catalog') }}
            </a>
        </div>
    </div>
</section>
@endsection

{{-- Countdown styles and scripts are now loaded from external files --}}

