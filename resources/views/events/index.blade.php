@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary" style="color: var(--dark-brown);">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">Events & Workshop</h1>
            <p class="lead">Bergabunglah dengan event dan workshop menarik dari kami</p>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="py-5">
    <div class="container">
                    @if($upcomingEvents->count() == 0 && $ongoingEvents->count() == 0 && $pastEvents->count() == 0)
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-calendar-x" style="font-size: 4rem; color: var(--medium-brown);"></i>
                            </div>
                            <h4 style="color: var(--dark-brown);" class="mt-3 fw-bold">Belum Ada Event</h4>
                            <p style="color: var(--medium-brown);" class="fs-5">Event menarik akan segera hadir! Pantau terus halaman ini untuk update terbaru.</p>
                            <div class="mt-4">
                                <div class="d-inline-block px-4 py-2 rounded-pill" style="background: var(--light-yellow); color: var(--dark-brown);">
                                    <i class="bi bi-bell me-2"></i>
                                    Coming Soon
                                </div>
                            </div>
                        </div>
        @else
            <!-- Upcoming Events -->
            @if($upcomingEvents->count() > 0)
            <div class="mb-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bold" style="color: var(--dark-brown);">Event Mendatang</h2>
                    <p style="color: var(--medium-brown);">Bergabunglah dengan event menarik yang akan datang</p>
            </div>
            
                <div class="row">
                    @foreach($upcomingEvents as $event)
                        <div class="col-md-4 mb-4">
                            <div class="card event-card h-100 border-0 shadow-sm">
                                <div class="event-image-container">
                                @if($event->image)
                                        <img src="{{ $event->image_url }}" 
                                             class="event-image" 
                                             alt="{{ $event->title }}"
                                             style="width: 100%; height: 220px; object-fit: cover; object-position: center;">
                                @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 220px;">
                                            <i class="bi bi-calendar-event text-muted fs-1"></i>
                                    </div>
                                @endif
                                    <div class="event-overlay">
                                        <div class="event-badge">
                                            <i class="bi bi-calendar-event me-1"></i>Event
                                        </div>
                                        <div class="event-status-badge">
                                            <i class="bi bi-clock me-1"></i>Mendatang
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="event-meta mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $event->start_date->format('d M Y') }}
                                        </small>
                                    </div>
                                    <h5 class="card-title event-title">{{ $event->localized_title }}</h5>
                                    <p class="card-text text-muted event-description">
                                        {{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}
                                    </p>
                                    
                                    <div class="event-info mb-3">
                                        <div class="event-location">
                                            <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                                {{ $event->localized_location ?: 'Lokasi TBA' }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Ultra-Simplified Countdown Timer (Days Only) -->
                                    <div class="countdown-timer mb-3" data-event-id="{{ $event->id }}" data-date="{{ $event->start_date->format('Y-m-d H:i:s') }}">
                                        <div class="text-center">
                                            <div class="countdown-item-single">
                                                <span class="countdown-number days">00</span>
                                                <small class="countdown-label">Hari</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-primary w-100">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
                @endif
                
                <!-- Ongoing Events -->
                @if($ongoingEvents->count() > 0)
            <div class="mb-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bold" style="color: var(--dark-brown);">Event Berlangsung</h2>
                    <p style="color: var(--medium-brown);">Event yang sedang berlangsung saat ini</p>
                </div>
                
                <div class="row">
                    @foreach($ongoingEvents as $event)
                        <div class="col-md-4 mb-4">
                            <div class="card event-card h-100 border-0 shadow-sm">
                                <div class="event-image-container">
                                @if($event->image)
                                        <img src="{{ $event->image_url }}" 
                                             class="event-image" 
                                             alt="{{ $event->title }}"
                                             style="width: 100%; height: 220px; object-fit: cover; object-position: center;">
                                @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 220px;">
                                            <i class="bi bi-calendar-event text-muted fs-1"></i>
                                    </div>
                                @endif
                                    <div class="event-overlay">
                                        <div class="event-badge">
                                            <i class="bi bi-calendar-event me-1"></i>Event
                                        </div>
                                        <div class="event-status-badge">
                                            <i class="bi bi-play-circle me-1"></i>Berlangsung
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="event-meta mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-play-circle me-1"></i>Sedang Berlangsung
                                        </small>
                                    </div>
                                    <h5 class="card-title event-title">{{ $event->localized_title }}</h5>
                                    <p class="card-text text-muted event-description">
                                        {{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}
                                    </p>
                                    
                                    <div class="event-info mb-3">
                                        <div class="event-location">
                                            <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                                {{ $event->localized_location ?: 'Lokasi TBA' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-success w-100">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
                @endif
                
                <!-- Past Events -->
                @if($pastEvents->count() > 0)
            <div class="mb-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bold" style="color: var(--dark-brown);">Event Selesai</h2>
                    <p style="color: var(--medium-brown);">Event yang telah berakhir</p>
                </div>
                
                <div class="row">
                    @foreach($pastEvents as $event)
                        <div class="col-md-4 mb-4">
                            <div class="card event-card h-100 border-0 shadow-sm">
                                <div class="event-image-container">
                                @if($event->image)
                                        <img src="{{ $event->image_url }}" 
                                             class="event-image" 
                                             alt="{{ $event->title }}"
                                             style="width: 100%; height: 220px; object-fit: cover; object-position: center;">
                                @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 220px;">
                                            <i class="bi bi-calendar-event text-muted fs-1"></i>
                                    </div>
                                @endif
                                    <div class="event-overlay">
                                        <div class="event-badge">
                                            <i class="bi bi-calendar-event me-1"></i>Event
                                        </div>
                                        <div class="event-status-badge">
                                            <i class="bi bi-check-circle me-1"></i>Selesai
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="event-meta mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $event->start_date->format('d M Y') }}
                                        </small>
                                    </div>
                                    <h5 class="card-title event-title">{{ $event->localized_title }}</h5>
                                    <p class="card-text text-muted event-description">
                                        {{ Str::limit($event->localized_short_description ?: $event->localized_description, 100) }}
                                    </p>
                                    
                                    <div class="event-info mb-3">
                                        <div class="event-location">
                                            <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                                {{ $event->localized_location ?: 'Lokasi TBA' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary w-100">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
                @endif
    </div>
</section>
@endsection

@section('styles')
<style>
/* Enhanced Countdown Timer Styles */
.countdown-timer {
    margin: 1rem 0;
    position: relative;
}

.countdown-item {
    background: var(--white);
    border: 2px solid var(--light-brown);
    border-radius: 15px;
    padding: 15px 8px;
    margin: 0 3px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.countdown-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(253, 231, 129, 0.3), transparent);
    transition: left 0.5s;
}

.countdown-item:hover::before {
    left: 100%;
}

.countdown-number {
    display: block;
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--dark-brown);
    text-shadow: 0 2px 4px rgba(85, 57, 20, 0.1);
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.countdown-label {
    display: block;
    font-size: 0.7rem;
    color: var(--light-brown);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-top: 5px;
    position: relative;
    z-index: 2;
}

/* Special effects for countdown */
.countdown-item:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 12px 30px rgba(211, 159, 105, 0.3);
    border-color: var(--yellow);
    background: var(--light-yellow);
}

.countdown-item:hover .countdown-number {
    color: var(--dark-brown);
    transform: scale(1.1);
}

.countdown-item:hover .countdown-label {
    color: var(--dark-brown);
}

/* Event Card Enhancements */
.event-card {
    position: relative;
    background: var(--white);
    border: 1px solid var(--light-brown);
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
}

.event-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--light-pink);
    z-index: 2;
}

.event-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    background: var(--white);
}

/* Event Image Container */
.event-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 20px 20px 0 0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    width: 100%;
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.event-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    filter: brightness(1.05) contrast(1.1);
}

.event-image-container:hover .event-image {
    transform: scale(1.05);
    filter: brightness(1.1) contrast(1.15) saturate(1.2);
}

.event-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.event-card:hover .event-overlay {
    opacity: 1;
    transform: translateY(0);
}

.event-badge {
    background: var(--light-pink);
    color: var(--dark-brown);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(254, 201, 211, 0.4);
    border: 2px solid var(--white);
}

.event-status-badge {
    background: var(--yellow);
    color: var(--dark-brown);
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    box-shadow: 0 3px 10px rgba(253, 231, 129, 0.4);
    border: 2px solid var(--white);
    animation: pulse 2s infinite;
}

/* Event Content */
.event-meta {
    background: var(--light-pink);
    padding: 8px 12px;
    border-radius: 15px;
    display: inline-block;
    margin-bottom: 1rem;
    border: 1px solid var(--light-brown);
}

.event-title {
    color: var(--dark-brown);
    font-weight: 700;
    font-size: 1.1rem;
    line-height: 1.4;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.event-card:hover .event-title {
    color: var(--light-brown);
    transform: scale(1.02);
}

.event-description {
    line-height: 1.6;
    color: var(--dark-grey);
    font-size: 0.95rem;
}

.event-info {
    background: var(--light-pink);
    padding: 10px 15px;
    border-radius: 15px;
    border: 1px solid var(--light-brown);
}

.event-location small {
    color: var(--dark-brown);
    font-weight: 500;
}

.event-location i {
    color: #000000;
}

/* Responsive Design */
@media (max-width: 768px) {
    .event-card {
        border-radius: 20px;
    }
    
    .event-image-container {
        border-radius: 15px 15px 0 0;
    }
    
    .event-badge {
        padding: 6px 12px;
        font-size: 0.7rem;
    }
    
    .event-status-badge {
        padding: 4px 8px;
        font-size: 0.6rem;
    }
    
    .event-title {
        font-size: 1rem;
    }
    
    .countdown-item {
        margin: 0 0.1rem;
        padding: 0.75rem 0.25rem;
    }
    
    .countdown-number {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .event-card {
        border-radius: 15px;
    }
    
    .event-image-container {
        border-radius: 10px 10px 0 0;
    }
    
    .event-badge {
        padding: 4px 8px;
        font-size: 0.6rem;
    }
    
    .event-status-badge {
        padding: 3px 6px;
        font-size: 0.5rem;
    }
    
    .event-title {
        font-size: 0.95rem;
    }
    
    .countdown-item {
        padding: 0.5rem 0.25rem;
    }
    
    .countdown-number {
        font-size: 1.25rem;
    }
}
</style>
@endsection

{{-- Countdown styles and scripts are now loaded from external files --}}