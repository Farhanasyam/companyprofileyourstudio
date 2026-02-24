@extends('admin.layout')

@section('title', 'Detail Pengaturan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Pengaturan</h2>
    <div>
        <a href="{{ route('admin.settings.edit', $setting) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pengaturan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Key:</label>
                        <p><code class="bg-light p-2 rounded d-block">{{ $setting->key }}</code></p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Group:</label>
                        <p>
                            <span class="badge bg-secondary fs-6">
                                @switch($setting->group)
                                    @case('company')
                                        <i class="bi bi-building me-1"></i>Company
                                        @break
                                    @case('social')
                                        <i class="bi bi-share me-1"></i>Social
                                        @break
                                    @case('seo')
                                        <i class="bi bi-search me-1"></i>SEO
                                        @break
                                    @case('general')
                                        <i class="bi bi-gear me-1"></i>General
                                        @break
                                    @default
                                        {{ ucfirst($setting->group) }}
                                @endswitch
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Type:</label>
                        <p>
                            <span class="badge bg-info fs-6">
                                @switch($setting->type)
                                    @case('text')
                                        <i class="bi bi-type me-1"></i>Text
                                        @break
                                    @case('textarea')
                                        <i class="bi bi-textarea-resize me-1"></i>Textarea
                                        @break
                                    @case('image')
                                        <i class="bi bi-image me-1"></i>Image
                                        @break
                                    @case('json')
                                        <i class="bi bi-code-square me-1"></i>JSON
                                        @break
                                    @case('boolean')
                                        <i class="bi bi-toggle-on me-1"></i>Boolean
                                        @break
                                    @default
                                        {{ ucfirst($setting->type) }}
                                @endswitch
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Deskripsi:</label>
                        <p>{{ $setting->description ?: '-' }}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Value:</label>
                    <div class="bg-light p-3 rounded">
                        @if($setting->type == 'boolean')
                            <span class="badge {{ $setting->value ? 'bg-success' : 'bg-danger' }} fs-6">
                                <i class="bi bi-{{ $setting->value ? 'check-circle' : 'x-circle' }} me-1"></i>
                                {{ $setting->value ? 'True' : 'False' }}
                            </span>
                        @elseif($setting->type == 'image' && $setting->value)
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($setting->value)) }}" 
                                     alt="Setting Image" 
                                     class="img-thumbnail" 
                                     style="max-width: 100px; max-height: 100px;">
                                <div>
                                    <p class="mb-1"><strong>File:</strong> {{ $setting->value }}</p>
                                    <a href="{{ asset('/storage/' . \App\Helpers\ImageHelper::encodePathForUrl($setting->value)) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Lihat Gambar
                                    </a>
                                </div>
                            </div>
                        @elseif($setting->type == 'json')
                            <pre class="mb-0"><code>{{ json_encode(json_decode($setting->value), JSON_PRETTY_PRINT) }}</code></pre>
                        @else
                            <p class="mb-0">{{ $setting->value ?: '-' }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Dibuat:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-plus me-1"></i>
                        {{ $setting->created_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $setting->created_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Diperbarui:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ $setting->updated_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $setting->updated_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">ID:</label>
                    <p class="mb-0"><code>{{ $setting->id }}</code></p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.settings.edit', $setting) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Pengaturan
                    </a>
                    
                    <form action="{{ route('admin.settings.destroy', $setting) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus pengaturan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Pengaturan
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
