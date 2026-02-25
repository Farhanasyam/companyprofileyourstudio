@extends('layouts.app')

@section('favicon')
{!! \App\Helpers\FaviconHelper::renderFaviconTags($category) !!}
@endsection

@section('content')
<div class="index-page">
<!-- Category Header -->
<section class="py-5 bg-gradient-primary index-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="modern-breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('common.products') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->localized_name }}</li>
            </ol>
        </nav>
        <div class="text-center">
            @if($category->image)
                <img src="{{ $category->image_url }}" alt="{{ $category->localized_name }}" class="rounded-circle mb-3 shadow index-category-thumb" style="width: 120px; height: 120px; object-fit: cover;">
            @endif
            <h1 class="display-5 fw-bold mb-3">{{ $category->localized_name }}</h1>
            @if($category->localized_description)
                <p class="lead mb-0">{{ $category->localized_description }}</p>
            @endif
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-5 products-grid-section">
    <div class="container">
        @if($products->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h3 class="fw-bold mb-0" style="color: var(--dark-brown) !important;">{{ $products->total() }} {{ __('common.products_found') }}</h3>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-index">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('common.all_products') }}
                </a>
            </div>
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="prod-card prod-card--catalog h-100">
                            <a href="{{ route('products.show', $product) }}" class="prod-card__img-wrap">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="prod-card__img" loading="lazy">
                                @else
                                    <span class="prod-card__noimg"><i class="bi bi-box-seam"></i></span>
                                @endif
                                @if($product->is_featured)
                                    <span class="prod-card__badge"><i class="bi bi-star-fill"></i> {{ __('common.featured') }}</span>
                                @endif
                            </a>
                            <div class="prod-card__body">
                                <span class="prod-card__cat">{{ $product->category->localized_name ?? $product->category->name }}</span>
                                <h3 class="prod-card__title"><a href="{{ route('products.show', $product) }}">{{ $product->localized_name ?? $product->name }}</a></h3>
                                <p class="prod-card__desc">{{ Str::limit($product->localized_short_description ?? $product->short_description ?: $product->description, 80) }}</p>
                                <small class="prod-card__date"><i class="bi bi-calendar3"></i> {{ $product->created_at->format('d M Y') }}</small>
                                <div class="prod-card__actions">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-primary rounded-pill"><i class="bi bi-eye"></i> {{ __('common.view_detail') }}</a>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#orderManualModal" title="{{ __('common.order_via_wa') }}"><i class="bi bi-cart-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center index-empty">
                <i class="bi bi-box-seam fs-1" style="color: var(--dark-brown); opacity: 0.6;"></i>
                <h4 class="mt-3 fw-bold" style="color: var(--dark-brown);">{{ __('common.no_products_in_category') }}</h4>
                <p class="text-muted mb-0">{{ __('common.products_will_appear') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-index mt-3">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('common.view_all_products') }}
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Other Categories - struktur sama dengan products.index -->
<section class="py-5 category-section">
    <div class="container">
        <div class="category-section__header text-center mb-5">
            <h2 class="category-section__title">{{ __('common.other_categories') }}</h2>
            <p class="category-section__subtitle mb-0">{{ __('common.category_section_sub') }}</p>
        </div>
        <div class="row g-4">
            @foreach(\App\Models\Category::active()->where('id', '!=', $category->id)->ordered()->take(6)->get() as $otherCategory)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('products.category', $otherCategory) }}" class="category-card">
                        <div class="category-card__inner">
                            <div class="category-card__thumb">
                                @if($otherCategory->image)
                                    <img src="{{ $otherCategory->image_url }}" alt="{{ $otherCategory->localized_name ?? $otherCategory->name }}">
                                @else
                                    <span class="category-card__icon"><i class="bi bi-tag-fill"></i></span>
                                @endif
                            </div>
                            <h3 class="category-card__name">{{ $otherCategory->localized_name ?? $otherCategory->name }}</h3>
                            <span class="category-card__count">{{ $otherCategory->products_count ?? 0 }} produk</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>
@endsection

