<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Your Studio</title>
    @php
        $adminFavicon = \App\Models\Setting::get('favicon');
        $adminFaviconUrl = $adminFavicon ? '/storage/' . ltrim($adminFavicon, '/') : '/favicon.ico';
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $adminFaviconUrl }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $adminFaviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $adminFaviconUrl }}">
    <link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="/vendor/sweetalert2/sweetalert2.all.min.js"></script>
    
    <!-- TinyMCE Configuration -->
    <x-head.tinymce-admin-config />
    <style>
        :root {
            --yellow: #fde781;
            --light-yellow: #fef5ce;
            --dark-brown: #553914;
            --orange: #ef9e46;
            --light-pink: #fec9d3;
            --light-brown: #d39f69;
            --white: #ffffff;
            --light-grey: #f8f8f8;
            --dark-grey: #313131;
        }
        
        .sidebar {
            min-height: 100vh;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background: linear-gradient(135deg, var(--light-brown) 0%, var(--dark-brown) 100%);
            border-right: 1px solid var(--light-brown);
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.35) transparent;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            margin: 3px 0;
            padding: .65rem .8rem;
            transition: all 0.3s;
            font-weight: 500;
        }
        .sidebar .nav-link:hover {
            color: var(--white);
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            color: var(--white);
            background: var(--yellow);
            color: var(--dark-brown) !important;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(253, 231, 129, 0.3);
        }
        .main-content {
            background: var(--light-grey);
            min-height: 100vh;
            padding: 1.75rem !important;
        }
        .main-content > .d-flex:first-child {
            align-items: flex-start !important;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(85, 57, 20, .1);
        }
        .main-content h2 {
            color: var(--dark-brown);
            font-size: 1.45rem;
            font-weight: 700;
            letter-spacing: 0;
        }
        .main-content > .card,
        .main-content > .row > [class*="col"] > .card {
            overflow: hidden;
        }
        .main-content .table {
            margin-bottom: 0;
            --bs-table-hover-bg: rgba(253, 231, 129, .12);
        }
        .main-content .table thead th {
            background: #fffaf0;
            color: var(--dark-brown);
            border-bottom: 2px solid rgba(211, 159, 105, .35);
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .035em;
            text-transform: uppercase;
            white-space: nowrap;
            padding: .85rem .75rem;
        }
        .main-content .table tbody td {
            color: #4c4030;
            padding: .8rem .75rem;
            vertical-align: middle;
        }
        .main-content .table tbody tr:last-child td { border-bottom: 0; }
        .main-content .table .btn-group { white-space: nowrap; }
        .main-content .table .btn-group .btn {
            min-width: 34px;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .main-content .card-header {
            background: #fffdf8;
            border-bottom: 1px solid rgba(211, 159, 105, .22);
            padding: .9rem 1.1rem;
        }
        .main-content .card-header h5 { color: var(--dark-brown); font-weight: 700; }
        .main-content .pagination { margin: 1.25rem 0 0; }
        .main-content .pagination .page-link {
            color: var(--dark-brown);
            border-color: rgba(211, 159, 105, .3);
        }
        .main-content .pagination .active .page-link {
            background: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--white);
        }
        .main-content .form-label { color: var(--dark-brown); font-weight: 600; }
        .main-content .form-control,
        .main-content .form-select {
            border-color: #dfd5c5;
            border-radius: 8px;
        }
        .main-content .form-control:focus,
        .main-content .form-select:focus {
            border-color: var(--light-brown);
            box-shadow: 0 0 0 .2rem rgba(211, 159, 105, .2);
        }
        .main-content > .row.mt-4 .card { border-color: rgba(211, 159, 105, .2); box-shadow: none; }
        .main-content > .row.mt-4 .card-header { background: #f7f1e7; }
        .card {
            border: 1px solid var(--light-brown);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            background: var(--white);
        }
        .stats-card {
            background: linear-gradient(135deg, var(--yellow) 0%, var(--light-brown) 100%);
            color: var(--dark-brown);
            border: 1px solid var(--light-brown);
        }
        .stats-card h3 {
            color: var(--dark-brown);
            font-weight: 700;
        }
        .stats-card p {
            color: var(--dark-brown);
            font-weight: 500;
        }
        .sidebar-logo {
            filter: none;
            transition: all 0.3s ease;
            max-height: 80px !important;
            max-width: 160px !important;
        }
        .sidebar-brand-fallback {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--white);
            font-size: 1.15rem;
            font-weight: 700;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-brand-fallback__mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: .6rem;
            background: var(--yellow);
            color: var(--dark-brown);
        }
        .sidebar-logo:hover {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
            transform: scale(1.05);
        }
        .btn-primary {
            background: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--white);
        }
        .btn-primary:hover {
            background: var(--dark-brown);
            border-color: var(--dark-brown);
        }
        .btn-outline-primary {
            border-color: var(--light-brown);
            color: var(--light-brown);
        }
        .btn-outline-primary:hover {
            background: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--white);
        }
        .btn-outline-light {
            border-color: var(--white);
            color: var(--white);
        }
        .btn-outline-light:hover {
            background: var(--white);
            color: var(--dark-brown);
        }
        .alert-success {
            background: var(--light-yellow);
            border-color: var(--light-brown);
            color: var(--dark-brown);
        }
        .alert-danger {
            background: var(--light-pink);
            border-color: var(--orange);
            color: var(--dark-brown);
        }
        .btn-success {
            background: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--white);
        }
        .btn-success:hover {
            background: var(--dark-brown);
            border-color: var(--dark-brown);
        }
        .btn-info {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
        }
        .btn-info:hover {
            background: var(--dark-brown);
            border-color: var(--dark-brown);
        }
        .btn-warning {
            background: var(--yellow);
            border-color: var(--yellow);
            color: var(--dark-brown);
        }
        .btn-warning:hover {
            background: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--white);
        }
        .badge.bg-success {
            background: var(--light-brown) !important;
            color: var(--white) !important;
        }
        .badge.bg-warning {
            background: var(--yellow) !important;
            color: var(--dark-brown) !important;
        }
        .nav-section-header {
            margin: 1.5rem 0 0.5rem 0;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .nav-section-header:first-child {
            margin-top: 1rem;
        }
        .admin-page-header {
            background: var(--white);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(211, 159, 105, 0.3);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .admin-breadcrumb {
            font-size: 0.875rem;
            color: var(--dark-brown);
        }
        .admin-breadcrumb a { color: var(--light-brown); text-decoration: none; }
        .admin-breadcrumb a:hover { text-decoration: underline; }
        .admin-breadcrumb .separator { color: #999; margin: 0 0.35rem; }
        .sidebar .nav-link .bi { opacity: 0.95; }

        .admin-feature-guide {
            border: 1px solid rgba(211, 159, 105, .35);
            border-left: 4px solid var(--light-brown);
            border-radius: 12px;
            background: #fffdf8;
            box-shadow: 0 2px 8px rgba(85, 57, 20, .05);
            overflow: hidden;
        }
        .admin-feature-guide__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.15rem;
        }
        .admin-feature-guide__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 2.35rem;
            width: 2.35rem;
            height: 2.35rem;
            border-radius: .7rem;
            background: var(--light-yellow);
            color: var(--dark-brown);
            font-size: 1.2rem;
        }
        .admin-feature-guide h5 { color: var(--dark-brown); font-weight: 700; }
        .admin-feature-guide p { color: #6b5b47; font-size: .9rem; }
        .admin-feature-guide__body {
            padding: 0 1.15rem 1.15rem 4.1rem;
        }
        .admin-feature-guide__steps {
            padding-left: 1.25rem;
            color: #514638;
            font-size: .88rem;
        }
        .admin-feature-guide__steps li { padding: .2rem 0; }
        .admin-feature-guide__tip {
            height: 100%;
            padding: .75rem .9rem;
            border-radius: 8px;
            background: #fff5cf;
            color: #624c28;
            font-size: .84rem;
        }

        /* ── Image / Video Preview Global ── */
        .admin-file-preview { margin-top: 10px; }
        .admin-file-preview__grid { display: flex; flex-wrap: wrap; gap: 10px; }
        .admin-file-preview__item {
            position: relative;
            border: 1.5px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
            width: 130px;
            flex-shrink: 0;
        }
        .admin-file-preview__img {
            display: block;
            width: 130px;
            height: 100px;
            object-fit: contain;
            background: #fff;
            padding: 4px;
        }
        .admin-file-preview__video {
            display: block;
            width: 130px;
            height: 100px;
            object-fit: cover;
        }
        .admin-file-preview__info {
            padding: 5px 7px;
            font-size: 0.72rem;
            color: #555;
            border-top: 1px solid #eee;
            background: #f8f9fa;
            word-break: break-all;
            line-height: 1.3;
        }
        .admin-file-preview__info strong { display: block; color: #333; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .admin-file-preview__badge {
            position: absolute;
            top: 5px; left: 5px;
            background: rgba(0,0,0,0.55);
            color: #fff;
            font-size: 0.65rem;
            padding: 1px 5px;
            border-radius: 4px;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                position: relative;
                height: auto;
                min-height: auto;
                max-height: none;
            }
            .main-content { padding: 1rem !important; }
            .main-content > .d-flex:first-child { gap: .75rem; }
            .main-content h2 { font-size: 1.25rem; }
            .main-content .card-body { padding: .85rem; }
            .main-content .table { min-width: 720px; }
            .main-content .table-responsive { margin: 0 -.85rem; padding: 0 .85rem; }
            .admin-feature-guide__header { flex-direction: column; }
            .admin-feature-guide__toggle { align-self: flex-start; }
            .admin-feature-guide__body { padding: 0 1rem 1rem 1.15rem; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        @php
                            $adminCompanyName = \App\Models\Setting::get('company_name', 'YourStudio');
                            $adminLogoPath = \App\Models\Setting::get('logo');
                            $adminLogoFile = $adminLogoPath ? storage_path('app/public/' . ltrim($adminLogoPath, '/')) : null;
                        @endphp
                        @if($adminLogoFile && is_file($adminLogoFile))
                               <img src="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($adminLogoPath)) }}"
                                   alt="{{ $adminCompanyName }}"
                                 class="img-fluid sidebar-logo">
                        @else
                            <span class="sidebar-brand-fallback"><span class="sidebar-brand-fallback__mark">Y</span>{{ $adminCompanyName }}</span>
                        @endif
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        
                        <!-- Beranda Section -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">BERANDA</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}" 
                           href="{{ route('admin.galleries.index') }}">
                            <i class="bi bi-images me-2"></i> Galeri
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.about-us.*') ? 'active' : '' }}" 
                           href="{{ route('admin.about-us.index') }}">
                            <i class="bi bi-info-circle me-2"></i> Tentang Kami
                        </a>
                        
                        <!-- Produk Section -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">PRODUK</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" 
                           href="{{ route('admin.products.index') }}">
                            <i class="bi bi-box me-2"></i> Produk
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                           href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-tags me-2"></i> Kategori
                        </a>
                        
                        <!-- Artikel Section -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">ARTIKEL</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" 
                           href="{{ route('admin.articles.index') }}">
                            <i class="bi bi-newspaper me-2"></i> Artikel
                        </a>
                        
                        <!-- Event Section -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">EVENT</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}" 
                           href="{{ route('admin.events.index') }}">
                            <i class="bi bi-calendar-event me-2"></i> Event
                        </a>
                        
                        <!-- Kontak (sesuai halaman Kontak: pesan + peta/lokasi) -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">KONTAK</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" 
                           href="{{ route('admin.contacts.index') }}">
                            <i class="bi bi-envelope me-2"></i> Pesan Kontak
                            @if(isset($stats['unread_contacts']) && $stats['unread_contacts'] > 0)
                                <span class="badge bg-danger ms-2">{{ $stats['unread_contacts'] }}</span>
                            @endif
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') && request()->get('section') == 'contact' ? 'active' : '' }}" 
                           href="{{ route('admin.settings.index', ['section' => 'contact']) }}">
                            <i class="bi bi-geo-alt me-2"></i> Kontak & Lokasi
                        </a>
                        
                        <!-- Pemesanan (sesuai fitur order di website) -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">PEMESANAN</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" 
                           href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-cart-check me-2"></i> Riwayat Order
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') && request()->get('section') == 'order-wa' ? 'active' : '' }}" 
                           href="{{ route('admin.settings.index', ['section' => 'order-wa']) }}">
                            <i class="bi bi-whatsapp me-2"></i> Order via WA
                        </a>
                        
                        <!-- Pengaturan (umum, SEO) -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">PENGATURAN</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" 
                           href="{{ route('admin.profile.edit') }}">
                            <i class="bi bi-person-gear me-2"></i> Profil Admin
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') && !request()->get('section') ? 'active' : '' }}" 
                           href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear me-2"></i> Pengaturan Umum
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.settings.seo') ? 'active' : '' }}" 
                           href="{{ route('admin.settings.seo') }}">
                            <i class="bi bi-search me-2"></i> SEO
                        </a>
                    </nav>

                    <div class="mt-auto pt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @hasSection('breadcrumb')
                    <div class="admin-page-header">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb admin-breadcrumb mb-0">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    </div>
                    @endif

                    @include('admin.partials.feature-guide')

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 Configuration -->
    <script>
        // SweetAlert2 Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Success Toast
        function showSuccessToast(message) {
            Toast.fire({
                icon: 'success',
                title: message
            });
        }

        // Error Toast
        function showErrorToast(message) {
            Toast.fire({
                icon: 'error',
                title: message
            });
        }

        // Warning Toast
        function showWarningToast(message) {
            Toast.fire({
                icon: 'warning',
                title: message
            });
        }

        // Info Toast
        function showInfoToast(message) {
            Toast.fire({
                icon: 'info',
                title: message
            });
        }

        // Delete Confirmation
        function confirmDelete(url, title = 'Data', text = 'Apakah Anda yakin ingin menghapus data ini?') {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                    document.querySelector('input[name="_token"]')?.value;
                    form.appendChild(csrfToken);
                    
                    // Add method override for DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Form Submit Confirmation
        function confirmSubmit(formId, title = 'Konfirmasi', text = 'Apakah Anda yakin ingin menyimpan data ini?') {
            const form = document.getElementById(formId);
            if (!form) return false;

            let _confirmed = false;

            form.addEventListener('submit', function(e) {
                if (_confirmed) return; // sudah dikonfirmasi, biarkan submit berjalan
                e.preventDefault();

                // Simpan konten TinyMCE ke textarea terlebih dahulu
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }

                // Validasi field TinyMCE yang data-required="true"
                let emptyRequired = false;
                form.querySelectorAll('textarea[data-required="true"]').forEach(function(ta) {
                    if (!ta.value.trim()) {
                        emptyRequired = true;
                    }
                });
                if (emptyRequired) {
                    showErrorToast('Kolom Konten wajib diisi sebelum menyimpan.');
                    return;
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#553914',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        _confirmed = true;
                        form.submit();
                    }
                });
            });
        }

        // Form Validation with SweetAlert
        function validateForm(formId) {
            const form = document.getElementById(formId);
            if (!form) return false;

            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;
            let errorMessages = [];

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    hasErrors = true;
                    const label = form.querySelector(`label[for="${field.id}"]`)?.textContent || field.name;
                    errorMessages.push(`${label} wajib diisi`);
                    
                    // Add visual feedback
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (hasErrors) {
                Swal.fire({
                    title: 'Data Belum Lengkap',
                    html: '<ul style="text-align: left;">' + 
                          errorMessages.map(msg => `<li>${msg}</li>`).join('') + 
                          '</ul>',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            return true;
        }

        // Show success message from session
        @if(session('success'))
            showSuccessToast('{{ session('success') }}');
        @endif

        // Show error message from session
        @if(session('error'))
            showErrorToast('{{ session('error') }}');
        @endif

        // Show validation errors
        @if($errors->any())
            showErrorToast('Terdapat kesalahan dalam form. Silakan periksa kembali.');
        @endif
    </script>

    <!-- ── Global File Preview untuk semua input[type=file] di admin ── -->
    <script>
    (function () {
        function formatSize(bytes) {
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
            if (bytes >= 1024) return Math.round(bytes / 1024) + ' KB';
            return bytes + ' B';
        }

        function escHtml(s) {
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function getOrCreatePreviewWrap(input) {
            // Cari preview container yang sudah ada (sibling langsung)
            var next = input.parentElement.querySelector('.admin-file-preview');
            if (!next) {
                next = document.createElement('div');
                next.className = 'admin-file-preview';
                input.parentElement.appendChild(next);
            }
            return next;
        }

        function buildItemHtml(file, dataUrl) {
            var isVideo = file.type.startsWith('video/');
            var mediaTag = isVideo
                ? '<video class="admin-file-preview__video" src="' + escHtml(dataUrl) + '" controls muted></video>'
                : '<img class="admin-file-preview__img" src="' + escHtml(dataUrl) + '" alt="' + escHtml(file.name) + '">';
            var badge = isVideo ? '<span class="admin-file-preview__badge"><i class="bi bi-camera-video-fill"></i> Video</span>' : '';
            return '<div class="admin-file-preview__item">' +
                badge + mediaTag +
                '<div class="admin-file-preview__info">' +
                '<strong title="' + escHtml(file.name) + '">' + escHtml(file.name) + '</strong>' +
                formatSize(file.size) +
                '</div></div>';
        }

        function handleFileInput(input) {
            var files = input.files;
            var wrap = getOrCreatePreviewWrap(input);

            if (!files || files.length === 0) {
                wrap.innerHTML = '';
                return;
            }

            // Hanya tampilkan preview untuk gambar dan video
            var mediaFiles = Array.from(files).filter(function(f) {
                return f.type.startsWith('image/') || f.type.startsWith('video/');
            });

            if (mediaFiles.length === 0) { wrap.innerHTML = ''; return; }

            var grid = document.createElement('div');
            grid.className = 'admin-file-preview__grid';
            wrap.innerHTML = '';
            wrap.appendChild(grid);

            var loaded = 0;
            mediaFiles.forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    grid.insertAdjacentHTML('beforeend', buildItemHtml(file, ev.target.result));
                };
                reader.readAsDataURL(file);
            });
        }

        // Event delegation — tangkap semua file input di halaman mana pun
        document.addEventListener('change', function(e) {
            var input = e.target;
            if (input.tagName !== 'INPUT' || input.type !== 'file') return;
            handleFileInput(input);
        });

        // Inisialisasi saat halaman dimuat untuk input yang sudah punya value (misal saat validasi gagal)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[type="file"]').forEach(function(input) {
                if (input.files && input.files.length > 0) handleFileInput(input);
            });
        });
    })();
    </script>

    @yield('scripts')
</body>
</html>
