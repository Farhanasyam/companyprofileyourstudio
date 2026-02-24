@extends('layouts.app')

@section('favicon')
{!! \App\Helpers\FaviconHelper::renderFaviconTags($category) !!}
@endsection

@section('content')
<!-- Category Header -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('common.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('common.products') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->localized_name }}</li>
            </ol>
        </nav>
        
        <div class="text-center">
            @if($category->image)
                <img src="{{ $category->image_url }}" 
                     alt="{{ $category->localized_name }}" 
                     class="rounded-circle mb-3 shadow" 
                     style="width: 120px; height: 120px; object-fit: cover;">
            @endif
            
            <h1 class="display-5 fw-bold mb-3" style="color: var(--dark-brown) !important;">{{ $category->localized_name }}</h1>
            @if($category->localized_description)
                <p class="lead text-muted">{{ $category->localized_description }}</p>
            @endif
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-5 bg-gradient-primary">
    <div class="container">
        @if($products->count() > 0)
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 style="color: var(--dark-brown) !important;">{{ $products->total() }} {{ __('common.products_found') }}</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>{{ __('common.all_products') }}
                    </a>
                </div>
            </div>
            
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card product-card h-100 border-0 shadow-sm">
                            <div class="product-image-container">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" 
                                         class="product-image" 
                                         alt="{{ $product->name }}"
                                         loading="lazy"
                                         onerror="this.style.display='none'; var ph=this.nextElementSibling; if(ph) ph.classList.remove('d-none');">
                                    <div class="bg-light d-flex align-items-center justify-content-center d-none" style="min-height: 250px;">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    </div>
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="min-height: 250px;">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    </div>
                                @endif
                                <div class="product-overlay">
                                    <div class="product-badge">
                                        <i class="bi bi-box me-1"></i>{{ __('common.products') }}
                                    </div>
                                    @if($product->is_featured)
                                        <div class="featured-badge">
                                            <i class="bi bi-star-fill me-1"></i>{{ __('common.featured') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="product-meta mb-2">
                                    <span class="badge product-category" style="background: var(--light-pink); color: var(--dark-brown); border: 1px solid var(--light-brown);">{{ $product->category->localized_name }}</span>
                                </div>
                                <h5 class="card-title product-title fw-bold" style="color: var(--dark-brown) !important;">{{ $product->localized_name }}</h5>
                                <p class="card-text product-description" style="color: var(--dark-grey) !important;">
                                    {{ Str::limit($product->localized_short_description ?: $product->localized_description, 80) }}
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
                                    <i class="bi bi-eye me-2"></i>{{ __('common.view_detail') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-box fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">{{ __('common.no_products_in_category') }}</h4>
                <p class="text-muted">{{ __('common.products_will_appear') }}</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('common.view_all_products') }}
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Other Categories -->
<section class="py-5 bg-gradient-secondary">
    <div class="container">
        <h3 class="fw-bold text-center mb-4" style="color: var(--dark-brown) !important;">{{ __('common.other_categories') }}</h3>
        <div class="row">
            @foreach(\App\Models\Category::active()->where('id', '!=', $category->id)->ordered()->take(6)->get() as $otherCategory)
                <div class="col-md-4 col-sm-6 mb-3">
                    <a href="{{ route('products.category', $otherCategory) }}" class="text-decoration-none">
                        <div class="card category-card h-100 text-center border-0 shadow-sm">
                            <div class="card-body">
                                <div class="category-image-container">
                                    @if($otherCategory->image)
                                        <img src="{{ $otherCategory->image_url }}" 
                                             alt="{{ $otherCategory->name }}" 
                                             class="category-image">
                                    @else
                                        <div class="category-placeholder">
                                            <i class="bi bi-tag fs-4"></i>
                                        </div>
                                    @endif
                                    <div class="category-overlay">
                                        <div class="category-badge">
                                            <i class="bi bi-tag me-1"></i>{{ __('common.category') }}
                                        </div>
                                    </div>
                                </div>
                                <h6 class="card-title category-title fw-bold" style="color: var(--dark-brown) !important;">{{ $otherCategory->localized_name }}</h6>
                                <small class="category-count" style="color: var(--dark-grey) !important; background: var(--light-pink); padding: 4px 12px; border-radius: 15px; border: 1px solid var(--light-brown); font-weight: 500;">{{ $otherCategory->products_count ?? 0 }} {{ __('common.products_count') }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

