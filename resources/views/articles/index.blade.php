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

<!-- Artikel Unggulan -->
@if($featuredArticles->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="index-section-title">{{ __('common.featured_articles') }}</h2>
            <p class="index-section-subtitle mb-0">{{ __('common.featured_articles_sub') }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($featuredArticles as $article)
                <div class="col-md-4">
                    @include('partials.article-card', ['article' => $article, 'featured' => true])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Semua Artikel -->
<section class="py-5 bg-gradient-primary">
    <div class="container">
        @if($featuredArticles->count() > 0)
            <div class="text-center mb-5">
                <h2 class="index-section-title" style="color: var(--dark-brown) !important;">{{ __('common.all_articles') }}</h2>
                <p class="index-section-subtitle mb-0">{{ __('common.explore_all_articles') }}</p>
            </div>
        @endif

        @if($articles->count() > 0)
            <div class="row g-4 justify-content-center">
                @foreach($articles as $article)
                    <div class="col-md-6 col-lg-4">
                        @include('partials.article-card', ['article' => $article, 'featured' => false])
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
