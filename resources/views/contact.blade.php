@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-contact position-relative overflow-hidden">
    <div class="hero-bg"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center" style="color: var(--dark-brown);">
                <div class="hero-content">
                    <h1 class="display-3 fw-bold mb-4 animate-fade-in">{{ __('common.contact_us') }}</h1>
                    <p class="lead fs-4 mb-5 animate-fade-in-delay">{{ __('common.contact_hero_sub') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <div class="card-header-custom">
                        <h3 class="fw-bold mb-0">
                            <i class="bi bi-chat-dots me-3 text-primary"></i>
                            {{ __('common.send_message') }}
                        </h3>
                        <p class="text-muted mb-0">{{ __('common.form_fill_below') }}</p>
                    </div>
                    <div class="card-body-custom">
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                                    <div>
                                        <strong>{{ __('common.message_sent_title') }}</strong>
                                        <div class="small">{{ session('success') }}</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                            @csrf
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               placeholder="Nama Lengkap" required>
                                        <label for="name">{{ __('common.full_name') }} <span class="text-danger">*</span></label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" 
                                               placeholder="Email" required>
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}" 
                                               placeholder="Nomor Telepon">
                                        <label for="phone">{{ __('common.phone_number') }}</label>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                               id="subject" name="subject" value="{{ old('subject') }}" 
                                               placeholder="Subjek" required>
                                        <label for="subject">{{ __('common.subject') }} <span class="text-danger">*</span></label>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="message" class="form-label fw-semibold">
                                    <i class="bi bi-chat-text me-2 text-primary"></i>
                                    {{ __('common.message') }} <span class="text-danger">*</span>
                                </label>
                                <x-forms.tinymce-editor 
                                    name="message" 
                                    id="message"
                                    value="{{ old('message') }}"
                                    placeholder="Tulis pesan Anda di sini..."
                                    :required="true"
                                />
                                @error('message')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm">
                                    <i class="bi bi-send me-2"></i>{{ __('common.send_message') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="contact-info-card mb-4">
                    <div class="card-header-custom">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-info-circle me-2 text-primary"></i>
                            Informasi Kontak
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="fw-bold mb-1">Alamat</h6>
                                <p class="text-muted mb-0">
                                    {{ \App\Models\Setting::get('maps_address', \App\Models\Setting::get('company_address', 'Jl. Contoh No. 123, Jakarta')) }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="fw-bold mb-1">Telepon</h6>
                                <p class="text-muted mb-0">
                                    <a href="tel:{{ \App\Models\Setting::get('company_phone', '+62 123 456 789') }}" class="text-decoration-none">
                                        {{ \App\Models\Setting::get('company_phone', '+62 123 456 789') }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">
                                    <a href="mailto:{{ \App\Models\Setting::get('company_email', 'info@yourstudio.com') }}" class="text-decoration-none">
                                        {{ \App\Models\Setting::get('company_email', 'info@yourstudio.com') }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div class="info-content">
                                <h6 class="fw-bold mb-1">{{ __('common.operating_hours') }}</h6>
                                <p class="text-muted mb-0">
                                    {{ \App\Models\Setting::get('company_operating_hours', __('common.operating_hours_default')) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="social-media-card mb-5">
                    <div class="card-header-custom">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-share me-2 text-primary"></i>
                            Ikuti Kami
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="social-links-header">
                            @if(\App\Models\Setting::get('instagram_url'))
                                <a href="{{ \App\Models\Setting::get('instagram_url') }}" 
                                   target="_blank" 
                                   class="social-link-header instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            @endif
                            @if(\App\Models\Setting::get('shopee_url'))
                                <a href="{{ \App\Models\Setting::get('shopee_url') }}" 
                                   target="_blank" 
                                   class="social-link-header shopee">
                                    <i class="bi bi-shop"></i>
                                </a>
                            @endif
                            @if(\App\Models\Setting::get('tiktok_url'))
                                <a href="{{ \App\Models\Setting::get('tiktok_url') }}" 
                                   target="_blank" 
                                   class="social-link-header tiktok">
                                    <i class="bi bi-tiktok"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3 contact-location-title">
                <i class="bi bi-geo-alt-fill me-3"></i>
                {{ __('common.our_location') }}
            </h2>
            <p class="lead text-muted">{{ __('common.visit_store_sub') }}</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="map-container">
                    @php
                        $mapsIframe = trim(\App\Models\Setting::get('maps_iframe', '') ?? '');
                        // Hilangkan width/height tetap dari iframe agar ukuran dikontrol CSS (responsive)
                        if ($mapsIframe !== '') {
                            $mapsIframe = preg_replace('/\s*width\s*=\s*["\']?\d+%?["\']?/i', ' width="100%"', $mapsIframe);
                            $mapsIframe = preg_replace('/\s*height\s*=\s*["\']?\d+%?["\']?/i', ' height="100%"', $mapsIframe);
                        }
                    @endphp
                    @if($mapsIframe !== '')
                        <div class="maps-wrapper">
                            {!! $mapsIframe !!}
                        </div>
                    @else
                        <div class="map-placeholder">
                            <div class="placeholder-content">
                                <i class="bi bi-geo-alt fs-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Peta Lokasi</h5>
                                <p class="text-muted">Administrator dapat mengatur iframe peta di panel admin</p>
                                @auth
                                <a href="{{ route('admin.settings.index', ['section' => 'contact']) }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-gear me-2"></i>Atur Peta
                                </a>
                                @endauth
                            </div>
                        </div>
                    @endif
                </div>
                
                @if(\App\Models\Setting::get('maps_address'))
                    <div class="map-info map-info--enhanced mt-4">
                        <div class="address-info address-info--enhanced">
                            <div class="address-icon address-icon--enhanced">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="address-details address-details--enhanced">
                                <span class="address-label">{{ __('common.full_address') }}</span>
                                <p class="address-text">{{ \App\Models\Setting::get('maps_address') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@section('styles')
<style>
/* Hero Section Styles */
.hero-contact {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 60vh;
    position: relative;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23a)"/><circle cx="800" cy="800" r="400" fill="url(%23a)"/></svg>') no-repeat center center;
    background-size: cover;
    opacity: 0.3;
}

.min-vh-50 {
    min-height: 50vh;
}

/* Removed hero-stats styles as customer support section is removed */

/* Removed stat styles as customer support section is removed */

/* Animation Classes */
.animate-fade-in {
    animation: fadeInUp 0.8s ease-out;
}

.animate-fade-in-delay {
    animation: fadeInUp 0.8s ease-out 0.2s both;
}

.animate-fade-in-delay-2 {
    animation: fadeInUp 0.8s ease-out 0.4s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Card Styles */
.contact-form-card,
.contact-info-card,
.social-media-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.contact-form-card:hover,
.contact-info-card:hover,
.social-media-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-header-custom {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e9ecef;
}

.card-body-custom {
    padding: 2rem;
}

/* Form Styles */
.contact-form .form-floating {
    margin-bottom: 1rem;
}

.contact-form .form-control {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem 0.75rem;
    transition: all 0.3s ease;
}

.contact-form .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Contact Info Styles */
.contact-info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding: 1rem;
    border-radius: 12px;
    transition: background-color 0.3s ease;
}

.contact-info-item:hover {
    background-color: #f8f9fa;
}

.info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-icon i {
    color: white;
    font-size: 1.2rem;
}

.info-content h6 {
    color: #333;
    margin-bottom: 0.5rem;
}

.info-content a {
    color: #667eea;
    transition: color 0.3s ease;
}

.info-content a:hover {
    color: #764ba2;
}

/* Social Media Styles */
.social-links-header {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    align-items: center;
}

.social-link-header {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: var(--dark-brown) !important;
    background: var(--light-brown);
    border: 2px solid var(--light-brown);
    text-decoration: none;
}

.social-link-header:hover {
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 8px 25px rgba(85, 57, 20, 0.2);
    background: var(--dark-brown);
    border-color: var(--dark-brown);
    color: var(--white) !important;
}

.social-link-header i {
    font-size: 1.3rem;
}


/* Map Styles */
.map-container {
    background: transparent;
    margin: 0 auto 2rem auto;
    max-width: 100%;
    width: 100%;
    text-align: center;
}

/* Google Maps iframe — responsive (ukuran mengikuti lebar layar) */
.maps-wrapper {
    position: relative;
    width: 100%;
    max-width: 420px;
    margin: 0 auto;
    overflow: hidden;
    border-radius: 12px;
    /* Rasio 4:7 — tinggi dinaikkan lagi */
    aspect-ratio: 4 / 7;
}

.maps-wrapper iframe,
.maps-wrapper embed,
.maps-wrapper object {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    border: 0 !important;
    display: block !important;
}

/* Responsive: ukuran peta per lebar layar */
@media (min-width: 576px) {
    .maps-wrapper { max-width: 380px; }
}

@media (min-width: 768px) {
    .maps-wrapper { max-width: 420px; }
}

@media (min-width: 992px) {
    .maps-wrapper { max-width: 400px; }
}

@media (max-width: 575.98px) {
    .maps-wrapper { max-width: 100%; aspect-ratio: 4 / 7; }
}

.map-placeholder {
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.placeholder-content {
    text-align: center;
}

.map-info {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 2rem;
}

/* Enhanced location title (icon + text) */
.contact-location-title {
    color: var(--dark-brown, #553914) !important;
}
.contact-location-title .bi {
    color: var(--light-brown, #d39f69);
}

/* === Blok Alamat Lengkap (Full Address) === */
.map-info--enhanced {
    --addr-bg: linear-gradient(145deg, #fefbf4 0%, #f9f3e8 50%, #f5ede0 100%);
    --addr-border: rgba(211, 159, 105, 0.4);
    --addr-shadow: 0 8px 32px rgba(85, 57, 20, 0.08);
    background: var(--addr-bg);
    border: 1px solid var(--addr-border);
    border-radius: 18px;
    padding: 1.75rem 2rem;
    box-shadow: var(--addr-shadow);
    transition: box-shadow 0.3s ease, border-color 0.3s ease;
}
.map-info--enhanced:hover {
    box-shadow: 0 12px 40px rgba(85, 57, 20, 0.12);
    border-color: rgba(211, 159, 105, 0.55);
}

.address-info--enhanced {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
}

.address-icon--enhanced {
    width: 60px;
    height: 60px;
    min-width: 60px;
    background: linear-gradient(145deg, #d39f69 0%, #553914 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(85, 57, 20, 0.25);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.map-info--enhanced:hover .address-icon--enhanced {
    transform: scale(1.03);
    box-shadow: 0 10px 28px rgba(85, 57, 20, 0.3);
}

.address-icon--enhanced i {
    color: #fff;
    font-size: 1.6rem;
}

.address-details--enhanced {
    flex: 1;
    min-width: 0;
}

.address-label {
    display: inline-block;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #b8860b;
    margin-bottom: 0.5rem;
}

.address-text {
    font-size: 1.08rem;
    line-height: 1.7;
    color: #2c1810;
    margin: 0;
    font-weight: 500;
}

.address-info {
    display: flex;
    align-items: center;
}

.address-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 2rem;
    flex-shrink: 0;
}

.address-icon i {
    color: white;
    font-size: 1.5rem;
}

.address-details h6 {
    color: #333;
    margin-bottom: 0.5rem;
}

/* Responsive: blok alamat */
@media (max-width: 576px) {
    .address-info--enhanced {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
    }
    .address-details--enhanced {
        width: 100%;
    }
    .map-info--enhanced {
        padding: 1.25rem 1.5rem;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-contact {
        min-height: 50vh;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-item {
        margin-bottom: 1rem;
    }
    
    .card-header-custom,
    .card-body-custom {
        padding: 1.5rem;
    }
    
    .contact-info-item {
        flex-direction: column;
        text-align: center;
    }
    
    .info-icon {
        margin: 0 auto 1rem auto;
    }
    
    .social-links-header {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .address-info {
        flex-direction: column;
        text-align: center;
    }
    
    .address-icon {
        margin: 0 auto 1rem auto;
    }
}

@media (max-width: 576px) {
    .social-links-header {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .card-header-custom,
    .card-body-custom {
        padding: 1.25rem;
    }
}
</style>
@endsection
