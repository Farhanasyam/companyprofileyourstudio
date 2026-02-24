@extends('layouts.app')

@section('content')
<div class="index-page">
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary index-hero">
    <div class="container">
        <div class="text-center">
            <h1 class="display-5 fw-bold mb-3">{{ __('common.product_catalog') }}</h1>
            <p class="lead mb-0">{{ __('common.product_catalog_sub') }}</p>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="index-filter">
    <div class="container">
        <div class="index-filter__card">
            <form method="GET" action="{{ route('products.index') }}" class="index-filter__form">
                <div class="index-filter__field index-filter__field--search">
                    <label for="search" class="index-filter__label">{{ __('common.search_products') }}</label>
                    <div class="index-filter__input-wrap">
                        <i class="bi bi-search index-filter__icon"></i>
                        <input type="text" class="form-control index-filter__input" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Nama produk, merek...">
                    </div>
                </div>
                <div class="index-filter__field">
                    <label for="category" class="index-filter__label">{{ __('common.category') }}</label>
                    <select class="form-select index-filter__select" id="category" name="category">
                        <option value="">{{ __('common.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->localized_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="index-filter__field">
                    <label for="sort" class="index-filter__label">Urutkan</label>
                    <select class="form-select index-filter__select" id="sort" name="sort">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>{{ __('common.sort_by_latest') }}</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>{{ __('common.sort_by_name_az') }}</option>
                    </select>
                </div>
                <div class="index-filter__field index-filter__field--submit">
                    <label class="index-filter__label index-filter__label--hidden">Terapkan</label>
                    <button type="submit" class="index-filter__btn">
                        <i class="bi bi-funnel-fill me-2"></i>{{ __('common.search_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-5">
    <div class="container">
        @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card product-card index-card h-100">
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                <div class="index-card__img-wrap index-card__img-wrap--cover position-relative">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" onerror="this.style.display='none'; var ph=this.nextElementSibling; if(ph) ph.classList.remove('d-none');">
                                        <div class="index-card__img-placeholder d-none"><i class="bi bi-box fs-1"></i></div>
                                    @else
                                        <div class="index-card__img-placeholder"><i class="bi bi-box fs-1"></i></div>
                                    @endif
                                    <div class="product-overlay">
                                        @if($product->is_featured)
                                            <span class="featured-badge"><i class="bi bi-star-fill me-1"></i>{{ __('common.featured') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <div class="card-body">
                                <span class="badge mb-2" style="background: var(--light-pink); color: var(--dark-brown);">{{ $product->category->localized_name ?? $product->category->name }}</span>
                                <h5 class="card-title fw-bold mb-2" style="color: var(--dark-brown); font-size: 1.05rem;">
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">{{ $product->localized_name ?? $product->name }}</a>
                                </h5>
                                <p class="card-text small text-muted mb-2" style="color: var(--dark-grey);">
                                    {{ Str::limit($product->localized_short_description ?? $product->short_description ?: $product->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center small text-muted">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $product->created_at->format('d M Y') }}</span>
                                    <span><i class="bi bi-eye me-1"></i>{{ $product->views ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-index flex-grow-1">
                                    <i class="bi bi-eye me-2"></i>{{ __('common.view_detail') }}
                                </a>
                                <button type="button" class="btn btn-success btn-index" data-bs-toggle="modal" data-bs-target="#orderManualModal" title="{{ __('common.order_via_wa') }}">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center index-empty">
                <i class="bi bi-box-seam fs-1" style="color: var(--dark-brown); opacity: 0.6;"></i>
                <h4 class="mt-3 fw-bold" style="color: var(--dark-brown);">{{ __('common.product_not_found') }}</h4>
                <p class="text-muted mb-0">{{ __('common.product_not_found_hint') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-index mt-3">
                    <i class="bi bi-arrow-clockwise me-2"></i>{{ __('common.reset_filter') }}
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 category-section">
    <div class="container">
        <div class="category-section__header text-center mb-5">
            <h2 class="category-section__title">{{ __('common.product_categories') }}</h2>
            <p class="category-section__subtitle">{{ __('common.category_section_sub') }}</p>
        </div>
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('products.category', $category) }}" class="category-card">
                        <div class="category-card__inner">
                            <div class="category-card__thumb">
                                @if($category->image)
                                    <img src="{{ $category->image_url }}" alt="{{ $category->localized_name }}">
                                @else
                                    <span class="category-card__icon"><i class="bi bi-tag-fill"></i></span>
                                @endif
                            </div>
                            <h3 class="category-card__name">{{ $category->localized_name ?? $category->name }}</h3>
                            <span class="category-card__count">{{ $category->products_count ?? 0 }} produk</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>
@endsection
