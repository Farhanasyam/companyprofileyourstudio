@extends('admin.layout')

@section('title', 'Pengaturan SEO')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pengaturan SEO</h2>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Pengaturan
    </a>
</div>

<form id="seoSettingsForm" action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <!-- Basic SEO -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO Dasar</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                               value="{{ \App\Models\Setting::get('meta_title') }}" maxlength="60">
                        <small class="text-muted">Maksimal 60 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" 
                                  rows="3" maxlength="160">{{ \App\Models\Setting::get('meta_description') }}</textarea>
                        <small class="text-muted">Maksimal 160 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                               value="{{ \App\Models\Setting::get('meta_keywords') }}">
                        <small class="text-muted">Pisahkan dengan koma</small>
                    </div>

                    <div class="mb-3">
                        <label for="canonical_url" class="form-label">Canonical URL</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url" 
                               value="{{ \App\Models\Setting::get('canonical_url') }}">
                    </div>

                    <div class="mb-3">
                        <label for="robots" class="form-label">Robots Meta Tag</label>
                        <select class="form-select" id="robots" name="robots">
                            <option value="index, follow" {{ \App\Models\Setting::get('robots') === 'index, follow' ? 'selected' : '' }}>Index, Follow</option>
                            <option value="index, nofollow" {{ \App\Models\Setting::get('robots') === 'index, nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                            <option value="noindex, follow" {{ \App\Models\Setting::get('robots') === 'noindex, follow' ? 'selected' : '' }}>No Index, Follow</option>
                            <option value="noindex, nofollow" {{ \App\Models\Setting::get('robots') === 'noindex, nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Open Graph -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Open Graph (Facebook)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="og_title" class="form-label">OG Title</label>
                        <input type="text" class="form-control" id="og_title" name="og_title" 
                               value="{{ \App\Models\Setting::get('og_title') }}">
                    </div>

                    <div class="mb-3">
                        <label for="og_description" class="form-label">OG Description</label>
                        <textarea class="form-control" id="og_description" name="og_description" 
                                  rows="3">{{ \App\Models\Setting::get('og_description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="og_image" class="form-label">OG Image</label>
                        <input type="file" class="form-control" id="og_image" name="og_image" accept="image/*">
                        @if(\App\Models\Setting::get('og_image'))
                            <small class="text-muted">Current: {{ \App\Models\Setting::get('og_image') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Twitter Card -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Twitter Card</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="twitter_title" class="form-label">Twitter Title</label>
                        <input type="text" class="form-control" id="twitter_title" name="twitter_title" 
                               value="{{ \App\Models\Setting::get('twitter_title') }}">
                    </div>

                    <div class="mb-3">
                        <label for="twitter_description" class="form-label">Twitter Description</label>
                        <textarea class="form-control" id="twitter_description" name="twitter_description" 
                                  rows="3">{{ \App\Models\Setting::get('twitter_description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="twitter_image" class="form-label">Twitter Image</label>
                        <input type="file" class="form-control" id="twitter_image" name="twitter_image" accept="image/*">
                        @if(\App\Models\Setting::get('twitter_image'))
                            <small class="text-muted">Current: {{ \App\Models\Setting::get('twitter_image') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical SEO -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Technical SEO</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="sitemap_priority" class="form-label">Sitemap Priority</label>
                        <select class="form-select" id="sitemap_priority" name="sitemap_priority">
                            <option value="1.0" {{ \App\Models\Setting::get('sitemap_priority') === '1.0' ? 'selected' : '' }}>1.0 (Highest)</option>
                            <option value="0.8" {{ \App\Models\Setting::get('sitemap_priority') === '0.8' ? 'selected' : '' }}>0.8 (High)</option>
                            <option value="0.6" {{ \App\Models\Setting::get('sitemap_priority') === '0.6' ? 'selected' : '' }}>0.6 (Medium)</option>
                            <option value="0.4" {{ \App\Models\Setting::get('sitemap_priority') === '0.4' ? 'selected' : '' }}>0.4 (Low)</option>
                            <option value="0.2" {{ \App\Models\Setting::get('sitemap_priority') === '0.2' ? 'selected' : '' }}>0.2 (Lowest)</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>Tips SEO:</h6>
                        <ul class="mb-0">
                            <li>Meta title harus unik dan menarik</li>
                            <li>Meta description harus informatif dan persuasif</li>
                            <li>Gunakan keywords yang relevan</li>
                            <li>OG dan Twitter images harus 1200x630px</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Simpan Pengaturan SEO
        </button>
    </div>
</form>

<!-- SEO Preview -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Preview SEO</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Google Search Result:</h6>
                <div class="border p-3 rounded bg-light">
                    <div class="text-primary fw-bold" id="preview-title">
                        {{ \App\Models\Setting::get('meta_title') ?: 'YourStudio - Toko Alat Lukis dan Clay Terpercaya' }}
                    </div>
                    <div class="text-success small" id="preview-url">
                        {{ url('/') }}
                    </div>
                    <div class="text-muted small mt-1" id="preview-description">
                        {{ \App\Models\Setting::get('meta_description') ?: 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik...' }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Facebook Share Preview:</h6>
                <div class="border p-3 rounded bg-light">
                    <div class="d-flex">
                        <div class="bg-secondary me-3" style="width: 60px; height: 60px;"></div>
                        <div>
                            <div class="fw-bold" id="preview-og-title">
                                {{ \App\Models\Setting::get('og_title') ?: \App\Models\Setting::get('meta_title') ?: 'YourStudio' }}
                            </div>
                            <div class="text-muted small" id="preview-og-description">
                                {{ \App\Models\Setting::get('og_description') ?: \App\Models\Setting::get('meta_description') ?: 'Toko alat lukis dan clay terpercaya...' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation and confirmation
    confirmSubmit('seoSettingsForm', 'Konfirmasi Simpan SEO', 'Apakah Anda yakin ingin menyimpan pengaturan SEO ini?');
    
    // Live preview update
    const fields = ['meta_title', 'meta_description', 'og_title', 'og_description'];
    
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener('input', function() {
                updatePreview();
            });
        }
    });
    
    function updatePreview() {
        const title = document.getElementById('meta_title').value || 'YourStudio - Toko Alat Lukis dan Clay Terpercaya';
        const description = document.getElementById('meta_description').value || 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik...';
        const ogTitle = document.getElementById('og_title').value || title;
        const ogDescription = document.getElementById('og_description').value || description;
        
        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-description').textContent = description;
        document.getElementById('preview-og-title').textContent = ogTitle;
        document.getElementById('preview-og-description').textContent = ogDescription;
    }
    
    // Character counter for meta title and description
    const metaTitle = document.getElementById('meta_title');
    const metaDescription = document.getElementById('meta_description');
    
    if (metaTitle) {
        metaTitle.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 60;
            if (length > maxLength) {
                this.classList.add('is-invalid');
                showWarningToast(`Meta title terlalu panjang (${length}/60 karakter)`);
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    
    if (metaDescription) {
        metaDescription.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 160;
            if (length > maxLength) {
                this.classList.add('is-invalid');
                showWarningToast(`Meta description terlalu panjang (${length}/160 karakter)`);
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
});
</script>
@endsection
