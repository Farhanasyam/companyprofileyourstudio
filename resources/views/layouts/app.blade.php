<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {!! app('seo')->renderMetaTags() !!}
    
    <!-- Favicon: logo toko di semua halaman (lihat FaviconHelper) -->
    {!! \App\Helpers\FaviconHelper::renderFaviconTags() !!}
    
    <!-- Critical CSS + main styles (path relatif agar tetap jalan di hosting) -->
    <link href="/css/critical.css" rel="stylesheet">
    
    <!-- CSS: vendor + main -->
    {{-- Bootstrap memuat grid/layout: harus render-blocking agar tidak ada layout loncat (CLS) saat loader hilang.
         Ikon boleh menyusul (async). --}}
    <link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="/css/modern-styles.css?v=27" rel="stylesheet">
    <link href="/css/countdown.css?v=2" rel="stylesheet">
    <noscript>
        <link href="/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    </noscript>
    
    @stack('styles')
    
    <!-- JSON-LD Structured Data -->
    {!! app('seo')->renderJsonLd() !!}

    {{-- Gambar konten yang gagal dimuat (file hilang/terhapus) diganti placeholder bertema,
         bukan ikon rusak + teks alt. Dipasang di <head> agar menangkap error sejak awal. --}}
    <script>
        (function () {
            var ph = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"><rect width="400" height="300" fill="#f7efe3"/><g fill="none" stroke="#d39f69" stroke-width="10" stroke-linejoin="round" opacity=".75"><rect x="150" y="105" width="100" height="80" rx="12"/><path d="M155 175l30-30 25 22 15-12 20 20"/></g><circle cx="225" cy="128" r="8" fill="#d39f69" opacity=".75"/></svg>');
            document.addEventListener('error', function (e) {
                var img = e.target;
                if (!img || img.tagName !== 'IMG' || img.dataset.fallback || img.closest('.site-loader, .order-modal, .navbar-modern')) return;
                img.dataset.fallback = '1';
                img.removeAttribute('srcset');
                img.src = ph;
                img.classList.add('img-fallback');
            }, true);
        })();
    </script>
