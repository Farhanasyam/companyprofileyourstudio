@extends('admin.layout')

@section('title', 'Detail Section Tentang Kami')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Section Tentang Kami</h2>
    <div>
        <a href="{{ route('admin.about-us.edit', $aboutUs) }}" class="btn btn-primary me-2">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.about-us.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Section</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Section:</strong></td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $aboutUs->section)) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Judul:</strong></td>
                        <td>{{ $aboutUs->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Sub Judul:</strong></td>
                        <td>{{ $aboutUs->subtitle ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Konten:</strong></td>
                        <td>{{ $aboutUs->content ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi:</strong></td>
                        <td>{{ $aboutUs->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($aboutUs->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Urutan:</strong></td>
                        <td>{{ $aboutUs->sort_order }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $aboutUs->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diupdate:</strong></td>
                        <td>{{ $aboutUs->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.about-us.edit', $aboutUs) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Section
                    </a>
                    
                    <form action="{{ route('admin.about-us.toggle', $aboutUs) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $aboutUs->is_active ? 'warning' : 'success' }} w-100">
                            <i class="bi bi-{{ $aboutUs->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $aboutUs->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.about-us.destroy', $aboutUs) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus section ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Section
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($aboutUs->features && count($aboutUs->features) > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Features</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($aboutUs->features as $index => $feature)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="bg-{{ $feature['color'] ?? 'primary' }} rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi {{ $feature['icon'] ?? 'bi-star' }} text-white fs-4"></i>
                                    </div>
                                    <h6 class="fw-bold">{{ $feature['title'] ?? 'Feature ' . ($index + 1) }}</h6>
                                    <p class="text-muted small">{{ $feature['description'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="mt-4">
    <div class="alert alert-info">
        <h6><i class="bi bi-info-circle me-2"></i>Preview di Halaman Public</h6>
        <p class="mb-0">
            Section ini akan ditampilkan di halaman <a href="{{ route('about') }}" target="_blank" class="alert-link">Tentang Kami</a> 
            jika statusnya aktif.
        </p>
    </div>
</div>
@endsection
