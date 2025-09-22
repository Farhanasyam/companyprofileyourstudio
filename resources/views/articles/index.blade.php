@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="text-center">
            <h1 class="display-5 fw-bold mb-3">Artikel & Tips</h1>
            <p class="lead">Tips dan inspirasi untuk mewujudkan kreativitas Anda</p>
        </div>
    </div>
</section>

<!-- Featured Articles -->
@if($featuredArticles->count() > 0)
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <h2 class="fw-bold mb-4">Artikel Unggulan</h2>
        <div class="row">
            @foreach($featuredArticles as $article)
                <div class="col-md-4 mb-4">
                    <div class="card article-card h-100 border-0 shadow-sm">
                        <div class="article-image-container">
                            @if($article->featured_image)
                                <img src="{{ $article->featured_image_url }}" 
                                     class="article-image" 
                                     alt="{{ $article->title }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 250px;">
                                    <i class="bi bi-newspaper text-muted fs-1"></i>
                                </div>
                            @endif
                            <div class="article-overlay">
                                <div class="article-badge">
                                    <i class="bi bi-newspaper me-1"></i>Artikel
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title article-title">{{ $article->title }}</h5>
                            <p class="card-text text-muted article-excerpt">
                                {!! Str::limit(trim(strip_tags($article->excerpt ?: $article->content, '<strong><b><em><i><u><span>')), 120) !!}
                            </p>
                            
                            @if($article->tags)
                                <div class="article-tags mb-3">
                                    @foreach($article->tags as $tag)
                                        <span class="badge article-tag me-1">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="article-stats d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $article->published_at->format('d M Y') }}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-eye me-1"></i>{{ $article->views }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-primary article-btn w-100">
                                <i class="bi bi-arrow-right me-2"></i>Baca Artikel
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
<section class="py-5 {{ $featuredArticles->count() > 0 ? 'bg-light' : '' }}">
    <div class="container">
        @if($featuredArticles->count() > 0)
            <h2 class="fw-bold mb-4">Semua Artikel</h2>
        @endif
        
        @if($articles->count() > 0)
            <div class="row">
                @foreach($articles as $article)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card article-card h-100 border-0 shadow-sm">
                            <div class="article-image-container">
                                @if($article->featured_image)
                                    <img src="{{ $article->featured_image_url }}" 
                                         class="article-image" 
                                         alt="{{ $article->title }}"
                                         style="height: 220px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 220px;">
                                        <i class="bi bi-newspaper text-muted fs-1"></i>
                                    </div>
                                @endif
                            <div class="article-overlay">
                                <div class="article-badge">
                                    <i class="bi bi-newspaper me-1"></i>Artikel
                                </div>
                            </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title article-title">{{ $article->title }}</h5>
                                <p class="card-text text-muted article-excerpt">
                                    {!! Str::limit(trim(strip_tags($article->excerpt ?: $article->content, '<strong><b><em><i><u><span>')), 100) !!}
                                </p>
                                
                                @if($article->tags)
                                    <div class="article-tags mb-3">
                                        @foreach($article->tags as $tag)
                                            <span class="badge article-tag me-1 small">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="article-stats d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $article->published_at->format('d M Y') }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-eye me-1"></i>{{ $article->views }}
                                    </small>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary article-btn w-100">
                                    <i class="bi bi-arrow-right me-2"></i>Baca Artikel
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-newspaper fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">Belum ada artikel</h4>
                <p class="text-muted">Artikel akan muncul di sini setelah dipublikasikan</p>
            </div>
        @endif
    </div>
</section>
@endsection