</head>
<body>
    @php
        $loaderLogoPath = \App\Models\Setting::get('logo');
        $loaderLogoFile = $loaderLogoPath ? storage_path('app/public/' . ltrim($loaderLogoPath, '/')) : null;
    @endphp
    <div class="site-loader" id="siteLoader" role="status" aria-label="Memuat halaman">
        <div class="site-loader__mark">
            @if($loaderLogoFile && is_file($loaderLogoFile))
                <img src="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($loaderLogoPath)) }}" alt="{{ \App\Models\Setting::get('company_name', 'YourStudio') }}">
            @else
                <span>Y</span>
            @endif
        </div>
        <div class="site-loader__ring"></div>
    </div>

    <!-- Modern Navigation -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
        <div class="container">
            <a class="navbar-brand modern-brand" href="{{ url('/') }}">
                @php
                    $companyName = \App\Models\Setting::get('company_name', 'YourStudio');
                    $logoPath = \App\Models\Setting::get('logo');
                    $logoFile = $logoPath ? storage_path('app/public/' . ltrim($logoPath, '/')) : null;
                @endphp
                @if($logoFile && is_file($logoFile))
                    <img src="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($logoPath)) }}" alt="{{ $companyName }}" class="brand-logo">
                @else
                    <span class="brand-fallback"><span class="brand-fallback-mark">Y</span>{{ $companyName }}</span>
                @endif
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle modern-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="#" id="eventsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar-event me-1"></i>{{ __('common.events') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end modern-dropdown" aria-labelledby="eventsDropdown">
                            <li><a class="dropdown-item {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                                <i class="bi bi-calendar3 me-2"></i>{{ __('common.events_filter_all') }}
                                <span class="badge bg-primary ms-2">{{ __('common.events_filter_tag_all') }}</span>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item {{ request()->routeIs('events.upcoming') ? 'active' : '' }}" href="{{ route('events.upcoming') }}">
                                <i class="bi bi-clock me-2"></i>{{ __('common.events_upcoming_badge') }}
                                <span class="badge bg-success ms-2">{{ __('common.events_filter_tag_upcoming') }}</span>
                            </a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('events.completed') ? 'active' : '' }}" href="{{ route('events.completed') }}">
                                <i class="bi bi-check-circle me-2"></i>{{ __('common.events_completed_badge') }}
                                <span class="badge bg-secondary ms-2">{{ __('common.events_filter_tag_past') }}</span>
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="bi bi-info-circle me-1"></i>{{ __('common.about') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">
                            <i class="bi bi-images me-1"></i>{{ __('common.gallery') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link modern-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="bi bi-telephone me-1"></i>{{ __('common.contact') }}
                        </a>
                    </li>
                </ul>

                <div class="navbar-nav-wrap navbar-actions order-lg-2">
                    <ul class="navbar-nav navbar-actions__order">
                        <li class="nav-item d-flex align-items-center">
                            <button type="button" class="btn btn-order-wa rounded-pill px-3 py-2 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#orderManualModal" title="{{ __('common.order_via_wa') }}">
                                <i class="bi bi-cart-plus me-1"></i><span class="d-none d-md-inline">{{ __('common.order') }}</span>
                            </button>
                        </li>
                    </ul>
                    <ul class="navbar-nav navbar-actions__social modern-social">
                        @if(\App\Models\Setting::get('instagram_url'))
                            <li class="nav-item d-lg-none">
                                <a class="nav-link social-link instagram" href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" rel="noopener" title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if(\App\Models\Setting::get('shopee_url'))
                            <li class="nav-item d-lg-none">
                                <a class="nav-link social-link shopee" href="{{ \App\Models\Setting::get('shopee_url') }}" target="_blank" rel="noopener" title="Shopee">
                                    <i class="bi bi-shop"></i>
                                </a>
                            </li>
                        @endif
                        @if(\App\Models\Setting::get('tiktok_url'))
                            <li class="nav-item d-lg-none">
                                <a class="nav-link social-link tiktok" href="{{ \App\Models\Setting::get('tiktok_url') }}" target="_blank" rel="noopener" title="TikTok">
                                    <i class="bi bi-tiktok"></i>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle modern-link social-link language-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Bahasa">
                                <i class="bi bi-translate"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end modern-dropdown" aria-labelledby="languageDropdown">
                                <li><a class="dropdown-item {{ app()->getLocale() == 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">🇮🇩 Bahasa Indonesia</a></li>
                                <li><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">🇺🇸 English</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <script>
        // Jarak konten = tinggi navbar sebenarnya (bukan angka tetap 100px yang menyisakan strip di bawah navbar)
        (function () {
            var nav = document.querySelector('.navbar-modern');
            if (!nav) return;
            function setNavHeight() {
                if (nav.querySelector('.navbar-collapse.show')) return; // menu mobile sedang terbuka
                var scrolled = nav.classList.contains('scrolled');
                if (scrolled) nav.classList.remove('scrolled');
                document.documentElement.style.setProperty('--nav-h', nav.offsetHeight + 'px');
                if (scrolled) nav.classList.add('scrolled');
            }
            setNavHeight();
            window.addEventListener('resize', setNavHeight);
            window.addEventListener('load', setNavHeight);
        })();
    </script>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Modal Order via WhatsApp - produk diload saat modal dibuka (lazy) agar halaman sangat cepat -->
    <div class="modal fade" id="orderManualModal" tabindex="-1" aria-labelledby="orderManualModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content order-modal">
                <div class="modal-header order-modal__header">
                    <div class="order-modal__heading">
                        <span class="order-modal__icon"><i class="bi bi-bag-heart"></i></span>
                        <div>
                            <h5 class="modal-title" id="orderManualModalLabel">{{ __('common.order_products_modal') }}</h5>
                            <p class="order-modal__subtitle mb-0">Pilih barang, isi data, lalu kirim pesanan via WhatsApp.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="formOrderManual" data-no-enhance>
                    <div class="modal-body order-modal__body">
                        <!-- Daftar item (setiap baris = 1 barang) -->
                        <div class="order-section" id="order_items_wrap">
                            <div class="order-section__head">
                                <h6 class="order-section__title"><span class="order-section__step">1</span>Barang pesanan</h6>
                                <div class="order-section__meta">
                                    <button type="button" class="order-clear-btn d-none" id="order_btn_clear_all" title="Kosongkan daftar" aria-label="Kosongkan daftar">
                                        <i class="bi bi-x-circle"></i><span class="order-clear-btn__text ms-1">Kosongkan</span>
                                    </button>
                                    <span class="order-count-badge" id="order_item_count">0 barang</span>
                                </div>
                            </div>
                            <div id="order_items_list" class="order-items-list">
                                <div class="order-item-row" data-row="1">
                                    <div class="order-item-thumb" aria-hidden="true">
                                        <img src="" alt="" class="order-item-thumb__img d-none" onerror="this.classList.add('d-none'); this.nextElementSibling.classList.remove('d-none');">
                                        <i class="bi bi-box-seam order-item-thumb__icon"></i>
                                    </div>
                                    <div class="order-item-main">
                                        <select class="form-select order-select" id="order_select_product" aria-label="Pilih produk">
                                            <option value="">-- Memuat... --</option>
                                        </select>
                                    </div>
                                    <div class="order-item-controls">
                                        <div class="order-row-actions d-none">
                                            <div class="order-qty-group">
                                                <button type="button" class="order-qty-btn order-qty-minus" aria-label="Kurangi"><i class="bi bi-dash-lg"></i></button>
                                                <input type="number" class="order-qty" value="1" min="0" max="999" aria-label="Jumlah">
                                                <button type="button" class="order-qty-btn order-qty-plus" aria-label="Tambah"><i class="bi bi-plus-lg"></i></button>
                                            </div>
                                        </div>
                                        <button type="button" class="order-icon-btn order-btn-detail d-none" aria-label="Detail produk" title="Detail produk"><i class="bi bi-info-circle"></i></button>
                                        <button type="button" class="order-icon-btn order-icon-btn--danger order-row-remove d-none" aria-label="Hapus barang" title="Hapus barang"><i class="bi bi-trash3"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="order-items-toolbar">
                                <button type="button" class="order-add-btn" id="order_btn_tambah_item">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah barang lain
                                </button>
                            </div>
                        </div>

                        <div class="order-section">
                            <div class="order-section__head">
                                <h6 class="order-section__title"><span class="order-section__step">2</span>Data pemesan</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="order_nama_pemesan" class="form-label">Nama Pemesan <span class="order-required">*</span></label>
                                    <div class="order-input">
                                        <i class="bi bi-person"></i>
                                        <input type="text" class="form-control" id="order_nama_pemesan" name="nama_pemesan" required placeholder="Nama Anda" autocomplete="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="order_no_hp" class="form-label">No. HP / WhatsApp <span class="order-required">*</span></label>
                                    <div class="order-input">
                                        <i class="bi bi-whatsapp"></i>
                                        <input type="tel" class="form-control" id="order_no_hp" name="no_hp" required placeholder="08xxxxxxxxxx" autocomplete="tel" inputmode="tel">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="order-section">
                            <div class="order-section__head">
                                <h6 class="order-section__title"><span class="order-section__step">3</span>Pengiriman</h6>
                            </div>
                            <div class="order-ship-options" role="radiogroup" aria-label="Metode pengiriman">
                                <label class="order-ship-option">
                                    <input type="radio" name="metode_pengiriman" value="kirim" checked>
                                    <span class="order-ship-option__box">
                                        <i class="bi bi-truck"></i>
                                        <span>
                                            <strong>Dikirim ke alamat</strong>
                                            <small>Ongkir dikonfirmasi via WhatsApp</small>
                                        </span>
                                    </span>
                                </label>
                                <label class="order-ship-option">
                                    <input type="radio" name="metode_pengiriman" value="ambil">
                                    <span class="order-ship-option__box">
                                        <i class="bi bi-shop"></i>
                                        <span>
                                            <strong>Ambil di toko</strong>
                                            <small>Tanpa ongkos kirim</small>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            @php
                                $pickupAddress = \App\Models\Setting::get('company_address');
                                $pickupHours = \App\Models\Setting::get('company_operating_hours');
                            @endphp
                            <div class="order-pickup-info d-none" id="order_pickup_info">
                                <i class="bi bi-geo-alt"></i>
                                <div>
                                    <strong>Lokasi pengambilan</strong>
                                    @if($pickupAddress)
                                        <p class="mb-0">{{ $pickupAddress }}</p>
                                    @endif
                                    @if($pickupHours)
                                        <small>{{ $pickupHours }}</small>
                                    @endif
                                    @unless($pickupAddress || $pickupHours)
                                        <p class="mb-0">Alamat toko akan dikirim oleh admin via WhatsApp.</p>
                                    @endunless
                                </div>
                            </div>
                            <div class="row g-3 mt-1" id="order_address_fields">
                                <div class="col-12">
                                    <label for="order_alamat" class="form-label">Alamat lengkap <span class="order-required">*</span></label>
                                    <textarea class="form-control" id="order_alamat" name="alamat" rows="2" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan" autocomplete="street-address"></textarea>
                                </div>
                                <div class="col-md-5">
                                    <label for="order_kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control" id="order_kecamatan" name="kecamatan" placeholder="Kecamatan">
                                </div>
                                <div class="col-md-4">
                                    <label for="order_kota" class="form-label">Kota / Kabupaten <span class="order-required">*</span></label>
                                    <input type="text" class="form-control" id="order_kota" name="kota" required placeholder="Kota" autocomplete="address-level2">
                                </div>
                                <div class="col-md-3">
                                    <label for="order_kode_pos" class="form-label">Kode pos</label>
                                    <input type="text" class="form-control" id="order_kode_pos" name="kode_pos" placeholder="12345" inputmode="numeric" maxlength="10" autocomplete="postal-code">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="order_catatan" class="form-label">Catatan <span class="order-optional">(opsional)</span></label>
                                <textarea class="form-control" id="order_catatan" name="catatan" rows="2" placeholder="Patokan lokasi, waktu pengiriman, permintaan khusus, dll."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer order-modal__footer">
                        <button type="button" class="order-cancel-btn" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="order-submit-btn" id="btnKirimOrder">
                            <i class="bi bi-whatsapp me-1"></i>Kirim ke WhatsApp
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Produk (popup dari tombol Detail di keranjang) -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">{{ __('common.product_detail_modal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="order-detail-image-wrap">
                        <img id="order_detail_img" src="" alt="" class="order-detail-img">
                        <div id="order_detail_noimg" class="order-detail-noimg d-none"><i class="bi bi-image"></i> Tidak ada gambar</div>
                    </div>
                    <div class="p-4">
                        <h6 id="order_detail_name" class="mb-2"></h6>
                        <p id="order_detail_category" class="mb-2 small text-muted" style="display: none;"><strong>{{ __('common.category') }}:</strong> <span id="order_detail_category_value"></span></p>
                        <div class="small"><strong class="text-secondary">{{ __('common.short_description') }}</strong></div>
                        <div id="order_detail_desc" class="text-muted small mt-1"></div>
                        <span id="order_detail_no_desc" class="d-none">{{ __('common.no_short_description') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer text-light py-5 mt-0 footer-no-gap">
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
                    <p>{{ \App\Models\Setting::get('company_operating_hours', __('common.operating_hours_default')) }}</p>
                    
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
                            <a href="{{ \App\Models\Setting::get('tiktok_url') }}" target="_blank" class="text-light">
                                <i class="bi bi-tiktok fs-4"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'YourStudio') }}. {{ __('common.all_rights_reserved') }}.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('common.admin_login') }}
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts (local vendor = no tracking prevention blocked storage) -->
    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/vendor/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="/js/optimized.js?v=3"></script>
    <script src="/js/countdown.js?v=2"></script>

    <script>
        // Sembunyikan loader saat HTML + CSS siap (bukan menunggu semua gambar selesai di 'load'),
        // supaya konten tidak tertutup lama di koneksi lambat (lebih baik untuk LCP).
        (function () {
            function hideLoader() {
                var loader = document.getElementById('siteLoader');
                if (!loader) return;
                loader.classList.add('site-loader--hidden');
                window.setTimeout(function () { loader.remove(); }, 450);
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', hideLoader);
            } else {
                hideLoader();
            }
        })();
    </script>

    <!-- Translation Helper -->
    <script>
        window.trans = {{ \Illuminate\Support\Js::from([
            'days' => __('common.days'),
            'hours' => __('common.hours'),
            'minutes' => __('common.minutes'),
            'seconds' => __('common.seconds'),
            'event_started' => __('common.event_started'),
        ]) }};
    </script>
    
    @stack('scripts')
    
    <!-- Config Order WA + URL API (tanpa query produk di page load = web sangat cepat) -->
    <script>
        window.orderWaConfig = {
            number: @json(\App\Models\Setting::get('whatsapp_order_number', '')),
            company_name: @json(\App\Models\Setting::get('company_name', 'Toko')),
            template: @json(\App\Models\Setting::get('whatsapp_order_template', "Halo! 🙏\n\nSaya ingin memesan dari *{company_name}*:\n\n📦 *Daftar Pesanan:*\n{items}\n\n👤 *Pemesan:* {nama_pemesan}\n📱 *No. WA:* {no_hp}\n📝 *Catatan:* {catatan}\n\nTerima kasih. Salam kreatif! ✨")),
            productsUrl: @json(route('order.products')),
            storeUrl: @json(route('order.store')),
            locale: @json(app()->getLocale()),
            msg_duplicate_product: @json(__('common.product_already_in_cart')),
            msg_choose_variant: @json(__('common.variant_choose_in_cart'))
        };
    </script>
    
    <!-- Order WA (keranjang + lazy load produk) -->
    <script src="/js/site.js?v=2"></script>
</body>
</html>
