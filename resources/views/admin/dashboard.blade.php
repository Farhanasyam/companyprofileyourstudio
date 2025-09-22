@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <div class="text-muted">
        Selamat datang, {{ auth()->user()->name }}!
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-box fs-1 mb-2"></i>
                <h3>{{ $stats['products'] }}</h3>
                <p class="mb-0">Produk</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-tags fs-1 mb-2"></i>
                <h3>{{ $stats['categories'] }}</h3>
                <p class="mb-0">Kategori</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-newspaper fs-1 mb-2"></i>
                <h3>{{ $stats['articles'] }}</h3>
                <p class="mb-0">Artikel</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-event fs-1 mb-2"></i>
                <h3>{{ $stats['events'] }}</h3>
                <p class="mb-0">Event</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-envelope fs-1 mb-2"></i>
                <h3>{{ $stats['contacts'] }}</h3>
                <p class="mb-0">Kontak</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-envelope-exclamation fs-1 mb-2"></i>
                <h3>{{ $stats['unread_contacts'] }}</h3>
                <p class="mb-0">Belum Dibaca</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-images fs-1 mb-2"></i>
                <h3>{{ $stats['galleries'] }}</h3>
                <p class="mb-0">Galeri</p>
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
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Artikel
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Event
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Galeri
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-person-gear me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
