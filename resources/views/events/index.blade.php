@extends('layouts.app')

@section('content')
@push('styles')
<link href="/css/events.css" rel="stylesheet">
@endpush

<div class="ev-page">
    <!-- Hero -->
    <section class="ev-hero">
        <div class="container text-center">
            <h1 class="ev-hero__title">{{ __('common.events_workshop') }}</h1>
            @if(request()->routeIs('events.upcoming'))
                <p class="ev-hero__sub">{{ __('common.events_upcoming_hero_sub') }}</p>
                <span class="ev-hero__badge"><i class="bi bi-clock"></i> {{ __('common.events_upcoming_badge') }}</span>
            @elseif(request()->routeIs('events.completed'))
                <p class="ev-hero__sub">{{ __('common.events_completed_hero_sub') }}</p>
                <span class="ev-hero__badge"><i class="bi bi-check2-circle"></i> {{ __('common.events_completed_badge') }}</span>
            @else
                <p class="ev-hero__sub">{{ __('common.events_all_hero_sub') }}</p>
                <span class="ev-hero__badge"><i class="bi bi-calendar3"></i> {{ __('common.events_all_badge') }}</span>
            @endif
        </div>
    </section>

    <!-- Filter Pills -->
    <section class="ev-filters">
        <div class="container">
            <ul class="ev-filters__list">
                <li>
                    <a href="{{ route('events.index') }}" class="ev-filters__link {{ request()->routeIs('events.index') ? 'ev-filters__link--active' : '' }}">
                        <i class="bi bi-calendar3"></i> {{ __('common.events_filter_all') }} <span class="ev-filters__tag">{{ __('common.events_filter_tag_all') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.upcoming') }}" class="ev-filters__link {{ request()->routeIs('events.upcoming') ? 'ev-filters__link--active' : '' }}">
                        <i class="bi bi-clock"></i> {{ __('common.events_filter_upcoming') }} <span class="ev-filters__tag">{{ __('common.events_filter_tag_upcoming') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.completed') }}" class="ev-filters__link {{ request()->routeIs('events.completed') ? 'ev-filters__link--active' : '' }}">
                        <i class="bi bi-check2-circle"></i> {{ __('common.events_filter_past') }} <span class="ev-filters__tag">{{ __('common.events_filter_tag_past') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <!-- Events List -->
    <section class="ev-section">
        <div class="container">
            @if($upcomingEvents->count() == 0 && $ongoingEvents->count() == 0 && $pastEvents->count() == 0)
                <div class="ev-empty">
                    <div class="ev-empty__icon"><i class="bi bi-calendar-x"></i></div>
                    <h2 class="ev-empty__title">{{ __('common.events_empty_title') }}</h2>
                    <p class="ev-empty__text">{{ __('common.events_empty_text') }}</p>
                    <span class="ev-empty__badge"><i class="bi bi-bell"></i> {{ __('common.events_coming_soon') }}</span>
                </div>
            @else
                <!-- Upcoming -->
                @if($upcomingEvents->count() > 0)
                    <h2 class="ev-section__title">{{ __('common.events_section_upcoming') }}</h2>
                    <p class="ev-section__sub">{{ __('common.events_section_upcoming_sub') }}</p>
                    <div class="row g-4 mb-5">
                        @foreach($upcomingEvents as $event)
                            <div class="col-md-6 col-lg-4">
                                <a href="{{ route('events.show', $event) }}" class="ev-card">
                                    <div class="ev-card__img-wrap">
                                        @if($event->image)
                                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="ev-card__img">
                                        @else
                                            <div class="ev-card__img-placeholder"><i class="bi bi-calendar-event"></i></div>
                                        @endif
                                        <span class="ev-card__date-tag"><i class="bi bi-calendar3 me-1"></i>{{ $event->start_date->format('d M Y') }}</span>
                                        <span class="ev-card__status ev-card__status--upcoming">{{ __('common.events_filter_upcoming') }}</span>
                                    </div>
                                    <div class="ev-card__body">
                                        <h3 class="ev-card__title">{{ $event->localized_title }}</h3>
                                        <p class="ev-card__excerpt">{{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}</p>
                                        <div class="ev-card__meta">
                                            <i class="bi bi-geo-alt"></i>
                                            {{ $event->localized_location ?: __('common.location_tba') }}
                                        </div>
                                        @if($event->is_upcoming)
                                            <div class="ev-card__countdown countdown-timer" data-event-id="{{ $event->id }}" data-date="{{ $event->start_date->format('Y-m-d H:i:s') }}">
                                                <div class="countdown-item-single">
                                                    <span class="countdown-number days">0</span>
                                                    <small class="countdown-label">{{ __('common.days') }}</small>
                                                </div>
                                            </div>
                                        @endif
                                        <span class="ev-card__btn">{{ __('common.view_detail') }} <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Ongoing -->
                @if($ongoingEvents->count() > 0)
                    <h2 class="ev-section__title">{{ __('common.events_section_ongoing') }}</h2>
                    <p class="ev-section__sub">{{ __('common.events_section_ongoing_sub') }}</p>
                    <div class="row g-4 mb-5">
                        @foreach($ongoingEvents as $event)
                            <div class="col-md-6 col-lg-4">
                                <a href="{{ route('events.show', $event) }}" class="ev-card">
                                    <div class="ev-card__img-wrap">
                                        @if($event->image)
                                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="ev-card__img">
                                        @else
                                            <div class="ev-card__img-placeholder"><i class="bi bi-calendar-event"></i></div>
                                        @endif
                                        <span class="ev-card__date-tag"><i class="bi bi-calendar3 me-1"></i>{{ $event->start_date->format('d M Y') }}</span>
                                        <span class="ev-card__status ev-card__status--ongoing">{{ __('common.ongoing_badge') }}</span>
                                    </div>
                                    <div class="ev-card__body">
                                        <h3 class="ev-card__title">{{ $event->localized_title }}</h3>
                                        <p class="ev-card__excerpt">{{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}</p>
                                        <div class="ev-card__meta">
                                            <i class="bi bi-geo-alt"></i>
                                            {{ $event->localized_location ?: __('common.location_tba') }}
                                        </div>
                                        <span class="ev-card__btn">{{ __('common.view_detail') }} <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Past -->
                @if($pastEvents->count() > 0)
                    <h2 class="ev-section__title">{{ __('common.events_section_past') }}</h2>
                    <p class="ev-section__sub">{{ __('common.events_section_past_sub') }}</p>
                    <div class="row g-4">
                        @foreach($pastEvents as $event)
                            <div class="col-md-6 col-lg-4">
                                <a href="{{ route('events.show', $event) }}" class="ev-card ev-card--past">
                                    <div class="ev-card__img-wrap">
                                        @if($event->image)
                                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="ev-card__img">
                                        @else
                                            <div class="ev-card__img-placeholder"><i class="bi bi-calendar-event"></i></div>
                                        @endif
                                        <span class="ev-card__date-tag"><i class="bi bi-calendar3 me-1"></i>{{ $event->start_date->format('d M Y') }}</span>
                                        <span class="ev-card__status ev-card__status--past">{{ __('common.past_badge') }}</span>
                                    </div>
                                    <div class="ev-card__body">
                                        <h3 class="ev-card__title">{{ $event->localized_title }}</h3>
                                        <p class="ev-card__excerpt">{{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}</p>
                                        <div class="ev-card__meta">
                                            <i class="bi bi-geo-alt"></i>
                                            {{ $event->localized_location ?: __('common.location_tba') }}
                                        </div>
                                        <span class="ev-card__btn">{{ __('common.view_detail') }} <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </section>
</div>
@endsection
