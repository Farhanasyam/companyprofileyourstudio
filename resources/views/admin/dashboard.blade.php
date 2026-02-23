@extends('admin.layout')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="mb-1">Dashboard</h2>
        <p class="text-muted mb-0 small">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Ringkasan konten dan aksi cepat.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3 mb-4">
    <div class="col">
        <div class="card stats-card h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-box fs-2 mb-1"></i>
                <h3 class="mb-0">{{ $stats['products'] }}</h3>
                <p class="mb-0 small">Produk</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stats-card h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-tags fs-2 mb-1"></i>
                <h3 class="mb-0">{{ $stats['categories'] }}</h3>
                <p class="mb-0 small">Kategori</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stats-card h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-newspaper fs-2 mb-1"></i>
                <h3 class="mb-0">{{ $stats['articles'] }}</h3>
                <p class="mb-0 small">Artikel</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stats-card h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-calendar-event fs-2 mb-1"></i>
                <h3 class="mb-0">{{ $stats['events'] }}</h3>
                <p class="mb-0 small">Event</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stats-card h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-images fs-2 mb-1"></i>
                <h3 class="mb-0">{{ $stats['galleries'] }}</h3>
                <p class="mb-0 small">Galeri</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stats-card h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-envelope fs-2 mb-1"></i>
                <h3 class="mb-0">{{ $stats['contacts'] }}</h3>
                <p class="mb-0 small">Pesan Masuk
                    @if($stats['unread_contacts'] > 0)
                        <span class="badge bg-danger ms-1">{{ $stats['unread_contacts'] }} baru</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Contacts -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kontak Terbaru</h5>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recent_contacts->count() > 0)
                    @foreach($recent_contacts as $contact)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $contact->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $contact->subject }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $contact->status_badge }}">{{ $contact->status_text }}</span>
                                <br>
                                <small class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada kontak</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Articles -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Artikel Terbaru</h5>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recent_articles->count() > 0)
                    @foreach($recent_articles as $article)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $article->title }}</strong>
                                <br>
                                <small class="text-muted">Oleh: {{ $article->user->name }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $article->status === 'published' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada artikel</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-2">
                    <div class="col">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Artikel
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Event
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Galeri
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-person-gear me-2"></i>Profil Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
