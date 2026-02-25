@extends('layouts.app')

@section('content')
<div class="index-page">
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary index-hero">
    <div class="container">
        <div class="text-center">
            <h1 class="display-5 fw-bold mb-3">{{ __('common.articles_tips') }}</h1>
            <p class="lead mb-0">{{ __('common.articles_hero_sub') }}</p>
        </div>
    </div>
</section>

<!-- Featured Articles -->
@if($featuredArticles->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="index-section-title">{{ __('common.featured_articles') }}</h2>
            <p class="index-section-subtitle mb-0">{{ __('common.featured_articles_sub') }}</p>
        </div>
        <div class="row g-4">
            @foreach($featuredArticles as $article)
                <div class="col-md-4">
                    <div class="art-card">
                        <a href="{{ route('articles.show', $article) }}" class="art-card__img-wrap">
                            @if($article->featured_image)
                                <img src="{{ $article->featured_image_url }}" alt="{{ $article->localized_title ?? $article->title }}" class="art-card__img" loading="lazy">
                            @else
                                <span class="art-card__noimg"><i class="bi bi-newspaper"></i></span>
                            @endif
                            <span class="art-card__badge"><i class="bi bi-star-fill me-1"></i>{{ __('common.featured') }}</span>
                        </a>
                        <div class="art-card__body">
                            <div class="art-card__meta">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $article->published_at ? $article->published_at->format('d M Y') : '-' }}</span>
                                <span><i class="bi bi-eye me-1"></i>{{ $article->views }}</span>
                            </div>
                            <h3 class="art-card__title">
                                <a href="{{ route('articles.show', $article) }}">{{ $article->localized_title ?? $article->title }}</a>
                            </h3>
                            <p class="art-card__excerpt">{{ Str::limit(strip_tags($article->excerpt_html ?: $article->content_html), 100) }}</p>
                            @if($article->tags && count($article->tags) > 0)
                                <div class="art-card__tags">
                                    @foreach(array_slice($article->tags, 0, 3) as $tag)
                                        <span class="art-card__tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="art-card__actions">
                                <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-primary rounded-pill">
                                    <i class="bi bi-journal-text me-1"></i>{{ __('common.read_article') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- All Articles -->
<section class="py-5 bg-gradient-primary">
    <div class="container">
        @if($featuredArticles->count() > 0)
            <div class="text-center mb-5">
                <h2 class="index-section-title" style="color: var(--dark-brown) !important;">{{ __('common.all_articles') }}</h2>
                <p class="index-section-subtitle mb-0">{{ __('common.explore_all_articles') }}</p>
            </div>
        @endif
        @if($articles->count() > 0)
            <div class="row g-4">
                @foreach($articles as $article)
                    <div class="col-md-6 col-lg-4">
                        <div class="art-card">
                            <a href="{{ route('articles.show', $article) }}" class="art-card__img-wrap">
                                @if($article->featured_image)
                                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->localized_title ?? $article->title }}" class="art-card__img" loading="lazy">
                                @else
                                    <span class="art-card__noimg"><i class="bi bi-newspaper"></i></span>
                                @endif
                                <span class="art-card__badge">{{ __('common.articles') }}</span>
                            </a>
                            <div class="art-card__body">
                                <div class="art-card__meta">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $article->published_at ? $article->published_at->format('d M Y') : '-' }}</span>
                                    <span><i class="bi bi-eye me-1"></i>{{ $article->views }}</span>
                                </div>
                                <h3 class="art-card__title">
                                    <a href="{{ route('articles.show', $article) }}">{{ $article->localized_title ?? $article->title }}</a>
                                </h3>
                                <p class="art-card__excerpt">{{ Str::limit(strip_tags($article->excerpt_html ?: $article->content_html), 100) }}</p>
                                @if($article->tags && count($article->tags) > 0)
                                    <div class="art-card__tags">
                                        @foreach(array_slice($article->tags, 0, 3) as $tag)
                                            <span class="art-card__tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="art-card__actions">
                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="bi bi-arrow-right me-1"></i>{{ __('common.read_article') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center index-empty" style="background: rgba(255,255,255,0.5); border-color: rgba(85,57,20,0.3);">
                <i class="bi bi-journal-text fs-1" style="color: var(--dark-brown); opacity: 0.6;"></i>
                <h4 class="mt-3 fw-bold" style="color: var(--dark-brown);">{{ __('common.articles_empty_title') }}</h4>
                <p class="text-muted mb-0">{{ __('common.articles_empty_text') }}</p>
            </div>
        @endif
    </div>
</section>
</div>
@endsection
