@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">{{ $aboutSections['hero']->getLocalizedTitle($locale) ?? __('common.about_us') }}</h1>
            <p class="lead">{{ $aboutSections['hero']->getLocalizedSubtitle($locale) ?? __('common.learn_more_about') . ' ' . \App\Models\Setting::get('company_name', 'YourStudio') }}</p>
        </div>
    </div>
</section>

<!-- About Content -->
@if(isset($aboutSections['history']) && $aboutSections['history']->is_active)
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">{{ $aboutSections['history']->getLocalizedTitle($locale) ?? __('common.our_story') }}</h2>
                @if($aboutSections['history']->getLocalizedContent($locale))
                    <p class="lead">{{ $aboutSections['history']->getLocalizedContent($locale) }}</p>
                @endif
                @if($aboutSections['history']->getLocalizedDescription($locale))
                    <p>{{ $aboutSections['history']->getLocalizedDescription($locale) }}</p>
                @endif
            </div>
            <div class="col-lg-6">
                @if($aboutImages->count() > 0)
                    <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($aboutImages as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" 
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
@if((isset($aboutSections['vision']) && $aboutSections['vision']->is_active) || (isset($aboutSections['mission']) && $aboutSections['mission']->is_active))
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            @if(isset($aboutSections['vision']) && $aboutSections['vision']->is_active)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-eye text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold">{{ $aboutSections['vision']->getLocalizedTitle($locale) ?? __('common.vision') }}</h4>
                        <p class="text-muted">{{ $aboutSections['vision']->getLocalizedContent($locale) ?? __('common.vision') . ' ' . __('common.our_company') . ' belum ditentukan.' }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            @if(isset($aboutSections['mission']) && $aboutSections['mission']->is_active)
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-bullseye text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold">{{ $aboutSections['mission']->getLocalizedTitle($locale) ?? __('common.mission') }}</h4>
                        <p class="text-muted">{{ $aboutSections['mission']->getLocalizedContent($locale) ?? __('common.mission') . ' ' . __('common.our_company') . ' belum ditentukan.' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Why Choose Us -->
@if(isset($aboutSections['why_choose_us']) && $aboutSections['why_choose_us']->is_active)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ $aboutSections['why_choose_us']->getLocalizedTitle($locale) ?? __('common.why_choose_us') }}</h2>
            @if($aboutSections['why_choose_us']->getLocalizedSubtitle($locale))
                <p class="text-muted">{{ $aboutSections['why_choose_us']->getLocalizedSubtitle($locale) }}</p>
            @endif
        </div>
        
        @if($aboutSections['why_choose_us']->getLocalizedFeatures($locale) && count($aboutSections['why_choose_us']->getLocalizedFeatures($locale)) > 0)
        <div class="row">
            @foreach($aboutSections['why_choose_us']->getLocalizedFeatures($locale) as $feature)
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
@if(isset($aboutSections['contact_info']) && $aboutSections['contact_info']->is_active)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">{{ $aboutSections['contact_info']->getLocalizedTitle($locale) ?? __('common.get_in_touch') }}</h2>
                @if($aboutSections['contact_info']->getLocalizedSubtitle($locale))
                    <p class="lead mb-4">{{ $aboutSections['contact_info']->getLocalizedSubtitle($locale) }}</p>
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
