@extends('layouts.app')

@section('content')
<div class="index-page gallery-page">
    <section class="py-5 bg-gradient-primary index-hero">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3">{{ __('common.gallery') }}</h1>
            <p class="lead mb-0">{{ __('common.gallery_subtitle') }} {{ \App\Models\Setting::get('company_name', 'YourStudio') }}</p>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            @if($galleryImages->isNotEmpty())
                <div class="row g-4">
                    @foreach($galleryImages as $image)
                        <div class="col-6 col-md-4 col-lg-3">
                            <figure class="card h-100 border-0 shadow-sm mb-0">
                                <img src="{{ $image->image_url }}"
                                     alt="{{ $image->title }}"
                                     class="card-img-top"
                                     width="640"
                                     height="480"
                                     loading="lazy"
                                     decoding="async"
                                     style="aspect-ratio: 4 / 3; object-fit: cover;">
                                <figcaption class="card-body py-3">
                                    <h2 class="h6 mb-1">{{ $image->title }}</h2>
                                    @if($image->description)
                                        <p class="small text-muted mb-0">{{ $image->description }}</p>
                                    @endif
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-images fs-1 text-muted"></i>
                    <h2 class="h4 mt-3">{{ app()->getLocale() === 'en' ? 'No gallery yet' : 'Belum ada galeri' }}</h2>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
