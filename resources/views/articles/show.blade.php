@extends('layouts.app')

@section('favicon')
{!! \App\Helpers\FaviconHelper::renderFaviconTags($article) !!}
@endsection

@section('content')
<!-- Article Header -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="modern-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">
                                    <i class="bi bi-house-door"></i>{{ __('common.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('articles.index') }}">
                                    <i class="bi bi-newspaper"></i>{{ __('common.articles') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-file-text"></i>{{ $article->localized_title ?? $article->title }}
                            </li>
                        </ol>
                    </nav>
                </div>
                
                <h1 class="display-5 fw-bold mb-3">{{ $article->localized_title ?? $article->title }}</h1>
                
                <div class="d-flex align-items-center text-muted mb-4">
                    <i class="bi bi-calendar me-2"></i>
                    <span class="me-3">{{ $article->published_at ? $article->published_at->format('d M Y') : '-' }}</span>
                    <i class="bi bi-eye me-2"></i>
                    <span>{{ __('common.seen_count', ['count' => $article->views]) }}</span>
                </div>
                
                @if($article->tags)
                    <div class="mb-4">
                        @foreach($article->tags as $tag)
                            <span class="badge bg-primary me-2">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                @if($article->featured_image)
                    <div class="mb-4">
                        <img src="{{ $article->featured_image_url }}" 
                             class="img-fluid rounded shadow" 
                             alt="{{ $article->localized_title ?? $article->title }}"
                             style="max-height: 400px; width: 100%; object-fit: cover;">
                    </div>
                @endif
                
                @if($article->localized_excerpt ?? $article->excerpt)
                    <div class="alert alert-info article-summary-box">
                        <h5>{{ __('common.summary') }}</h5>
                        <div class="mb-0 article-html">{!! $article->excerpt_html !!}</div>
                    </div>
                @endif
                
                <div class="article-content article-html">
                    <div class="article-content-inner">
                        {!! $article->content_html !!}
                    </div>
                </div>
                
                <!-- Article Meta -->
                <div class="mt-5 pt-4 border-top">
                    <div class="row">
                        <div class="col-md-12">
                            <h6>{{ __('common.article_info') }}</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-calendar me-2"></i>{{ __('common.published') }}: {{ $article->published_at ? $article->published_at->format('d M Y H:i') : '-' }}</li>
                                <li><i class="bi bi-eye me-2"></i>{{ __('common.seen_count', ['count' => $article->views]) }}</li>
                                <li><i class="bi bi-clock me-2"></i>{{ __('common.reading_time_min', ['min' => $article->reading_time]) }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Articles -->
@if($relatedArticles->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="fw-bold mb-4">{{ __('common.related_articles') }}</h3>
                <div class="row">
                    @foreach($relatedArticles as $relatedArticle)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                @if($relatedArticle->featured_image)
                                    <img src="{{ $relatedArticle->featured_image_url }}" 
                                         class="card-img-top" 
                                         alt="{{ $relatedArticle->localized_title ?? $relatedArticle->title }}"
                                         style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 180px;">
                                        <i class="bi bi-newspaper text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h6 class="card-title fw-bold" style="color: #000 !important;">{{ $relatedArticle->localized_title ?? $relatedArticle->title }}</h6>
                                    <p class="card-text small" style="color: #333 !important;">
                                        {!! Str::limit(trim(strip_tags($relatedArticle->localized_excerpt ?? $relatedArticle->excerpt ?: $relatedArticle->content, '<strong><b><em><i><u><span>')), 80) !!}
                                    </p>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('articles.show', $relatedArticle) }}" class="btn btn-sm btn-outline-primary w-100">
                                        {{ __('common.read_article') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="fw-bold mb-3">{{ __('common.article_cta_title') }}</h3>
                <p class="lead mb-4">{{ __('common.article_cta_sub') }}</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-box me-2"></i>{{ __('common.view_products') }}
                    </a>
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-newspaper me-2"></i>{{ __('common.other_articles') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
}

.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4,
.article-content h5,
.article-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1.5rem 0;
}

.article-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6c757d;
}

.article-content ul,
.article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.article-content li {
    margin-bottom: 0.5rem;
}
</style>
@endsection
