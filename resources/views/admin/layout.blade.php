<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - YourStudio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
            background: linear-gradient(135deg, var(--light-brown) 0%, var(--dark-brown) 100%);
            border-right: 1px solid var(--light-brown);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            margin: 5px 0;
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
        }
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/images/logo1.png') }}" 
                             alt="YourStudio" 
                             class="img-fluid sidebar-logo">
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
                        
                        <!-- Kontak Section -->
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
                           href="{{ route('admin.settings.index') }}?section=contact">
                            <i class="bi bi-geo-alt me-2"></i> Peta & Lokasi
                        </a>
                        
                        <!-- Pengaturan Section -->
                        <div class="nav-section-header">
                            <small class="text-white-50 fw-bold">PENGATURAN</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" 
                           href="{{ route('admin.profile.edit') }}">
                            <i class="bi bi-person-gear me-2"></i> Profil Admin
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.settings.*') && !request()->get('section') ? 'active' : '' }}" 
                           href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear me-2"></i> Umum
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

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Remove event listener to prevent infinite loop
                        form.removeEventListener('submit', arguments.callee);
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
    
    @yield('scripts')
</body>
</html>
