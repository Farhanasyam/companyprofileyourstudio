@extends('layouts.app')

@section('favicon')
{!! \App\Helpers\FaviconHelper::renderFaviconTags($event) !!}
@endsection

@section('content')
@push('styles')
<link href="{{ asset('css/events.css') }}" rel="stylesheet">
@endpush

<!-- Hero -->
<section class="evd-hero">
    <div class="evd-hero__bg">
        @if($event->image)
            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="evd-hero__img">
        @endif
        <div class="evd-hero__overlay"></div>
    </div>
    <div class="evd-hero__content">
        <div class="container">
            <nav aria-label="breadcrumb" class="evd-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door"></i> {{ __('common.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events.index') }}"><i class="bi bi-calendar-event"></i> {{ __('common.events') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($event->localized_title, 45) }}</li>
                </ol>
            </nav>
            @if($event->is_upcoming)
                <span class="evd-hero__pill evd-hero__pill--upcoming"><i class="bi bi-clock me-1"></i> {{ __('common.events_filter_upcoming') }}</span>
            @elseif($event->is_ongoing)
                <span class="evd-hero__pill evd-hero__pill--ongoing"><i class="bi bi-play-circle-fill me-1"></i> {{ __('common.ongoing_badge') }}</span>
            @else
                <span class="evd-hero__pill evd-hero__pill--past"><i class="bi bi-check-circle-fill me-1"></i> {{ __('common.past_badge') }}</span>
            @endif
            <h1 class="evd-hero__title">{{ $event->localized_title }}</h1>
            @if($event->localized_short_description)
                <p class="evd-hero__lead">{{ Str::limit($event->localized_short_description, 180) }}</p>
            @endif
        </div>
    </div>
</section>

