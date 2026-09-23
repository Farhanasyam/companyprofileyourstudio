@extends('admin.layout')

@section('title', 'Pengaturan SEO')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pengaturan SEO</h2>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Pengaturan
    </a>
</div>

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

<form id="seoSettingsForm" action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Basic SEO Settings -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-search me-2"></i>SEO Dasar</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                               value="{{ \App\Models\Setting::get('meta_title') }}" 
                               maxlength="60" required>
                        <div class="form-text">
                            <span id="meta_title_count">0</span>/60 karakter
                            <span id="meta_title_warning" class="text-warning ms-2" style="display: none;">
                                <i class="bi bi-exclamation-triangle"></i> Terlalu panjang untuk SEO optimal
                            </span>
                        </div>
                        @error('meta_title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="meta_description" name="meta_description" 
                                  rows="3" maxlength="160" required>{{ \App\Models\Setting::get('meta_description') }}</textarea>
                        <div class="form-text">
                            <span id="meta_description_count">0</span>/160 karakter
                            <span id="meta_description_warning" class="text-warning ms-2" style="display: none;">
                                <i class="bi bi-exclamation-triangle"></i> Terlalu panjang untuk SEO optimal
                            </span>
                        </div>
                        @error('meta_description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               value="{{ \App\Models\Setting::get('meta_keywords') }}">
                        <div class="form-text">Pisahkan dengan koma (contoh: alat lukis, clay, seni)</div>
                        @error('meta_keywords')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="canonical_url" class="form-label">Canonical URL</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url" 
                               value="{{ \App\Models\Setting::get('canonical_url', config('app.url')) }}">
                        <div class="form-text">URL utama website untuk SEO</div>
                        @error('canonical_url')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Open Graph Settings -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-share me-2"></i>Open Graph (Facebook)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="og_title" class="form-label">OG Title</label>
                        <input type="text" class="form-control" id="og_title" name="og_title" 
                               value="{{ \App\Models\Setting::get('og_title') }}" maxlength="60">
                        <div class="form-text">Judul untuk berbagi di Facebook</div>
                        @error('og_title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="og_description" class="form-label">OG Description</label>
                        <textarea class="form-control" id="og_description" name="og_description" 
                                  rows="3" maxlength="160">{{ \App\Models\Setting::get('og_description') }}</textarea>
                        <div class="form-text">Deskripsi untuk berbagi di Facebook</div>
                        @error('og_description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="og_image" class="form-label">OG Image</label>
                        @if(\App\Models\Setting::get('og_image'))
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . \App\Models\Setting::get('og_image')) }}" 
                                     alt="Current OG Image" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="og_image" name="og_image" 
                               accept="image/*">
                        <div class="form-text">Gambar untuk berbagi di Facebook (1200x630px optimal)</div>
                        @error('og_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Twitter Card Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-twitter me-2"></i>Twitter Card</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="twitter_title" class="form-label">Twitter Title</label>
                        <input type="text" class="form-control" id="twitter_title" name="twitter_title" 
                               value="{{ \App\Models\Setting::get('twitter_title') }}" maxlength="60">
                        <div class="form-text">Judul untuk berbagi di Twitter</div>
                        @error('twitter_title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="twitter_description" class="form-label">Twitter Description</label>
                        <textarea class="form-control" id="twitter_description" name="twitter_description" 
                                  rows="3" maxlength="160">{{ \App\Models\Setting::get('twitter_description') }}</textarea>
                        <div class="form-text">Deskripsi untuk berbagi di Twitter</div>
                        @error('twitter_description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="twitter_image" class="form-label">Twitter Image</label>
                        @if(\App\Models\Setting::get('twitter_image'))
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . \App\Models\Setting::get('twitter_image')) }}" 
                                     alt="Current Twitter Image" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="twitter_image" name="twitter_image" 
                               accept="image/*">
                        <div class="form-text">Gambar untuk berbagi di Twitter (1200x600px optimal)</div>
                        @error('twitter_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-whatsapp me-2"></i>Pengaturan WhatsApp</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="whatsapp_event_registration" class="form-label">Nomor WhatsApp untuk Pendaftaran Event <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="whatsapp_event_registration" name="whatsapp_event_registration" 
                       value="{{ \App\Models\Setting::get('whatsapp_event_registration') }}" 
                       placeholder="+62 812-3456-7890" required>
                <div class="form-text">
                    <strong>Format:</strong> Gunakan format internasional dengan kode negara (contoh: +62 812-3456-7890)<br>
                    <strong>Fungsi:</strong> Nomor ini akan digunakan untuk link pendaftaran event di halaman public
                </div>
                @error('whatsapp_event_registration')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Maps Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Pengaturan Maps</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="maps_iframe" class="form-label">Google Maps Iframe <span class="text-danger">*</span></label>
                <textarea class="form-control" id="maps_iframe" name="maps_iframe" 
                          rows="4" placeholder="Paste Google Maps iframe embed code here...">{{ \App\Models\Setting::get('maps_iframe') }}</textarea>
                <div class="form-text">
                    <strong>Cara mendapatkan iframe:</strong><br>
                    1. Buka Google Maps<br>
                    2. Cari lokasi bisnis Anda<br>
                    3. Klik "Share" → "Embed a map"<br>
                    4. Copy kode iframe dan paste di sini
                </div>
                @error('maps_iframe')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="maps_address" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" id="maps_address" name="maps_address" 
                          rows="3" placeholder="Masukkan alamat lengkap bisnis...">{{ \App\Models\Setting::get('maps_address') }}</textarea>
                <div class="form-text">Alamat yang akan ditampilkan di halaman kontak</div>
                @error('maps_address')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Maps Preview -->
            <div class="mb-3">
                <label class="form-label">Preview Maps</label>
                <div id="maps-preview" class="border rounded p-3" style="min-height: 300px; background-color: #f8f9fa;">
                    @php
                        $mapsIframe = trim(\App\Models\Setting::get('maps_iframe', '') ?? '');
                        $mapsPreviewSrc = null;
                        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $mapsIframe, $matches)) {
                            $candidate = filter_var($matches[1], FILTER_VALIDATE_URL);
                            $host = $candidate ? strtolower((string) parse_url($candidate, PHP_URL_HOST)) : '';
                            if ($candidate && parse_url($candidate, PHP_URL_SCHEME) === 'https'
                                && in_array($host, ['google.com', 'www.google.com', 'maps.google.com'], true)) {
                                $mapsPreviewSrc = $candidate;
                            }
                        }
                    @endphp
                    @if($mapsPreviewSrc)
                        <div id="maps-iframe-container">
                            <iframe src="{{ $mapsPreviewSrc }}" width="100%" height="280" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-geo-alt fs-1"></i>
                            <p class="mt-3">Preview maps akan muncul setelah Anda memasukkan iframe code</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional SEO Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Pengaturan Tambahan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="robots" class="form-label">Robots Meta Tag</label>
                        <select class="form-select" id="robots" name="robots">
                            <option value="index, follow" {{ \App\Models\Setting::get('robots') == 'index, follow' ? 'selected' : '' }}>
                                Index, Follow (Recommended)
                            </option>
                            <option value="index, nofollow" {{ \App\Models\Setting::get('robots') == 'index, nofollow' ? 'selected' : '' }}>
                                Index, No Follow
                            </option>
                            <option value="noindex, follow" {{ \App\Models\Setting::get('robots') == 'noindex, follow' ? 'selected' : '' }}>
                                No Index, Follow
                            </option>
                            <option value="noindex, nofollow" {{ \App\Models\Setting::get('robots') == 'noindex, nofollow' ? 'selected' : '' }}>
                                No Index, No Follow
                            </option>
                        </select>
                        <div class="form-text">Instruksi untuk search engine crawler</div>
                        @error('robots')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sitemap_priority" class="form-label">Sitemap Priority</label>
                        <input type="number" class="form-control" id="sitemap_priority" name="sitemap_priority" 
                               value="{{ \App\Models\Setting::get('sitemap_priority', '0.8') }}" 
                               min="0" max="1" step="0.1">
                        <div class="form-text">Prioritas halaman di sitemap (0.0 - 1.0)</div>
                        @error('sitemap_priority')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Batal
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Simpan Pengaturan SEO
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission confirmation
    confirmSubmit('seoSettingsForm', 'Konfirmasi Simpan SEO', 'Apakah Anda yakin ingin menyimpan pengaturan SEO ini?');
    
    // Character counter for meta title
    const metaTitle = document.getElementById('meta_title');
    const metaTitleCount = document.getElementById('meta_title_count');
    const metaTitleWarning = document.getElementById('meta_title_warning');
    
    function updateMetaTitleCounter() {
        const length = metaTitle.value.length;
        metaTitleCount.textContent = length;
        
        if (length > 50) {
            metaTitleWarning.style.display = 'inline';
            metaTitleCount.className = 'text-warning';
        } else {
            metaTitleWarning.style.display = 'none';
            metaTitleCount.className = '';
        }
    }
    
    metaTitle.addEventListener('input', updateMetaTitleCounter);
    updateMetaTitleCounter(); // Initial call
    
    // Character counter for meta description
    const metaDescription = document.getElementById('meta_description');
    const metaDescriptionCount = document.getElementById('meta_description_count');
    const metaDescriptionWarning = document.getElementById('meta_description_warning');
    
    function updateMetaDescriptionCounter() {
        const length = metaDescription.value.length;
        metaDescriptionCount.textContent = length;
        
        if (length > 150) {
            metaDescriptionWarning.style.display = 'inline';
            metaDescriptionCount.className = 'text-warning';
        } else {
            metaDescriptionWarning.style.display = 'none';
            metaDescriptionCount.className = '';
        }
    }
    
    metaDescription.addEventListener('input', updateMetaDescriptionCounter);
    updateMetaDescriptionCounter(); // Initial call
    
    // Maps iframe live preview
    const mapsIframe = document.getElementById('maps_iframe');
    const mapsPreview = document.getElementById('maps-preview');
    
    function updateMapsPreview() {
        const iframeCode = mapsIframe.value.trim();
        
        if (iframeCode) {
            // Validate iframe code
            if (iframeCode.includes('<iframe') && iframeCode.includes('</iframe>')) {
                mapsPreview.innerHTML = `<div id="maps-iframe-container">${iframeCode}</div>`;
            } else {
                mapsPreview.innerHTML = `
                    <div class="text-center text-danger py-5">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                        <p class="mt-3">Kode iframe tidak valid. Pastikan Anda memasukkan kode iframe lengkap.</p>
                    </div>
                `;
            }
        } else {
            mapsPreview.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bi bi-geo-alt fs-1"></i>
                    <p class="mt-3">Preview maps akan muncul setelah Anda memasukkan iframe code</p>
                </div>
            `;
        }
    }
    
    mapsIframe.addEventListener('input', updateMapsPreview);
    
    // Style iframe in preview to be responsive
    const style = document.createElement('style');
    style.textContent = `
        #maps-preview iframe {
            width: 100% !important;
            height: 250px !important;
            border: 0;
            border-radius: 8px;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
