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
                                    <i class="bi bi-house-door"></i>Beranda
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('articles.index') }}">
                                    <i class="bi bi-newspaper"></i>Artikel
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-file-text"></i>{{ $article->title }}
                            </li>
                        </ol>
                    </nav>
                </div>
                
                <h1 class="display-5 fw-bold mb-3">{{ $article->title }}</h1>
                
                <div class="d-flex align-items-center text-muted mb-4">
                    <i class="bi bi-calendar me-2"></i>
                    <span class="me-3">{{ $article->published_at->format('d M Y') }}</span>
                    <i class="bi bi-eye me-2"></i>
                    <span>{{ $article->views }} views</span>
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
                             alt="{{ $article->title }}"
                             style="max-height: 400px; width: 100%; object-fit: cover;">
                    </div>
                @endif
                
                @if($article->excerpt)
                    <div class="alert alert-info">
                        <h5>Ringkasan</h5>
                        <p class="mb-0">{!! trim(strip_tags($article->excerpt, '<strong><b><em><i><u><span>')) !!}</p>
                    </div>
                @endif
                
                <div class="article-content">
                    {!! $article->content !!}
                </div>
                
                <!-- Article Meta -->
                <div class="mt-5 pt-4 border-top">
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Informasi Artikel</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-calendar me-2"></i>Dipublikasikan: {{ $article->published_at->format('d M Y H:i') }}</li>
                                <li><i class="bi bi-eye me-2"></i>Dilihat: {{ $article->views }} kali</li>
                                <li><i class="bi bi-clock me-2"></i>Waktu baca: {{ $article->reading_time }} menit</li>
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
                <h3 class="fw-bold mb-4">Artikel Terkait</h3>
                <div class="row">
                    @foreach($relatedArticles as $relatedArticle)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                @if($relatedArticle->featured_image)
                                    <img src="{{ $relatedArticle->featured_image_url }}" 
                                         class="card-img-top" 
                                         alt="{{ $relatedArticle->title }}"
                                         style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 180px;">
                                        <i class="bi bi-newspaper text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h6 class="card-title">{{ $relatedArticle->title }}</h6>
                                    <p class="card-text text-muted small">
                                        {!! Str::limit(trim(strip_tags($relatedArticle->excerpt ?: $relatedArticle->content, '<strong><b><em><i><u><span>')), 80) !!}
                                    </p>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('articles.show', $relatedArticle) }}" class="btn btn-sm btn-outline-primary w-100">
                                        Baca Artikel
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
                <h3 class="fw-bold mb-3">Suka dengan artikel ini?</h3>
                <p class="lead mb-4">Temukan produk terbaik untuk mewujudkan kreativitas Anda</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-box me-2"></i>Lihat Produk
                    </a>
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-newspaper me-2"></i>Artikel Lainnya
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
