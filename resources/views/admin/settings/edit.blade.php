@extends('admin.layout')

@section('title', 'Edit Pengaturan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Pengaturan</h2>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Pengaturan: {{ $setting->key }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.update', $setting) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('key') is-invalid @enderror" 
                           id="key" 
                           name="key" 
                           value="{{ old('key', $setting->key) }}" 
                           required>
                    @error('key')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="group" class="form-label">Group <span class="text-danger">*</span></label>
                    <select class="form-select @error('group') is-invalid @enderror" 
                            id="group" 
                            name="group" 
                            required>
                        <option value="">Pilih Group</option>
                        <option value="general" {{ old('group', $setting->group) == 'general' ? 'selected' : '' }}>General</option>
                        <option value="company" {{ old('group', $setting->group) == 'company' ? 'selected' : '' }}>Company</option>
                        <option value="social" {{ old('group', $setting->group) == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="seo" {{ old('group', $setting->group) == 'seo' ? 'selected' : '' }}>SEO</option>
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
                        <option value="text" {{ old('type', $setting->type) == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="textarea" {{ old('type', $setting->type) == 'textarea' ? 'selected' : '' }}>Textarea</option>
                        <option value="image" {{ old('type', $setting->type) == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="json" {{ old('type', $setting->type) == 'json' ? 'selected' : '' }}>JSON</option>
                        <option value="boolean" {{ old('type', $setting->type) == 'boolean' ? 'selected' : '' }}>Boolean</option>
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
                           value="{{ old('description', $setting->description) }}">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="value" class="form-label">Value</label>
                    
                    @if($setting->type == 'textarea')
                        <textarea class="form-control @error('value') is-invalid @enderror" 
                                  id="value" 
                                  name="value" 
                                  rows="5">{{ old('value', $setting->value) }}</textarea>
                    @elseif($setting->type == 'boolean')
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="value" 
                                   name="value" 
                                   value="1"
                                   {{ old('value', $setting->value) ? 'checked' : '' }}>
                            <label class="form-check-label" for="value">
                                Aktif
                            </label>
                        </div>
                    @elseif($setting->type == 'image')
                        <div class="d-flex gap-2">
                            <input type="file" 
                                   class="form-control @error('value') is-invalid @enderror" 
                                   id="value" 
                                   name="value" 
                                   accept="image/*">
                            @if($setting->value)
                                <a href="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($setting->value)) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-secondary">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            @endif
                        </div>
                        @if($setting->value)
                            <small class="text-muted">Current: {{ $setting->value }}</small>
                        @endif
                    @else
                        <input type="text" 
                               class="form-control @error('value') is-invalid @enderror" 
                               id="value" 
                               name="value" 
                               value="{{ old('value', $setting->value) }}">
                    @endif
                    
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Informasi Pengaturan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Key:</strong> <code>{{ $setting->key }}</code></p>
                <p><strong>Group:</strong> <span class="badge bg-secondary">{{ $setting->group }}</span></p>
                <p><strong>Type:</strong> <span class="badge bg-info">{{ $setting->type }}</span></p>
            </div>
            <div class="col-md-6">
                <p><strong>Dibuat:</strong> {{ $setting->created_at->format('d M Y H:i') }}</p>
                <p><strong>Diperbarui:</strong> {{ $setting->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
