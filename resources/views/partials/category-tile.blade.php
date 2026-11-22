{{-- Kartu kategori ringkas (horizontal): thumbnail kecil + nama + jumlah produk. Props: $category --}}
<a href="{{ route('products.category', $category) }}" class="cat-tile">
    <span class="cat-tile__thumb {{ $category->image ? '' : $category->placeholder_class }}">
        @if($category->image)
            <img src="{{ $category->image_url }}" alt="" loading="lazy" decoding="async">
        @else
            <i class="bi {{ $category->placeholder_icon }}"></i>
        @endif
    </span>
    <span class="cat-tile__text">
        <span class="cat-tile__name">{{ $category->localized_name ?: $category->name }}</span>
        <span class="cat-tile__count">{{ $category->products_count ?? 0 }} {{ __('common.products_count') }}</span>
    </span>
    <i class="bi bi-arrow-right cat-tile__arrow" aria-hidden="true"></i>
</a>
