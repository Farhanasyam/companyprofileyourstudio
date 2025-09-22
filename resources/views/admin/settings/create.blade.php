@extends('admin.layout')

@section('title', 'Tambah Pengaturan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Pengaturan Baru</h2>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Pengaturan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('key') is-invalid @enderror" 
                           id="key" 
                           name="key" 
                           value="{{ old('key') }}" 
                           required>
                    @error('key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Key unik untuk pengaturan (contoh: company_name, social_facebook)</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="group" class="form-label">Group <span class="text-danger">*</span></label>
                    <select class="form-select @error('group') is-invalid @enderror" 
                            id="group" 
                            name="group" 
                            required>
                        <option value="">Pilih Group</option>
                        <option value="general" {{ old('group') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="company" {{ old('group') == 'company' ? 'selected' : '' }}>Company</option>
                        <option value="social" {{ old('group') == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="seo" {{ old('group') == 'seo' ? 'selected' : '' }}>SEO</option>
                    </select>
                    @error('group')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" 
                            id="type" 
                            name="type" 
                            required>
                        <option value="">Pilih Type</option>
                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="textarea" {{ old('type') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON</option>
                        <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <input type="text" 
                           class="form-control @error('description') is-invalid @enderror" 
                           id="description" 
                           name="description" 
                           value="{{ old('description') }}"
                           placeholder="Deskripsi singkat pengaturan">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="value" class="form-label">Value</label>
                    <input type="text" 
                           class="form-control @error('value') is-invalid @enderror" 
                           id="value" 
                           name="value" 
                           value="{{ old('value') }}"
                           placeholder="Nilai default pengaturan">
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Nilai default untuk pengaturan ini</small>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Panduan Penggunaan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Group Pengaturan:</h6>
                <ul class="list-unstyled">
                    <li><span class="badge bg-secondary me-2">general</span> Pengaturan umum</li>
                    <li><span class="badge bg-secondary me-2">company</span> Informasi perusahaan</li>
                    <li><span class="badge bg-secondary me-2">social</span> Media sosial</li>
                    <li><span class="badge bg-secondary me-2">seo</span> SEO & meta tags</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Type Input:</h6>
                <ul class="list-unstyled">
                    <li><span class="badge bg-info me-2">text</span> Input teks singkat</li>
                    <li><span class="badge bg-info me-2">textarea</span> Input teks panjang</li>
                    <li><span class="badge bg-info me-2">image</span> Upload gambar</li>
                    <li><span class="badge bg-info me-2">json</span> Data JSON</li>
                    <li><span class="badge bg-info me-2">boolean</span> True/False</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
