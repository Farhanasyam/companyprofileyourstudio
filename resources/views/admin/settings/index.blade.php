@extends('admin.layout')

@section('title', $section === 'contact' ? 'Kontak & Lokasi' : ($section === 'order-wa' ? 'Order via WA' : 'Pengaturan Umum'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></li>
    @if($section === 'contact')
        <li class="breadcrumb-item active" aria-current="page">Kontak & Lokasi</li>
    @elseif($section === 'order-wa')
        <li class="breadcrumb-item active" aria-current="page">Order via WA</li>
    @else
        <li class="breadcrumb-item active" aria-current="page">Umum</li>
    @endif
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">
            @if($section === 'contact')
                <i class="bi bi-geo-alt me-2"></i>Kontak & Lokasi
            @elseif($section === 'order-wa')
                <i class="bi bi-whatsapp me-2"></i>Order via WhatsApp
            @else
                <i class="bi bi-gear me-2"></i>Pengaturan Umum
            @endif
        </h2>
        <p class="text-muted mb-0 small">
            @if($section === 'contact')
                Atur alamat, peta, dan informasi kontak yang tampil di halaman Kontak website.
            @elseif($section === 'order-wa')
                Atur nomor WhatsApp dan template pesan untuk pemesanan dari website.
            @else
                Informasi dasar perusahaan dan pengaturan umum website.
            @endif
        </p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($section)
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Pengaturan Umum
            </a>
        @else
            <div class="btn-group" role="group">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-primary">
                    <i class="bi bi-gear me-2"></i>Umum
                </a>
                <a href="{{ route('admin.settings.index', ['section' => 'contact']) }}" class="btn btn-outline-primary">
                    <i class="bi bi-geo-alt me-2"></i>Kontak & Lokasi
                </a>
                <a href="{{ route('admin.settings.index', ['section' => 'order-wa']) }}" class="btn btn-outline-primary">
                    <i class="bi bi-whatsapp me-2"></i>Order WA
                </a>
                <a href="{{ route('admin.settings.seo') }}" class="btn btn-outline-primary">
                    <i class="bi bi-search me-2"></i>SEO
                </a>
            </div>
        @endif
    </div>
</div>

<form action="{{ route('admin.settings.bulk-update') }}" method="POST">
    @csrf
    @method('POST')
    @if($section)
        <input type="hidden" name="section" value="{{ $section }}">
    @endif
    
    @foreach($settings as $group => $groupSettings)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    @switch($group)
                        @case('company')
                            <i class="bi bi-building me-2"></i>Informasi Perusahaan (Alamat, Telepon, Email)
                            @break
                        @case('maps')
                            <i class="bi bi-geo-alt me-2"></i>Peta & Lokasi
                            @break
                        @case('order_wa')
                            <i class="bi bi-whatsapp me-2"></i>Order Manual via WhatsApp
                            @break
                        @case('social')
                            <i class="bi bi-share me-2"></i>Media Sosial
                            @break
                        @case('seo')
                            <i class="bi bi-search me-2"></i>SEO
                            @break
                        @case('general')
                            <i class="bi bi-gear me-2"></i>Umum
                            @break
                        @default
                            <i class="bi bi-sliders me-2"></i>{{ ucfirst($group) }}
                    @endswitch
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($groupSettings as $setting)
                        <div class="col-md-6 mb-3">
                            <label for="{{ $setting->key }}" class="form-label">
                                {{ $setting->description ?: ucfirst(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            
                            @switch($setting->type)
                                @case('textarea')
                                    <textarea class="form-control" 
                                              id="{{ $setting->key }}" 
                                              name="{{ $setting->key }}" 
                                              rows="{{ $setting->key === 'maps_iframe' ? 5 : 3 }}">{{ $setting->value }}</textarea>
                                    @break
                                    
                                @case('boolean')
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="{{ $setting->key }}" 
                                               name="{{ $setting->key }}" 
                                               value="1"
                                               {{ $setting->value ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $setting->key }}">
                                            Aktif
                                        </label>
                                    </div>
                                    @break
                                    
                                @case('image')
                                    <div class="d-flex gap-2">
                                        <input type="file" 
                                               class="form-control" 
                                               id="{{ $setting->key }}" 
                                               name="{{ $setting->key }}" 
                                               accept="image/*">
                                        @if($setting->value)
                                            <a href="{{ asset('storage/' . $setting->value) }}" 
                                               target="_blank" 
                                               class="btn btn-outline-secondary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                    @if($setting->value)
                                        <small class="text-muted">Current: {{ $setting->value }}</small>
                                    @endif
                                    @break
                                    
                                @default
                                    <input type="text" 
                                           class="form-control" 
                                           id="{{ $setting->key }}" 
                                           name="{{ $setting->key }}" 
                                           value="{{ $setting->value }}">
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Simpan Semua Pengaturan
        </button>
    </div>
</form>

@if($section === 'order-wa')
<div class="alert alert-info mt-3">
    <strong><i class="bi bi-info-circle me-2"></i>Template pesan</strong> — Gunakan placeholder: <code>{company_name}</code> (nama toko), <code>{items}</code> (daftar barang), <code>{nama_pemesan}</code>, <code>{no_hp}</code>, <code>{catatan}</code>. Nomor WA isi format 62xxx (contoh: 6281234567890). Template bisa pakai emoji agar pesan lebih menarik.
</div>
@endif

@if(!$section)
<!-- Advanced: Individual Settings (untuk pengembang) -->
<div class="card mt-4">
    <div class="card-header d-flex align-items-center">
        <button class="btn btn-link text-decoration-none p-0 me-2 text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSettings" aria-expanded="false">
            <i class="bi bi-chevron-down"></i>
        </button>
        <h5 class="mb-0 text-muted"><i class="bi bi-code-slash me-2"></i>Kelola pengaturan individual (untuk pengembang)</h5>
    </div>
    <div class="collapse" id="advancedSettings">
        <div class="card-body">
            <p class="text-muted small">Bagian ini menampilkan key/value pengaturan. Gunakan hanya jika Anda paham. Untuk mengubah tampilan website, gunakan tab Umum, Kontak & Lokasi, Order WA, atau SEO di atas.</p>
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Deskripsi</th>
                            <th>Group</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings->flatten() as $setting)
                            <tr>
                                <td><code>{{ $setting->key }}</code></td>
                                <td>{{ $setting->description ?: '-' }}</td>
                                <td><span class="badge bg-secondary">{{ $setting->group }}</span></td>
                                <td><span class="badge bg-info">{{ $setting->type }}</span></td>
                                <td><div class="text-truncate" style="max-width: 180px;" title="{{ $setting->value }}">{{ $setting->value ?: '-' }}</div></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.settings.show', $setting) }}" class="btn btn-outline-secondary" title="Lihat"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.settings.edit', $setting) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.settings.destroy', $setting) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengaturan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.settings.create') }}" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-plus-circle me-1"></i>Tambah pengaturan</a>
        </div>
    </div>
