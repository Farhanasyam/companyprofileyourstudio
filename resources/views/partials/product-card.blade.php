{{--
    Kartu produk publik (beranda, katalog, kategori, produk terkait).
    Props: $product (dengan relasi category), $variant = 'catalog' | 'home'
--}}
@php
    $variant = $variant ?? 'catalog';
    $name = $product->localized_name ?: $product->name;
    // Hanya deskripsi singkat yang ditulis khusus per produk (bukan teks umum kategori di deskripsi lengkap)
    $desc = trim(strip_tags($product->localized_short_description ?: ''));
    $showDesc = $desc !== '' && mb_strtolower($desc) !== mb_strtolower($name);
    // variants_count dari withCount() bila ada, agar tidak query per kartu
    $variantCount = $product->variants_count ?? $product->variants()->count();
@endphp
<div class="prod-card prod-card--{{ $variant }} h-100">
    <a href="{{ route('products.show', $product) }}" class="prod-card__img-wrap" aria-label="{{ $name }}">
        @if($product->display_image_url)
            <img src="{{ $product->display_image_url }}" alt="{{ $name }}" class="prod-card__img" loading="lazy" decoding="async">
        @else
            <span class="prod-card__noimg {{ $product->category?->placeholder_class ?? 'ph ph--default' }}">
                <i class="bi {{ $product->category?->placeholder_icon ?? 'bi-box-seam' }}"></i>
            </span>
        @endif
        @if($product->is_featured)
            <span class="prod-card__badge"><i class="bi bi-star-fill"></i> {{ __('common.featured') }}</span>
        @endif
    </a>
    <div class="prod-card__body">
        @if($product->category)
            <span class="prod-card__cat">{{ $product->category->localized_name ?: $product->category->name }}</span>
        @endif
        <h3 class="prod-card__title"><a href="{{ route('products.show', $product) }}">{{ $name }}</a></h3>
        @if($variantCount > 1)
            <span class="prod-card__variants"><i class="bi bi-grid-3x3-gap"></i> {{ __('common.variants_count', ['count' => $variantCount]) }}</span>
        @endif
        @if($showDesc)
            <p class="prod-card__desc">{{ Str::limit($desc, 80) }}</p>
        @endif
        <div class="prod-card__actions">
            <a href="{{ route('products.show', $product) }}" class="btn btn-sm prod-card__btn-detail rounded-pill">{{ __('common.view_detail') }}</a>
            @if($variantCount > 0)
                {{-- Produk bervarian: pilih varian dulu di halaman detail --}}
                <a href="{{ route('products.show', $product) }}" class="btn btn-sm prod-card__btn-cart rounded-pill">
                    <i class="bi bi-grid-3x3-gap"></i> {{ __('common.choose_variant_btn') }}
                </a>
            @else
                <button type="button" class="btn btn-sm prod-card__btn-cart rounded-pill btn-add-to-order-cart"
                        data-product-id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#orderManualModal"
                        title="{{ __('common.order_via_wa') }}" aria-label="{{ __('common.add_to_cart') }}: {{ $name }}">
                    <i class="bi bi-cart-plus"></i> {{ __('common.cart_short') }}
                </button>
            @endif
        </div>
    </div>
</div>
