@extends('layouts.app')

@section('favicon')
{!! \App\Helpers\FaviconHelper::renderFaviconTags($event) !!}
@endsection

@section('content')
<!-- Event Header -->
<section class="py-5">
    <div class="container">
        <div class="modern-breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i>{{ __('common.home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('events.index') }}">
                            <i class="bi bi-calendar-event"></i>{{ __('common.events') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="bi bi-calendar-check"></i>{{ $event->localized_title }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- Event Details -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @if($event->image)
                    <div class="mb-4 event-main-image">
                        <img src="{{ $event->image_url }}" 
                             class="img-fluid rounded-3" 
                             alt="{{ $event->title }}"
                             style="width: 100%; height: 400px; object-fit: cover; box-shadow: 0 8px 25px rgba(0,0,0,0.15);">
                    </div>
                @endif

                @if($event->video)
                    <div class="mb-4">
                        <h5 class="mb-3">Video Event</h5>
                        <div class="ratio ratio-16x9 event-video-container">
                            <video controls>
                                <source src="{{ $event->video_url }}" type="video/mp4">
                                Browser Anda tidak mendukung video.
                            </video>
                        </div>
                    </div>
                @endif

                @if($event->gallery && count($event->gallery) > 0)
                    <div class="mb-4">
                        <h5 class="mb-3">Galeri</h5>
                        <div class="row event-gallery">
                            @foreach($event->gallery as $media)
                                <div class="col-md-4 mb-3">
                                    @if(pathinfo($media, PATHINFO_EXTENSION) === 'mp4' || 
                                        pathinfo($media, PATHINFO_EXTENSION) === 'avi' || 
                                        pathinfo($media, PATHINFO_EXTENSION) === 'mov')
                                        <div class="event-video-container">
                                            <video controls class="img-fluid">
                                                <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @else
                                        <div class="event-gallery-image">
                                            <img src="{{ asset('storage/' . $media) }}" 
                                                 class="img-fluid rounded-2" 
                                                 alt="{{ $event->localized_title }}"
                                                 style="width: 100%; height: 200px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="col-lg-4">
                <div class="event-details-card">
                    <div class="card-body">
                        <h2 class="h4 fw-bold mb-3 event-title">{{ $event->localized_title }}</h2>
                        
                        <div class="mb-3">
                            <div class="event-info-item">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event"></i>
                                    <div>
                                        <strong>Tanggal Mulai</strong><br>
                                        <span class="text-muted">{{ $event->start_date->format('d F Y, H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($event->end_date)
                                <div class="event-info-item">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-check"></i>
                                        <div>
                                            <strong>Tanggal Selesai</strong><br>
                                            <span class="text-muted">{{ $event->end_date->format('d F Y, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($event->location)
                                <div class="event-info-item">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt"></i>
                                        <div>
                                            <strong>Lokasi</strong><br>
                                            <span class="text-muted">{{ $event->location }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>



                        <!-- Countdown Timer for Upcoming Events -->
                        @if($event->is_upcoming)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Event Dimulai Dalam:</h6>
                                <div class="countdown-container">
                                    <div class="countdown-timer" 
                                         data-event-id="{{ $event->id }}" 
                                         data-date="{{ $event->start_date->format('Y-m-d H:i:s') }}">
                                        <div class="countdown-item-single">
                                                    <span class="countdown-number days">0</span>
                                                    <small class="countdown-label">Hari</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            @if($event->is_upcoming)
                                @php
                                    $whatsappNumber = \App\Models\Setting::get('whatsapp_event_registration', '+62 812-3456-7890');
                                    $whatsappNumber = preg_replace('/[^0-9+]/', '', $whatsappNumber);
                                    $eventTitle = $event->localized_title;
                                    $eventDate = $event->start_date->format('d F Y, H:i');
                                    $eventLocation = $event->localized_location ?: 'Lokasi TBA';
                                    $message = "Halo, saya ingin mendaftar untuk event:\n\n*$eventTitle*\n📅 Tanggal: $eventDate\n📍 Lokasi: $eventLocation\n\nMohon informasi lebih lanjut mengenai pendaftaran. Terima kasih!";
                                    $encodedMessage = urlencode($message);
                                    $whatsappUrl = "https://wa.me/$whatsappNumber?text=$encodedMessage";
                                @endphp
                                <a href="{{ $whatsappUrl }}" target="_blank" class="event-action-btn" style="text-align: center;">
                                    <i class="bi bi-whatsapp me-2"></i>Daftar Event
                                </a>
                            @elseif($event->is_ongoing)
                                <button class="event-action-btn" disabled style="opacity: 0.8;">
                                    <i class="bi bi-play-circle me-2"></i>Event Sedang Berlangsung
                                </button>
                            @else
                                <button class="event-action-btn" disabled style="opacity: 0.6;">
                                    <i class="bi bi-check-circle me-2"></i>Event Selesai
                                </button>
                            @endif
                            
                            <a href="{{ route('events.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Description -->
        <div class="row mt-5">
            <div class="col-lg-8">
                <div class="event-description-card">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">{{ __('common.event_description') }}</h4>
                        <div class="event-description">
                            {!! nl2br(e($event->localized_description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Events -->
@if($relatedEvents->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="related-events-section">
            <h3 class="fw-bold mb-4">Event Terkait</h3>
            <div class="row">
            @foreach($relatedEvents as $relatedEvent)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="related-event-card h-100">
                        @if($relatedEvent->image)
                            <img src="{{ $relatedEvent->image_url }}" 
                                 class="card-img-top" 
                                 alt="{{ $relatedEvent->title }}"
                                 style="width: 100%; height: 220px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 220px;">
                                <i class="bi bi-calendar-event text-muted fs-1"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h6 class="card-title">{{ $relatedEvent->localized_title }}</h6>
                            <p class="card-text text-muted small">
                                {{ Str::limit($relatedEvent->localized_short_description ?: $relatedEvent->localized_description, 80) }}
                            </p>
                            
                            <div class="d-flex justify-content-start align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $relatedEvent->start_date->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('events.show', $relatedEvent) }}" class="btn btn-outline-primary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
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
                <h3 class="fw-bold mb-3">Tertarik dengan Event Kami?</h3>
                <p class="lead mb-4">Daftar sekarang atau hubungi kami untuk informasi lebih lanjut</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-chat-dots me-2"></i>Hubungi Kami
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-calendar-event me-2"></i>Lihat Semua Event
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
@if($event->is_upcoming)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Use the global countdown system from countdown.js
    // The countdown will be handled by the external countdown.js file
    console.log('Event detail countdown initialized');
});
</script>
@endif

<style>
/* Enhanced Countdown Timer Styles - Days Only */
.countdown-timer {
    margin: 1rem 0;
    position: relative;
}

.countdown-item-single {
    background: linear-gradient(135deg, var(--white) 0%, #f8f9fa 100%);
    border: 3px solid var(--light-brown);
    border-radius: 20px;
    padding: 20px 15px;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    max-width: 120px;
    margin: 0 auto;
}

.countdown-item-single::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(253, 231, 129, 0.4), transparent);
    transition: left 0.6s ease;
}

.countdown-item-single:hover::before {
    left: 100%;
}

.countdown-number {
    display: block;
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--dark-brown);
    text-shadow: 0 3px 6px rgba(85, 57, 20, 0.2);
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
    line-height: 1;
}

.countdown-label {
    display: block;
    font-size: 0.8rem;
    color: var(--light-brown);
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 1px;
    margin-top: 8px;
    position: relative;
    z-index: 2;
}

/* Special effects for single countdown */
.countdown-item-single:hover {
    transform: translateY(-10px) scale(1.08);
    box-shadow: 0 15px 40px rgba(211, 159, 105, 0.4);
    border-color: var(--yellow);
    background: linear-gradient(135deg, var(--light-yellow) 0%, #fff3cd 100%);
}

.countdown-item-single:hover .countdown-number {
    color: var(--dark-brown);
    transform: scale(1.15);
    text-shadow: 0 4px 8px rgba(85, 57, 20, 0.3);
}

.countdown-item-single:hover .countdown-label {
    color: var(--dark-brown);
    font-weight: 800;
}

/* Responsive Design for Days-Only Countdown */
@media (max-width: 768px) {
    .countdown-item-single {
        max-width: 100px;
        padding: 15px 10px;
    }
    
    .countdown-number {
        font-size: 1.8rem;
    }
    
    .countdown-label {
        font-size: 0.7rem;
    }
}

@media (max-width: 576px) {
    .countdown-item-single {
        max-width: 80px;
        padding: 12px 8px;
    }
    
    .countdown-number {
        font-size: 1.5rem;
    }
    
    .countdown-label {
        font-size: 0.6rem;
    }
}

.event-description {
    line-height: 1.8;
}

/* Event Show Page Styles */
.event-main-image {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
}

.event-main-image img {
    transition: transform 0.3s ease;
}

.event-main-image:hover img {
    transform: scale(1.02);
}

.event-gallery-image {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.event-gallery-image:hover {
    transform: translateY(-3px);
}

.event-gallery-image img {
    transition: transform 0.3s ease;
}

.event-gallery-image:hover img {
    transform: scale(1.05);
}

.related-event-card {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.related-event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.related-event-card .card-img-top {
    border-radius: 15px 15px 0 0;
}

.event-details-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(142, 103, 63, 0.1);
}

.event-description-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(142, 103, 63, 0.1);
}

.event-action-btn {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.event-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
}

.event-action-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .event-main-image img {
        height: 250px !important;
    }
    
    .event-gallery-image img {
        height: 150px !important;
    }
    
    .related-event-card .card-img-top {
        height: 180px !important;
    }
}
</style>
@endsection