</div>
@endif

<!-- Panduan Pengaturan Peta & Lokasi -->
@if($section === 'contact')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan Pengaturan Peta & Lokasi
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="mapGuideAccordion">
                    <!-- Cara Mengatur Alamat -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#setAddress">
                                <i class="bi bi-geo-alt me-2"></i>Cara Mengatur Alamat Lengkap
                            </button>
                        </h2>
                        <div id="setAddress" class="accordion-collapse collapse" data-bs-parent="#mapGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Isi field <strong>"Maps Address"</strong> dengan alamat lengkap perusahaan</li>
                                    <li>Format: <code>Nama Tempat, Jalan, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos</code></li>
                                    <li>Contoh: <code>Ruko Wow, Jl. Raya Sawojajar Blok Paris PA-1 No.12, Sawojajar, Kedungkandang, Malang City, East Java 65139</code></li>
                                    <li>Alamat ini akan ditampilkan di halaman kontak website</li>
                                    <li>Klik <strong>"Simpan Semua Pengaturan"</strong> untuk menyimpan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Mengatur Google Maps -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#setGoogleMaps">
                                <i class="bi bi-map me-2"></i>Cara Mengatur Google Maps
                            </button>
                        </h2>
                        <div id="setGoogleMaps" class="accordion-collapse collapse" data-bs-parent="#mapGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka <a href="https://maps.google.com" target="_blank">Google Maps</a></li>
                                    <li>Cari alamat perusahaan Anda</li>
                                    <li>Klik <strong>"Share"</strong> → <strong>"Embed a map"</strong></li>
                                    <li>Pilih ukuran peta (Medium atau Large)</li>
                                    <li>Copy kode iframe yang diberikan</li>
                                    <li>Paste kode iframe ke field <strong>"Maps Iframe"</strong></li>
                                    <li>Klik <strong>"Simpan Semua Pengaturan"</strong></li>
                                </ol>
                                <div class="alert alert-info mt-3">
                                    <strong>💡 Tips:</strong> Gunakan ukuran Medium (600x450) untuk tampilan yang optimal di website.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Mengatur Informasi Kontak -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#setContactInfo">
                                <i class="bi bi-telephone me-2"></i>Cara Mengatur Informasi Kontak
                            </button>
                        </h2>
                        <div id="setContactInfo" class="accordion-collapse collapse" data-bs-parent="#mapGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li><strong>Company Phone:</strong> Nomor telepon utama perusahaan</li>
                                    <li><strong>Company Email:</strong> Email utama perusahaan</li>
                                    <li><strong>WhatsApp Event Registration:</strong> Nomor WhatsApp untuk pendaftaran event</li>
                                    <li><strong>Company Operating Hours:</strong> Jam operasional perusahaan</li>
                                    <li>Format jam operasional: <code>Senin - Jumat: 08:00 - 17:00, Sabtu: 08:00 - 15:00</code></li>
                                    <li>Semua informasi ini akan ditampilkan di halaman kontak</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Mengatur Media Sosial -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#setSocialMedia">
                                <i class="bi bi-share me-2"></i>Cara Mengatur Media Sosial
                            </button>
                        </h2>
                        <div id="setSocialMedia" class="accordion-collapse collapse" data-bs-parent="#mapGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li><strong>Instagram URL:</strong> Link profil Instagram perusahaan</li>
                                    <li><strong>Shopee URL:</strong> Link toko Shopee perusahaan</li>
                                    <li><strong>TikTok URL:</strong> Link profil TikTok perusahaan</li>
                                    <li>Format URL: <code>https://www.instagram.com/username</code></li>
                                    <li>Link ini akan muncul sebagai tombol di halaman kontak</li>
                                    <li>Kosongkan field jika tidak memiliki akun media sosial tersebut</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Preview Hasil -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#previewResult">
                                <i class="bi bi-eye me-2"></i>Cara Preview Hasil
                            </button>
                        </h2>
                        <div id="previewResult" class="accordion-collapse collapse" data-bs-parent="#mapGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Setelah menyimpan pengaturan, buka halaman <strong>Kontak</strong> di website</li>
                                    <li>Periksa apakah semua informasi ditampilkan dengan benar</li>
                                    <li>Pastikan peta Google Maps berfungsi dan menampilkan lokasi yang tepat</li>
                                    <li>Test tombol media sosial apakah mengarah ke akun yang benar</li>
                                    <li>Pastikan informasi kontak (telepon, email) dapat diklik</li>
                                </ol>
                                <div class="alert alert-warning mt-3">
                                    <strong>⚠️ Catatan:</strong> Perubahan mungkin memerlukan beberapa menit untuk muncul di website karena caching.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Trik -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mapTips">
                                <i class="bi bi-lightbulb me-2"></i>Tips & Trik
                            </button>
                        </h2>
                        <div id="mapTips" class="accordion-collapse collapse" data-bs-parent="#mapGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Alamat Lengkap:</strong> Gunakan alamat yang mudah ditemukan di Google Maps</li>
                                    <li><strong>Google Maps:</strong> Pastikan bisnis sudah terdaftar di Google My Business</li>
                                    <li><strong>Responsive:</strong> Peta akan otomatis menyesuaikan dengan ukuran layar</li>
                                    <li><strong>Loading Speed:</strong> Peta dimuat secara asinkron untuk performa optimal</li>
                                    <li><strong>Backup:</strong> Simpan kode iframe sebagai backup</li>
                                    <li><strong>Update:</strong> Update informasi kontak secara berkala</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
