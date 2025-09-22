@extends('admin.layout')

@section('title', 'Pengaturan SEO')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pengaturan SEO</h2>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Pengaturan
    </a>
</div>

<div class="alert alert-info">
    <h5><i class="bi bi-info-circle me-2"></i>SEO Settings Page</h5>
    <p>Halaman ini untuk mengelola pengaturan SEO website.</p>
    <p>Jumlah setting SEO: {{ $seoSettings->count() ?? 0 }}</p>
</div>

<form id="seoSettingsForm" action="{{ route('admin.settings.seo.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="card">
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
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Simpan Pengaturan SEO
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation and confirmation
    confirmSubmit('seoSettingsForm', 'Konfirmasi Simpan SEO', 'Apakah Anda yakin ingin menyimpan pengaturan SEO ini?');
});
</script>
@endsection
