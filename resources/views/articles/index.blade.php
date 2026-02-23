@extends('layouts.app')

@section('content')
<div class="index-page">
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary index-hero">
    <div class="container">
        <div class="text-center">
            <h1 class="display-5 fw-bold mb-3">Artikel & Tips</h1>
            <p class="lead mb-0">Tips dan inspirasi untuk mewujudkan kreativitas Anda</p>
        </div>
    </div>
</section>

<!-- Featured Articles -->
@if($featuredArticles->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="index-section-title">Artikel Unggulan</h2>
            <p class="index-section-subtitle mb-0">Pilihan artikel pilihan untuk Anda</p>
        </div>
        <div class="row g-4">
            @foreach($featuredArticles as $article)
                <div class="col-md-4">
                    <div class="card article-card index-card h-100 border-0">
                        <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                            <div class="index-card__img-wrap position-relative">
                                @if($article->featured_image)
                                    <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}">
                                @else
                                    <div class="index-card__img-placeholder"><i class="bi bi-newspaper fs-1"></i></div>
                                @endif
                                <div class="article-overlay position-absolute top-0 end-0 p-3">
                                    <span class="badge rounded-pill" style="background: var(--light-brown); color: var(--white);">Artikel</span>
                                </div>
                            </div>
                        </a>
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2" style="color: var(--dark-brown); font-size: 1.05rem;">
                                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark">{{ $article->localized_title ?? $article->title }}</a>
                            </h5>
                            <p class="card-text small text-muted mb-2">
                                {!! Str::limit(trim(strip_tags($article->localized_excerpt ?? $article->excerpt ?: $article->content, '<strong><b><em><i><u><span>')), 120) !!}
                            </p>
                            @if($article->tags && count($article->tags) > 0)
                                <div class="mb-2">
                                    @foreach(array_slice($article->tags, 0, 3) as $tag)
                                        <span class="badge bg-light text-dark border me-1 small">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center small text-muted">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $article->published_at->format('d M Y') }}</span>
                                <span><i class="bi bi-eye me-1"></i>{{ $article->views }}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-primary btn-index w-100">
                                <i class="bi bi-journal-text me-2"></i>Baca Artikel
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- All Articles -->
<section class="py-5 {{ $featuredArticles->count() > 0 ? '' : '' }} bg-gradient-primary">
    <div class="container">
        @if($featuredArticles->count() > 0)
            <div class="text-center mb-5">
                <h2 class="index-section-title" style="color: var(--dark-brown) !important;">Semua Artikel</h2>
                <p class="index-section-subtitle mb-0">Jelajahi semua artikel kami</p>
            </div>
        @endif
        @if($articles->count() > 0)
            <div class="row g-4">
                @foreach($articles as $article)
                    <div class="col-md-6 col-lg-4">
                        <div class="card article-card index-card h-100 border-0">
                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                <div class="index-card__img-wrap position-relative">
                                    @if($article->featured_image)
                                        <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}">
                                    @else
                                        <div class="index-card__img-placeholder"><i class="bi bi-newspaper fs-1"></i></div>
                                    @endif
                                    <div class="article-overlay position-absolute top-0 end-0 p-3">
                                        <span class="badge rounded-pill" style="background: var(--light-brown); color: var(--white);">Artikel</span>
                                    </div>
                                </div>
                            </a>
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-2" style="color: var(--dark-brown); font-size: 1.05rem;">
                                    <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark">{{ $article->localized_title ?? $article->title }}</a>
                                </h5>
                                <p class="card-text small text-muted mb-2">
                                    {!! Str::limit(trim(strip_tags($article->localized_excerpt ?? $article->excerpt ?: $article->content, '<strong><b><em><i><u><span>')), 100) !!}
                                </p>
                                @if($article->tags && count($article->tags) > 0)
                                    <div class="mb-2">
                                        @foreach(array_slice($article->tags, 0, 3) as $tag)
                                            <span class="badge bg-light text-dark border me-1 small">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center small text-muted">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $article->published_at->format('d M Y') }}</span>
                                    <span><i class="bi bi-eye me-1"></i>{{ $article->views }}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary btn-index w-100">
                                    <i class="bi bi-arrow-right me-2"></i>Baca Artikel
                                </a>
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
                <h4 class="mt-3 fw-bold" style="color: var(--dark-brown);">Belum ada artikel</h4>
                <p class="text-muted mb-0">Artikel akan muncul di sini setelah dipublikasikan</p>
            </div>
        @endif
    </div>
</section>
</div>
@endsection
