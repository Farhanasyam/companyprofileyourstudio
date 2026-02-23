@extends('admin.layout')

@section('title', 'Detail Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Artikel</h2>
    <div>
        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $article->title }}</h5>
            </div>
            <div class="card-body">
                @if($article->featured_image)
                    <img src="{{ asset('storage/' . $article->featured_image) }}" 
                         alt="{{ $article->title }}" 
                         class="img-fluid rounded mb-3">
                @endif
                
                @if($article->excerpt)
                    <div class="mb-3">
                        <h6>Ringkasan:</h6>
                        <div class="text-muted article-html">{!! $article->excerpt_html !!}</div>
                    </div>
                @endif
                
                <div class="mb-3">
                    <h6>Konten:</h6>
                    <div class="text-justify article-html">{!! $article->content_html !!}</div>
                </div>
                
                @if($article->tags && count($article->tags) > 0)
                    <div class="mb-3">
                        <h6>Tags:</h6>
                        @foreach($article->tags as $tag)
                            <span class="badge bg-secondary me-1">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Artikel</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Status:</label>
                    <p>
                        @if($article->status === 'published')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Published
                            </span>
                        @else
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-clock me-1"></i>Draft
                            </span>
                        @endif
                        
                        @if($article->is_featured)
                            <span class="badge bg-warning fs-6 ms-2">
                                <i class="bi bi-star me-1"></i>Unggulan
                            </span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Penulis:</label>
                    <p>{{ $article->user->name }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Dibuat:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-plus me-1"></i>
                        {{ $article->created_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $article->created_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Diperbarui:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-check me-1"></i>
                        {{ $article->updated_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $article->updated_at->format('H:i:s') }}</small>
                    </p>
                </div>
                
                @if($article->published_at)
                <div class="mb-3">
                    <label class="form-label fw-bold">Dipublikasi:</label>
                    <p class="mb-0">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $article->published_at->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ $article->published_at->format('H:i:s') }}</small>
                    </p>
                </div>
                @endif
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Slug:</label>
                    <p class="mb-0"><code>{{ $article->slug }}</code></p>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">SEO Information</h5>
            </div>
            <div class="card-body">
                @if($article->meta_title)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Title:</label>
                        <p class="mb-0">{{ $article->meta_title }}</p>
                    </div>
                @endif
                
                @if($article->meta_description)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description:</label>
                        <p class="mb-0">{{ $article->meta_description }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Artikel
                    </a>
                    
                    <form action="{{ route('admin.articles.destroy', $article) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Artikel
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.article-html p { margin-bottom: 0.75rem; }
.article-html p:last-child { margin-bottom: 0; }
.article-html strong, .article-html b { font-weight: 600; }
.article-html ul, .article-html ol { padding-left: 1.5rem; margin-bottom: 0.75rem; }
</style>
@endsection
