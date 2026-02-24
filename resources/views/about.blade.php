@extends('layouts.app')

@section('content')
<!-- Hero Section -->
@php
    $heroSection = $aboutSections->get('hero');
@endphp
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">{{ $heroSection ? $heroSection->getLocalizedTitle($locale) : __('common.about_us') }}</h1>
            <p class="lead">{{ $heroSection ? $heroSection->getLocalizedSubtitle($locale) : __('common.learn_more_about') . ' ' . \App\Models\Setting::get('company_name', 'YourStudio') }}</p>
        </div>
    </div>
</section>

<!-- About Content -->
@if($aboutSections->has('history') && $aboutSections->get('history')->is_active)
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                @php $historySection = $aboutSections->get('history'); @endphp
                <h2 class="fw-bold mb-4">{{ $historySection ? $historySection->getLocalizedTitle($locale) : __('common.our_story') }}</h2>
                @if($historySection && $historySection->getLocalizedContent($locale))
                    <p class="lead">{{ $historySection->getLocalizedContent($locale) }}</p>
                @endif
                @if($historySection && $historySection->getLocalizedDescription($locale))
                    <p>{{ $historySection->getLocalizedDescription($locale) }}</p>
                @endif
            </div>
            <div class="col-lg-6">
                @if($aboutImages->count() > 0)
                    <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($aboutImages as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="/storage/{{ ltrim($image->image, '/') }}" 
                                         class="d-block w-100 rounded" 
                                         alt="{{ $image->title }}"
                                         style="height: 400px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if($aboutImages->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-image fs-1"></i>
                            <p class="mt-2">About Images</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Vision & Mission -->
@if(($aboutSections->has('vision') && $aboutSections->get('vision')->is_active) || ($aboutSections->has('mission') && $aboutSections->get('mission')->is_active))
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            @if($aboutSections->has('vision') && $aboutSections->get('vision')->is_active)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-eye text-white fs-2"></i>
                        </div>
                        @php $visionSection = $aboutSections->get('vision'); @endphp
                        <h4 class="fw-bold">{{ $visionSection ? $visionSection->getLocalizedTitle($locale) : __('common.vision') }}</h4>
                        <p class="text-muted">{{ $visionSection && $visionSection->getLocalizedContent($locale) ? $visionSection->getLocalizedContent($locale) : __('common.vision') . ' ' . __('common.our_company') . ' belum ditentukan.' }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($aboutSections->has('mission') && $aboutSections->get('mission')->is_active)
            @php $missionSection = $aboutSections->get('mission'); @endphp
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-bullseye text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold">{{ $missionSection ? $missionSection->getLocalizedTitle($locale) : __('common.mission') }}</h4>
                        <p class="text-muted">{{ $missionSection && $missionSection->getLocalizedContent($locale) ? $missionSection->getLocalizedContent($locale) : __('common.mission') . ' ' . __('common.our_company') . ' belum ditentukan.' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Why Choose Us -->
@if($aboutSections->has('why_choose_us') && $aboutSections->get('why_choose_us')->is_active)
@php $whySection = $aboutSections->get('why_choose_us'); @endphp
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $whySection ? $whySection->getLocalizedTitle($locale) : __('common.why_choose_us') }}</h2>
            @if($whySection && $whySection->getLocalizedSubtitle($locale))
                <p class="text-muted">{{ $whySection->getLocalizedSubtitle($locale) }}</p>
            @endif
        </div>

        @if($whySection && $whySection->getLocalizedFeatures($locale) && count($whySection->getLocalizedFeatures($locale)) > 0)
        <div class="row">
            @foreach($whySection->getLocalizedFeatures($locale) as $feature)
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-{{ $feature['color'] ?? 'primary' }} rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi {{ $feature['icon'] ?? 'bi-star' }} text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold">{{ $feature['title'] ?? 'Feature' }}</h5>
                    <p class="text-muted">{{ $feature['description'] ?? 'Deskripsi feature belum ditentukan.' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

<!-- Contact Info -->
@if($aboutSections->has('contact_info') && $aboutSections->get('contact_info')->is_active)
@php $contactSection = $aboutSections->get('contact_info'); @endphp
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">{{ $contactSection ? $contactSection->getLocalizedTitle($locale) : __('common.get_in_touch') }}</h2>
                @if($contactSection && $contactSection->getLocalizedSubtitle($locale))
                    <p class="lead mb-4">{{ $contactSection->getLocalizedSubtitle($locale) }}</p>
                @endif
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-geo-alt text-primary fs-3 me-3"></i>
                            <div class="text-start">
                                <strong>Alamat</strong>
                                <br>
                                <small class="text-muted">{{ \App\Models\Setting::get('company_address', 'Jl. Contoh No. 123, Jakarta') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-telephone text-primary fs-3 me-3"></i>
                            <div class="text-start">
                                <strong>Telepon</strong>
                                <br>
                                <small class="text-muted">{{ \App\Models\Setting::get('company_phone', '+62 123 456 789') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-envelope text-primary fs-3 me-3"></i>
                            <div class="text-start">
                                <strong>Email</strong>
                                <br>
                                <small class="text-muted">{{ \App\Models\Setting::get('company_email', 'info@yourstudio.com') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-chat-dots me-2"></i>{{ __('common.send_message') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
