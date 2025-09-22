<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {!! app('seo')->renderMetaTags() !!}
    
    <!-- Favicon -->
    @hasSection('favicon')
        @yield('favicon')
    @else
        @if(\App\Models\Setting::get('favicon'))
            <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
            <link rel="apple-touch-icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
            <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
        @endif
    @endif
    
    <!-- Critical CSS -->
    <style>
        {!! file_get_contents(public_path('css/critical.css')) !!}
    </style>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('css/modern-styles.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('css/countdown.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    
    <!-- TinyMCE Configuration -->
    <x-head.tinymce-config />
    
    @stack('styles')
    
    <!-- JSON-LD Structured Data -->
    {!! app('seo')->renderJsonLd() !!}
</head>
<body>
    <!-- Modern Navigation -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
        <div class="container">
            <a class="navbar-brand modern-brand" href="{{ url('/') }}">
                <img src="{{ asset('storage/images/logo1.png') }}" alt="{{ \App\Models\Setting::get('company_name', 'YourStudio') }}" height="45" class="brand-logo">
            </a>
            
            <button class="navbar-toggler modern-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto modern-nav">
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house-door me-1"></i>{{ __('common.home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-box me-1"></i>{{ __('common.products') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                            <i class="bi bi-newspaper me-1"></i>{{ __('common.articles') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                            <i class="bi bi-calendar-event me-1"></i>{{ __('common.events') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="bi bi-info-circle me-1"></i>{{ __('common.about') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="bi bi-telephone me-1"></i>{{ __('common.contact') }}
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav modern-social">
                    @if(\App\Models\Setting::get('instagram_url'))
                        <li class="nav-item">
                            <a class="nav-link social-link instagram" href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </li>
                    @endif
                    @if(\App\Models\Setting::get('shopee_url'))
                        <li class="nav-item">
                            <a class="nav-link social-link shopee" href="{{ \App\Models\Setting::get('shopee_url') }}" target="_blank">
                                <i class="bi bi-shop"></i>
                            </a>
                        </li>
                    @endif
                    @if(\App\Models\Setting::get('tiktok_url'))
                        <li class="nav-item">
                            <a class="nav-link social-link tiktok" href="{{ \App\Models\Setting::get('tiktok_url') }}" target="_blank">
                                <i class="bi bi-tiktok"></i>
                            </a>
                        </li>
                    @endif
                    
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle modern-link" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-translate me-1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end modern-dropdown" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item {{ app()->getLocale() == 'id' ? 'active' : '' }}" href="{{ route('language.switch', 'id') }}">🇮🇩 Bahasa Indonesia</a></li>
                            <li><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">🇺🇸 English</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>{{ \App\Helpers\SettingHelper::getCompanyName() }}</h5>
                    <p>{{ \App\Helpers\SettingHelper::getCompanyDescription() }}</p>
                </div>
                <div class="col-md-4">
                    <h5>{{ __('common.contact') }}</h5>
                    <p>
                        @if(\App\Models\Setting::get('company_address'))
                            <i class="bi bi-geo-alt me-2"></i>{{ \App\Models\Setting::get('company_address') }}<br>
                        @endif
                        @if(\App\Models\Setting::get('company_phone'))
                            <i class="bi bi-telephone me-2"></i>{{ \App\Models\Setting::get('company_phone') }}<br>
                        @endif
                        @if(\App\Models\Setting::get('company_email'))
                            <i class="bi bi-envelope me-2"></i>{{ \App\Models\Setting::get('company_email') }}
                        @endif
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>{{ __('common.operating_hours') }}</h5>
                    <p>{{ \App\Models\Setting::get('company_operating_hours', 'Senin - Jumat: 08:00 - 17:00') }}</p>
                    
                    <h6 class="mt-3">{{ __('common.follow_us') }}</h6>
                    <div class="d-flex gap-2">
                        @if(\App\Models\Setting::get('instagram_url'))
                            <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" class="text-light" style="color: #E4405F !important;">
                                <i class="bi bi-instagram fs-4"></i>
                            </a>
                        @endif
                        @if(\App\Models\Setting::get('shopee_url'))
                            <a href="{{ \App\Models\Setting::get('shopee_url') }}" target="_blank" class="text-light" style="color: #ee4d2d !important;">
                                <i class="bi bi-shop fs-4"></i>
                            </a>
                        @endif
                        @if(\App\Models\Setting::get('facebook_url'))
                            <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank" class="text-light">
                                <i class="bi bi-facebook fs-4"></i>
                            </a>
                        @endif
                        @if(\App\Models\Setting::get('youtube_url'))
                            <a href="{{ \App\Models\Setting::get('youtube_url') }}" target="_blank" class="text-light">
                                <i class="bi bi-youtube fs-4"></i>
                            </a>
                        @endif
                        @if(\App\Models\Setting::get('tiktok_url'))
                            <a href="{{ \App\Models\Setting::get('tiktok_url') }}" target="_blank" class="text-light" style="color: #000000 !important;">
                                <i class="bi bi-tiktok fs-4"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'YourStudio') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('common.admin_login') }}
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/optimized.js') }}"></script>
    <script src="{{ asset('js/countdown.js') }}"></script>
    
    <!-- Translation Helper -->
    <script>
        window.trans = {
            'days': '{{ __('common.days') }}',
            'hours': '{{ __('common.hours') }}',
            'minutes': '{{ __('common.minutes') }}',
            'seconds': '{{ __('common.seconds') }}',
            'event_started': '{{ __('common.event_started') }}'
        };
    </script>
    
    @stack('scripts')
    
    <!-- Simple JavaScript Effects -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navbar scroll effect
            const navbar = document.querySelector('.navbar-modern');
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
        
    </script>
</body>
</html>