<!-- Info Bar -->
<section class="evd-info">
    <div class="container">
        <div class="evd-info__grid">
            <div class="evd-info__item">
                <div class="evd-info__icon"><i class="bi bi-calendar3"></i></div>
                <div>
                    <div class="evd-info__label">{{ __('common.date_label') }}</div>
                    <div class="evd-info__value">{{ $event->start_date->format('d M Y') }}@if($event->end_date) – {{ $event->end_date->format('d M Y') }}@endif</div>
                </div>
            </div>
            <div class="evd-info__item">
                <div class="evd-info__icon"><i class="bi bi-clock"></i></div>
                <div>
                    <div class="evd-info__label">{{ __('common.time_label') }}</div>
                    <div class="evd-info__value">{{ $event->start_date->format('H:i') }} WIB</div>
                </div>
            </div>
            <div class="evd-info__item">
                <div class="evd-info__icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="evd-info__label">{{ __('common.event_location') }}</div>
                    <div class="evd-info__value">{{ $event->localized_location ?: 'TBA' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Countdown (upcoming only) -->
@if($event->is_upcoming)
<section class="evd-countdown-bar">
    <div class="container">
        <div class="evd-countdown-bar__inner">
            <span class="evd-countdown-bar__text"><i class="bi bi-hourglass-split me-2"></i>{{ __('common.event_starts_in') }}</span>
            <div class="countdown-timer" data-event-id="{{ $event->id }}" data-date="{{ $event->start_date->format('Y-m-d H:i:s') }}">
                <div class="countdown-item-single">
                    <span class="countdown-number days">0</span>
                    <small class="countdown-label">{{ __('common.days') }}</small>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Main + Sidebar -->
<section class="evd-main">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-8">
                <article class="evd-article">
                    <div class="evd-article__body">
                        <h2 class="evd-article__heading"><i class="bi bi-file-text"></i> {{ __('common.event_description') }}</h2>
                        <div class="evd-article__content">
                            {!! nl2br(e($event->localized_description)) !!}
                        </div>
                    </div>
                </article>

                @if($event->video)
                    <div class="evd-media">
                        <div class="evd-media__body">
                            <h3 class="evd-media__title"><i class="bi bi-play-circle"></i> {{ __('common.video_event') }}</h3>
                            <div class="ratio ratio-16x9">
                                <video controls>
                                    <source src="{{ $event->video_url }}" type="video/mp4">
                                    {{ __('common.video_not_supported') }}
                                </video>
                            </div>
                        </div>
                    </div>
                @endif

                @if($event->gallery && count($event->gallery) > 0)
                    <div class="evd-media">
                        <div class="evd-media__body">
                            <h3 class="evd-media__title"><i class="bi bi-images"></i> {{ __('common.gallery') }}</h3>
                            <div id="evdGalleryCarousel" class="carousel slide evd-gallery-slider" data-bs-ride="false" data-bs-interval="false">
                                <div class="carousel-indicators">
                                    @foreach($event->gallery as $i => $media)
                                        <button type="button" data-bs-target="#evdGalleryCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
                                    @endforeach
                                </div>
                                <div class="carousel-inner evd-gallery-slider__inner rounded-3 overflow-hidden">
                                    @foreach($event->gallery as $i => $media)
                                        @php
                                            $ext = strtolower(pathinfo($media, PATHINFO_EXTENSION));
                                            $isVideo = in_array($ext, ['mp4', 'webm', 'avi', 'mov']);
                                        @endphp
                                        <div class="carousel-item evd-gallery-slider__item {{ $i === 0 ? 'active' : '' }}">
                                            @if($isVideo)
                                                <div class="evd-gallery-slider__video-wrap ratio ratio-16x9">
                                                    <video controls class="evd-gallery-slider__video">
                                                        <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                                    </video>
                                                </div>
                                            @else
                                                <div class="evd-gallery-slider__img-wrap">
                                                    <img src="{{ asset('storage/' . $media) }}" class="evd-gallery-slider__img" alt="{{ $event->localized_title }} - {{ $i + 1 }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev evd-gallery-slider__prev" type="button" data-bs-target="#evdGalleryCarousel" data-bs-slide="prev">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="visually-hidden">{{ __('common.previous') }}</span>
                                </button>
                                <button class="carousel-control-next evd-gallery-slider__next" type="button" data-bs-target="#evdGalleryCarousel" data-bs-slide="next">
                                    <i class="bi bi-chevron-right"></i>
                                    <span class="visually-hidden">{{ __('common.next') }}</span>
                                </button>
                            </div>
                            <p class="evd-gallery-slider__counter text-muted small mt-2 mb-0 text-center">
                                <span class="evd-gallery-slider__current">1</span> / {{ count($event->gallery) }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="evd-sidebar">
                    <div class="evd-cta-card">
                        <div class="evd-cta-card__body">
                            <h3 class="evd-cta-card__title">{{ __('common.register_info') }}</h3>
                            @if($event->is_upcoming)
                                @php
                                    $wa = preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('whatsapp_event_registration', '6281234567890'));
                                    $msg = "Halo, saya ingin mendaftar event:\n\n*" . $event->localized_title . "*\n📅 " . $event->start_date->format('d F Y, H:i') . "\n📍 " . ($event->localized_location ?: 'TBA') . "\n\nMohon info lebih lanjut. Terima kasih!";
                                    $waUrl = 'https://wa.me/' . $wa . '?text=' . urlencode($msg);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="evd-cta-btn">
                                    <i class="bi bi-whatsapp"></i> {{ __('common.register_via_wa') }}
                                </a>
                            @elseif($event->is_ongoing)
                                <div class="evd-cta-badge evd-cta-badge--ongoing">
                                    <i class="bi bi-play-circle-fill"></i>
                                    <span class="fw-semibold">{{ __('common.event_ongoing_msg') }}</span>
                                </div>
                            @else
                                <div class="evd-cta-badge evd-cta-badge--past">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span class="fw-semibold">{{ __('common.event_ended_msg') }}</span>
                                </div>
                            @endif
                            <a href="{{ route('events.index') }}" class="evd-cta-back mt-3">
                                <i class="bi bi-arrow-left"></i> {{ __('common.back_to_all_events') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related -->
@if($relatedEvents->count() > 0)
<section class="evd-related">
    <div class="container">
        <h2 class="evd-related__title">{{ __('common.other_events') }}</h2>
        <p class="evd-related__sub">{{ __('common.event_cta_sub') }}</p>
        <div class="row g-4">
            @foreach($relatedEvents as $rel)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('events.show', $rel) }}" class="evd-related-card">
                        <div class="evd-related-card__img-wrap">
                            @if($rel->image)
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="evd-related-card__img">
                            @else
                                <div class="evd-related-card__ph"><i class="bi bi-calendar-event"></i></div>
                            @endif
                            <span class="evd-related-card__date"><i class="bi bi-calendar3 me-1"></i>{{ $rel->start_date->format('d M') }}</span>
                        </div>
                        <div class="evd-related-card__body">
                            <h3 class="evd-related-card__title">{{ $rel->localized_title }}</h3>
                            <p class="evd-related-card__excerpt">{{ Str::limit($rel->localized_short_description ?: $rel->localized_description, 90) }}</p>
                            <span class="evd-related-card__link">{{ __('common.view_detail_link') }} <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Bottom CTA -->
<section class="evd-cta-section">
    <div class="container">
        <div class="evd-cta-box">
            <h2 class="evd-cta-box__title">{{ __('common.event_cta_title') }}</h2>
            <p class="evd-cta-box__text">{{ __('common.event_cta_sub') }}</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('contact') }}" class="btn btn-light"> <i class="bi bi-chat-dots me-2"></i>{{ __('common.contact_us') }}</a>
                <a href="{{ route('events.index') }}" class="btn btn-outline-light"> <i class="bi bi-calendar-event me-2"></i>{{ __('common.view_all_events') }}</a>
            </div>
        </div>
    </div>
</section>

@if($event->gallery && count($event->gallery) > 1)
@push('scripts')
<script>
(function() {
    var carousel = document.getElementById('evdGalleryCarousel');
    var currentEl = document.querySelector('.evd-gallery-slider__current');
    var total = {{ count($event->gallery) }};
    if (carousel && currentEl) {
        carousel.addEventListener('slid.bs.carousel', function(e) {
            currentEl.textContent = (e.to + 1);
        });
    }
})();
</script>
@endpush
@endif
@endsection
