{{--
    Kartu artikel publik (halaman Artikel & beranda).
    Props: $article, $featured (bool, badge "Unggulan" bila true)
--}}
@php
    $featured = $featured ?? false;
    $title = $article->localized_title ?? $article->title;
@endphp
<div class="art-card">
    <a href="{{ route('articles.show', $article) }}" class="art-card__img-wrap" aria-label="{{ $title }}">
        @if($article->featured_image)
            <img src="{{ $article->featured_image_url }}" alt="{{ $title }}" class="art-card__img" loading="lazy" decoding="async">
        @else
            <span class="art-card__noimg"><i class="bi bi-newspaper"></i></span>
        @endif
        @if($featured)
            <span class="art-card__badge art-card__badge--featured"><i class="bi bi-star-fill me-1"></i>{{ __('common.featured') }}</span>
        @else
            <span class="art-card__badge">{{ __('common.articles') }}</span>
        @endif
    </a>
    <div class="art-card__body">
        <div class="art-card__meta">
            <span><i class="bi bi-calendar3"></i> {{ ($article->published_at ?? $article->created_at)?->format('d M Y') ?? '-' }}</span>
            <span><i class="bi bi-eye"></i> {{ $article->views }}</span>
        </div>
        <h3 class="art-card__title">
            <a href="{{ route('articles.show', $article) }}">{{ $title }}</a>
        </h3>
        <p class="art-card__excerpt">{{ Str::limit(strip_tags($article->excerpt_html ?: $article->content_html), 110) }}</p>
        @if($article->tags && count($article->tags) > 0)
            <div class="art-card__tags">
                @foreach(array_slice($article->tags, 0, 3) as $tag)
                    <span class="art-card__tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
        <div class="art-card__actions">
            <a href="{{ route('articles.show', $article) }}" class="art-card__read-btn">
                <i class="bi bi-arrow-right-circle"></i> {{ __('common.read_article') }}
            </a>
        </div>
    </div>
</div>
