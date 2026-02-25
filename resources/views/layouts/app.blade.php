<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {!! app('seo')->renderMetaTags() !!}
    
    <!-- Favicon -->
    @hasSection('favicon')
        @yield('favicon')
    @else
        @if(\App\Models\Setting::get('favicon'))
            @php $faviconPath = '/storage/' . ltrim(\App\Models\Setting::get('favicon'), '/'); @endphp
            <link rel="icon" type="image/x-icon" href="{{ $faviconPath }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconPath }}">
            <link rel="apple-touch-icon" href="{{ $faviconPath }}">
        @else
            <link rel="icon" type="image/x-icon" href="/favicon.ico">
            <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
            <link rel="apple-touch-icon" href="/favicon.ico">
        @endif
    @endif
    
    <!-- Critical CSS + main styles (path relatif agar tetap jalan di hosting) -->
    <link href="/css/critical.css" rel="stylesheet">
    
    <!-- CSS: vendor + main -->
    <link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="/css/modern-styles.css?v=5" rel="stylesheet">
    <link href="/css/countdown.css?v=2" rel="stylesheet">
    <noscript>
        <link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    </noscript>
    
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
                <img src="/storage/images/logo1.png" alt="{{ \App\Models\Setting::get('company_name', 'YourStudio') }}" height="45" class="brand-logo">
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
                            <li class="nav-item">
                                <a class="nav-link social-link instagram" href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" rel="noopener" title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if(\App\Models\Setting::get('shopee_url'))
                            <li class="nav-item">
                                <a class="nav-link social-link shopee" href="{{ \App\Models\Setting::get('shopee_url') }}" target="_blank" rel="noopener" title="Shopee">
                                    <i class="bi bi-shop"></i>
                                </a>
                            </li>
                        @endif
                        @if(\App\Models\Setting::get('tiktok_url'))
                            <li class="nav-item">
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

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Modal Order via WhatsApp - produk diload saat modal dibuka (lazy) agar halaman sangat cepat -->
    <div class="modal fade" id="orderManualModal" tabindex="-1" aria-labelledby="orderManualModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderManualModalLabel"><i class="bi bi-cart-plus me-2"></i>{{ __('common.order_products_modal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="formOrderManual">
                    <div class="modal-body">
                        <p class="text-muted small mb-3">Isi barang pesanan di bawah. Klik <strong>Tambah item baru</strong> untuk menambah baris barang lain. Atur jumlah dengan tombol +/−.</p>
                        <!-- Daftar item (setiap baris = 1 barang) -->
                        <div class="mb-3" id="order_items_wrap">
                            <label class="form-label">Daftar barang pesanan</label>
                            <div id="order_items_list">
                                <div class="order-item-row row g-2 align-items-center mb-2" data-row="1">
                                    <div class="col">
                                        <select class="form-select order-select" id="order_select_product">
                                            <option value="">-- Memuat... --</option>
                                        </select>
                                    </div>
                                    <div class="col-auto order-row-actions d-none">
                                        <div class="input-group input-group-sm order-qty-group">
                                            <button type="button" class="btn btn-outline-secondary order-qty-minus" aria-label="Kurangi">−</button>
                                            <input type="number" class="form-control text-center order-qty" value="1" min="0" max="999" aria-label="Jumlah">
                                            <button type="button" class="btn btn-outline-secondary order-qty-plus" aria-label="Tambah">+</button>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-outline-info btn-sm order-btn-detail d-none" aria-label="Detail"><i class="bi bi-info-circle me-1"></i>Detail</button>
                                    </div>
                                    <div class="col-auto order-row-actions d-none">
                                        <button type="button" class="btn btn-outline-danger btn-sm order-row-remove" aria-label="Hapus baris"><i class="bi bi-trash me-1"></i>Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="order_btn_tambah_item">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah item baru
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="order_btn_clear_all" title="Kosongkan daftar (satu baris tetap)">
                                    <i class="bi bi-trash me-1"></i>Hapus semua item
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="order_nama_pemesan" class="form-label">Nama Pemesan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="order_nama_pemesan" name="nama_pemesan" required placeholder="Nama Anda">
                        </div>
                        <div class="mb-3">
                            <label for="order_no_hp" class="form-label">No. HP / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="order_no_hp" name="no_hp" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="mb-3">
                            <label for="order_catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="order_catatan" name="catatan" rows="2" placeholder="Alamat, waktu pengiriman, dll."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="btnKirimOrder">
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
    <footer class="bg-dark text-light py-5 mt-0 footer-no-gap">
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
    <script src="/js/optimized.js"></script>
    <script src="/js/countdown.js"></script>
    
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
    
    <!-- Config Order WA + URL API (tanpa query produk di page load = web sangat cepat) -->
    <script>
        window.orderWaConfig = {
            number: @json(\App\Models\Setting::get('whatsapp_order_number', '')),
            company_name: @json(\App\Models\Setting::get('company_name', 'Toko')),
            template: @json(\App\Models\Setting::get('whatsapp_order_template', "Halo! 🙏\n\nSaya ingin memesan dari *{company_name}*:\n\n📦 *Daftar Pesanan:*\n{items}\n\n👤 *Pemesan:* {nama_pemesan}\n📱 *No. WA:* {no_hp}\n📝 *Catatan:* {catatan}\n\nTerima kasih. Salam kreatif! ✨")),
            productsUrl: @json(route('order.products')),
            storeUrl: @json(route('order.store')),
            locale: @json(app()->getLocale()),
            msg_duplicate_product: @json(__('common.product_already_in_cart'))
        };
    </script>
    
    <!-- Simple JavaScript Effects + Order WA (keranjang + lazy load produk) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navbar scroll effect
            var navbar = document.querySelector('.navbar-modern');
            if (navbar) window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollTop > 100) navbar.classList.add('scrolled');
                else navbar.classList.remove('scrolled');
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    var target = document.querySelector(this.getAttribute('href'));
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
            

            // Daftar item: setiap baris punya select + qty. Tombol "Tambah item baru" = clone baris.
            var orderItemsList = document.getElementById('order_items_list');
            var selectProduct = document.getElementById('order_select_product');
            var orderProductsLoaded = false;
            var maxOrderRows = 20;
            
            function escapeHtml(s) { return (s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
            function showSwal(msg, icon) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: icon || 'warning', title: icon === 'success' ? 'Berhasil' : (icon === 'error' ? 'Oops...' : 'Perhatian'), text: msg, confirmButtonText: 'OK', confirmButtonColor: '#d39f69' });
                } else { alert(msg); }
            }
            
            // ─── LocalStorage cart persistence ───────────────────────────────────────
            var CART_KEY = 'ys_order_cart';

            function saveCartToStorage() {
                try {
                    var items = collectOrderItems();
                    if (items.length > 0) {
                        localStorage.setItem(CART_KEY, JSON.stringify(items));
                    } else {
                        localStorage.removeItem(CART_KEY);
                    }
                } catch(e) {}
            }

            function clearCartStorage() {
                try { localStorage.removeItem(CART_KEY); } catch(e) {}
            }

            // Pulihkan keranjang dari localStorage setelah produk selesai di-load
            function restoreCartFromStorage() {
                var saved;
                try { saved = localStorage.getItem(CART_KEY); } catch(e) {}
                if (!saved) return;
                var items;
                try { items = JSON.parse(saved); } catch(e) {}
                if (!items || !items.length) return;
                items.forEach(function(item) {
                    if (!item.id) return;
                    var rows = orderItemsList.querySelectorAll('.order-item-row');
                    var targetRow = null;
                    // Cari baris yang sudah punya produk ini
                    for (var r = 0; r < rows.length; r++) {
                        var s = rows[r].querySelector('.order-select');
                        if (s && s.value == item.id) { targetRow = rows[r]; break; }
                    }
                    // Pakai baris kosong
                    if (!targetRow) {
                        for (var r = 0; r < rows.length; r++) {
                            var s = rows[r].querySelector('.order-select');
                            if (s && !s.value) { targetRow = rows[r]; break; }
                        }
                    }
                    // Buat baris baru jika semua penuh
                    if (!targetRow) {
                        var addBtn = document.getElementById('order_btn_tambah_item');
                        if (addBtn) addBtn.click();
                        rows = orderItemsList.querySelectorAll('.order-item-row');
                        targetRow = rows[rows.length - 1];
                    }
                    if (targetRow) {
                        var sel = targetRow.querySelector('.order-select');
                        var qtyIn = targetRow.querySelector('.order-qty');
                        if (sel) {
                            for (var i = 0; i < sel.options.length; i++) {
                                if (sel.options[i].value == item.id) { sel.selectedIndex = i; break; }
                            }
                        }
                        if (qtyIn) qtyIn.value = item.qty || 1;
                    }
                });
                toggleDetailButtons();
            }
            // ─────────────────────────────────────────────────────────────────────────

            // Helper: isi produk ke baris — cari existing, lalu pakai baris kosong, lalu buat baru
            function addProductToOrderList(pid) {
                if (!orderItemsList || !pid) return;
                var rows = orderItemsList.querySelectorAll('.order-item-row');
                // 1) Jika produk sudah ada di baris manapun, tambah qty saja
                for (var r = 0; r < rows.length; r++) {
                    var sel = rows[r].querySelector('.order-select');
                    if (sel && sel.value == pid) {
                        var qtyIn = rows[r].querySelector('.order-qty');
                        if (qtyIn) qtyIn.value = (parseInt(qtyIn.value, 10) || 0) + 1;
                        toggleDetailButtons();
                        return;
                    }
                }
                // 2) Pakai baris kosong pertama yang ada (select belum dipilih)
                var emptyRow = null;
                for (var r = 0; r < rows.length; r++) {
                    var sel = rows[r].querySelector('.order-select');
                    if (sel && !sel.value) { emptyRow = rows[r]; break; }
                }
                var targetRow = emptyRow;
                // 3) Jika tidak ada baris kosong, tambah baris baru
                if (!targetRow) {
                    var addBtn = document.getElementById('order_btn_tambah_item');
                    if (addBtn) addBtn.click();
                    rows = orderItemsList.querySelectorAll('.order-item-row');
                    targetRow = rows[rows.length - 1];
                }
                if (targetRow) {
                    var sel = targetRow.querySelector('.order-select');
                    var qtyIn = targetRow.querySelector('.order-qty');
                    if (sel) {
                        for (var i = 0; i < sel.options.length; i++) {
                            if (sel.options[i].value == pid) { sel.selectedIndex = i; break; }
                        }
                    }
                    if (qtyIn) qtyIn.value = 1;
                }
                toggleDetailButtons();
            }

            // Lazy-load daftar produk saat modal dibuka
            // event.relatedTarget = elemen yang memicu modal (tombol keranjang)
            var orderModal = document.getElementById('orderManualModal');
            if (orderModal && selectProduct) {
                orderModal.addEventListener('show.bs.modal', function(event) {
                    // Ambil product id dari tombol pemicu (relatedTarget) jika ada
                    var triggerBtn = event.relatedTarget;
                    var pendingPid = (triggerBtn && triggerBtn.getAttribute('data-product-id')) || window.pendingAddToOrderProductId || null;
                    window.pendingAddToOrderProductId = null;

                    if (orderProductsLoaded) {
                        if (pendingPid) addProductToOrderList(pendingPid);
                        return;
                    }
                    var url = (window.orderWaConfig && window.orderWaConfig.productsUrl) || '';
                    if (!url) { selectProduct.innerHTML = '<option value="">-- Pilih produk --</option>'; return; }
                    url += (url.indexOf('?') >= 0 ? '&' : '?') + 'locale=' + encodeURIComponent((window.orderWaConfig && window.orderWaConfig.locale) || 'id');
                    selectProduct.innerHTML = '<option value="">-- Memuat... --</option>';
                    fetch(url).then(function(r) { return r.json(); }).then(function(arr) {
                        orderProductsLoaded = true;
                        selectProduct.innerHTML = '<option value="">-- Pilih produk --</option>';
                        (arr || []).forEach(function(p) {
                            var opt = document.createElement('option');
                            opt.value = p.id;
                            opt.setAttribute('data-name', p.name || '');
                            if (p.image) opt.setAttribute('data-image', p.image);
                            if (p.category) opt.setAttribute('data-category', p.category);
                            if (p.description) opt.setAttribute('data-description', p.description);
                            opt.textContent = p.name || '';
                            selectProduct.appendChild(opt);
                        });
                        // Pulihkan keranjang dari sesi sebelumnya (jika ada), lalu tambah produk baru
                        restoreCartFromStorage();
                        if (pendingPid) addProductToOrderList(pendingPid);
                    }).catch(function() {
                        selectProduct.innerHTML = '<option value="">-- Gagal memuat. Coba lagi.</option>';
                    });
                });
            }
            
            // Tampilkan tombol Detail, kontrol jumlah (+/-), dan Hapus hanya jika produk sudah dipilih
            // Sekaligus simpan state keranjang ke localStorage
            function toggleDetailButtons() {
                if (!orderItemsList) return;
                orderItemsList.querySelectorAll('.order-item-row').forEach(function(row) {
                    var sel = row.querySelector('.order-select');
                    var hasProduct = sel && sel.value;
                    var btn = row.querySelector('.order-btn-detail');
                    if (btn) btn.classList.toggle('d-none', !hasProduct);
                    row.querySelectorAll('.order-row-actions').forEach(function(el) {
                        if (hasProduct) el.classList.remove('d-none');
                        else el.classList.add('d-none');
                    });
                });
                saveCartToStorage();
            }
            
            // Simpan product id saat buka modal detail (untuk tombol Masukkan keranjang)
            window.orderDetailCurrentProductId = null;
            
            // Tombol Detail: tampilkan popup nama, kategori, deskripsi singkat (sesuai bahasa)
            function openDetailModal(row) {
                var sel = row && row.querySelector('.order-select');
                if (!sel || !sel.value) return;
                window.orderDetailCurrentProductId = sel.value;
                var opt = sel.options[sel.selectedIndex];
                var name = opt.getAttribute('data-name') || opt.text;
                var category = opt.getAttribute('data-category') || '';
                var desc = opt.getAttribute('data-description') || '';
                var imgSrc = opt.getAttribute('data-image') || '';
                var imgEl = document.getElementById('order_detail_img');
                var noimgEl = document.getElementById('order_detail_noimg');
                var nameEl = document.getElementById('order_detail_name');
                var categoryEl = document.getElementById('order_detail_category');
                var descEl = document.getElementById('order_detail_desc');
                if (nameEl) nameEl.textContent = name;
                var categoryValueEl = document.getElementById('order_detail_category_value');
                if (categoryValueEl) categoryValueEl.textContent = category || '';
                if (categoryEl) categoryEl.style.display = category ? '' : 'none';
                var noDescText = document.getElementById('order_detail_no_desc') ? document.getElementById('order_detail_no_desc').textContent : '';
                if (descEl) descEl.textContent = desc || noDescText;
                if (imgEl && noimgEl) {
                    if (imgSrc) { imgEl.src = imgSrc; imgEl.alt = name; imgEl.classList.remove('d-none'); noimgEl.classList.add('d-none'); }
                    else { imgEl.src = ''; imgEl.classList.add('d-none'); noimgEl.classList.remove('d-none'); }
                }
                var detailModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
                detailModal.show();
            }
            
            // Cek apakah produk sudah dipilih di baris lain (untuk cegah duplikat)
            function getSelectedProductIdsExcept(excludeRow) {
                var ids = [];
                if (!orderItemsList) return ids;
                orderItemsList.querySelectorAll('.order-item-row').forEach(function(row) {
                    if (row === excludeRow) return;
                    var sel = row.querySelector('.order-select');
                    if (sel && sel.value) ids.push(sel.value);
                });
                return ids;
            }
            
            // Tambah item baru: clone baris pertama, reset pilihan & jumlah
            if (document.getElementById('order_btn_tambah_item') && orderItemsList) {
                document.getElementById('order_btn_tambah_item').addEventListener('click', function() {
                    var rows = orderItemsList.querySelectorAll('.order-item-row');
                    if (rows.length >= maxOrderRows) { showSwal('Maksimal ' + maxOrderRows + ' barang.', 'info'); return; }
                    var first = rows[0];
                    var clone = first.cloneNode(true);
                    clone.removeAttribute('id');
                    var cloneSel = clone.querySelector('.order-select');
                    if (cloneSel) cloneSel.id = '';
                    cloneSel.selectedIndex = 0;
                    var qtyIn = clone.querySelector('.order-qty');
                    if (qtyIn) { qtyIn.value = 1; qtyIn.setAttribute('min', '1'); }
                    clone.querySelectorAll('.order-row-actions').forEach(function(el) { el.classList.add('d-none'); });
                    var detailBtn = clone.querySelector('.order-btn-detail');
                    if (detailBtn) detailBtn.classList.add('d-none');
                    orderItemsList.appendChild(clone);
                });
            }
            
            // Hapus semua item: kosongkan daftar, sisakan satu baris
            if (document.getElementById('order_btn_clear_all') && orderItemsList) {
                document.getElementById('order_btn_clear_all').addEventListener('click', function() {
                    var rows = orderItemsList.querySelectorAll('.order-item-row');
                    for (var i = rows.length - 1; i >= 1; i--) rows[i].remove();
                    var first = orderItemsList.querySelector('.order-item-row');
                    if (first) {
                        var sel = first.querySelector('.order-select');
                        if (sel) sel.selectedIndex = 0;
                        var qtyIn = first.querySelector('.order-qty');
                        if (qtyIn) qtyIn.value = 1;
                    }
                    toggleDetailButtons();
                });
            }
            
            // Delegasi: +/- jumlah dan Hapus baris. Semua baris bisa dihapus; jika hanya satu baris, hapus = kosongkan baris
            if (orderItemsList) {
                function clearRow(row) {
                    var sel = row.querySelector('.order-select');
                    var qtyIn = row.querySelector('.order-qty');
                    if (sel) sel.selectedIndex = 0;
                    if (qtyIn) qtyIn.value = 1;
                    toggleDetailButtons();
                }
                orderItemsList.addEventListener('change', function(e) {
                    if (e.target && e.target.classList && e.target.classList.contains('order-select')) {
                        var sel = e.target;
                        var row = sel.closest('.order-item-row');
                        if (sel.value) {
                            var used = getSelectedProductIdsExcept(row);
                            if (used.indexOf(sel.value) >= 0) {
                                showSwal((window.orderWaConfig && window.orderWaConfig.msg_duplicate_product) || 'Produk ini sudah ada di daftar.', 'warning');
                                sel.selectedIndex = 0;
                            }
                        }
                        toggleDetailButtons();
                    }
                    if (e.target && e.target.classList && e.target.classList.contains('order-qty')) {
                        var row = e.target.closest('.order-item-row');
                        if (!row) return;
                        var v = parseInt(e.target.value, 10) || 0;
                        var rows = orderItemsList.querySelectorAll('.order-item-row');
                        if (v < 1 && rows.length > 1) row.remove();
                    }
                });
                orderItemsList.addEventListener('click', function(e) {
                    var row = e.target.closest('.order-item-row');
                    if (!row) return;
                    var qtyIn = row.querySelector('.order-qty');
                    var rows = orderItemsList.querySelectorAll('.order-item-row');
                    if (e.target.closest('.order-qty-minus') && qtyIn) {
                        var v = parseInt(qtyIn.value, 10) || 0;
                        if (v <= 1) {
                            if (rows.length === 1) {
                                qtyIn.value = Math.max(0, v - 1);
                            } else {
                                row.remove();
                            }
                        } else {
                            qtyIn.value = v - 1;
                        }
                    }
                    if (e.target.closest('.order-qty-plus') && qtyIn) {
                        var v = parseInt(qtyIn.value, 10) || 0;
                        qtyIn.value = v + 1;
                    }
                    if (e.target.closest('.order-row-remove')) {
                        if (rows.length === 1) clearRow(row);
                        else row.remove();
                    }
                    if (e.target.closest('.order-btn-detail')) openDetailModal(row);
                });
            }
            
            // Kumpulkan item dari semua baris (untuk submit)
            function collectOrderItems() {
                var items = [];
                if (!orderItemsList) return items;
                orderItemsList.querySelectorAll('.order-item-row').forEach(function(row) {
                    var sel = row.querySelector('.order-select');
                    var qtyIn = row.querySelector('.order-qty');
                    if (!sel || !sel.value || !qtyIn) return;
                    var qty = parseInt(qtyIn.value, 10) || 0;
                    if (qty < 1) return;
                    var opt = sel.options[sel.selectedIndex];
                    var name = opt ? (opt.getAttribute('data-name') || opt.text) : '';
                    items.push({ id: sel.value, name: name, qty: qty });
                });
                return items;
            }
            
            function resetOrderRows() {
                if (!orderItemsList) return;
                var rows = orderItemsList.querySelectorAll('.order-item-row');
                for (var i = 1; i < rows.length; i++) rows[i].remove();
                var first = orderItemsList.querySelector('.order-item-row');
                if (first) {
                    var sel = first.querySelector('.order-select');
                    if (sel) sel.selectedIndex = 0;
                    var qtyIn = first.querySelector('.order-qty');
                    if (qtyIn) qtyIn.value = 1;
                }
                clearCartStorage();
                toggleDetailButtons();
            }
            
            var formOrder = document.getElementById('formOrderManual');
            if (formOrder) {
                formOrder.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var orderCart = collectOrderItems();
                    if (orderCart.length === 0) { showSwal('Pilih minimal satu barang dan isi jumlah.', 'warning'); return; }
                    var nama = document.getElementById('order_nama_pemesan') && document.getElementById('order_nama_pemesan').value;
                    var hp = document.getElementById('order_no_hp') && document.getElementById('order_no_hp').value;
                    if (!nama || !hp) { showSwal('Isi Nama Pemesan dan No. HP/WhatsApp.', 'warning'); return; }
                    var cfg = window.orderWaConfig || {};
                    var num = (cfg.number || '').replace(/\D/g, '');
                    if (!num) { showSwal('Nomor WhatsApp untuk order belum diatur. Silakan hubungi admin.', 'warning'); return; }
                    if (num.startsWith('0')) num = '62' + num.slice(1);
                    else if (!num.startsWith('62')) num = '62' + num;
                    var itemsText = orderCart.map(function(i) { return '• ' + i.name + ' × ' + i.qty; }).join('\n');
                    var companyName = (cfg.company_name || 'Toko').trim();
                    var tpl = (cfg.template || '').replace(/\{company_name\}/g, companyName).replace(/\{items\}/g, itemsText).replace(/\{nama_pemesan\}/g, nama).replace(/\{no_hp\}/g, hp).replace(/\{catatan\}/g, (document.getElementById('order_catatan') && document.getElementById('order_catatan').value) || '-');
                    var storeUrl = cfg.storeUrl;
                    var csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    var payload = { nama_pemesan: nama, no_hp: hp, catatan: (document.getElementById('order_catatan') && document.getElementById('order_catatan').value) || '', items: orderCart, _token: csrf };
                    var openWa = function() {
                        window.open('https://wa.me/' + num + '?text=' + encodeURIComponent(tpl), '_blank');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('orderManualModal'));
                        if (modal) modal.hide();
                        resetOrderRows();
                        formOrder.reset();
                    };
                    if (storeUrl && csrf) {
                        var btn = document.getElementById('btnKirimOrder');
                        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...'; }
                        fetch(storeUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(payload) })
                            .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
                            .then(function(res) {
                                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-whatsapp me-1"></i>Kirim ke WhatsApp'; }
                                openWa();
                            })
                            .catch(function() {
                                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-whatsapp me-1"></i>Kirim ke WhatsApp'; }
                                openWa();
                            });
                    } else {
                        openWa();
                    }
                });
            }
        });
    </script>
</body>
</html>
