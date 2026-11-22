@extends('layouts.app')


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
                    <div class="col-6 col-lg-4 col-xl-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
            <div class="d-flex flex-column align-items-center mt-5 gap-2">
                <nav aria-label="Pagination">{{ $products->links() }}</nav>
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
        <div class="row g-3 justify-content-center">
            @foreach(\App\Models\Category::active()->where('id', '!=', $category->id)->ordered()->withCount(['products' => fn ($q) => $q->where('is_active', true)])->take(6)->get() as $otherCategory)
                <div class="col-12 col-sm-6 col-lg-4">
                    @include('partials.category-tile', ['category' => $otherCategory])
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>
@endsection

