@extends('admin.layout')

@section('title', 'Kelola Tentang Kami')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Tentang Kami</h2>
    <a href="{{ route('admin.about-us.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Section
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aboutSections as $section)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $section->section)) }}</span>
                            </td>
                            <td>{{ $section->title ?? '-' }}</td>
                            <td>
                                @if($section->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>{{ $section->sort_order }}</td>
                            <td>{{ $section->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.about-us.show', $section) }}" class="btn btn-sm btn-outline-info" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.about-us.edit', $section) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.about-us.toggle', $section) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $section->is_active ? 'warning' : 'success' }}" 
                                                title="{{ $section->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi bi-{{ $section->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.about-us.destroy', $section) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus section ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada section tentang kami
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="alert alert-info">
                <h6><i class="bi bi-info-circle me-2"></i>Informasi</h6>
                <ul class="mb-0">
                    <li>Section yang tidak aktif tidak akan ditampilkan di halaman public</li>
                    <li>Urutan section ditentukan berdasarkan kolom "Urutan" (sort_order)</li>
                    <li>Section "why_choose_us" dapat memiliki multiple features</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert alert-success">
                <h6><i class="bi bi-lightbulb me-2"></i>Quick Guide</h6>
                <ul class="mb-0 small">
                    <li><strong>Hero:</strong> Judul utama halaman</li>
                    <li><strong>History:</strong> Cerita perusahaan</li>
                    <li><strong>Vision:</strong> Impian masa depan</li>
                    <li><strong>Mission:</strong> Tujuan perusahaan</li>
                    <li><strong>Why Choose Us:</strong> Keunggulan + features</li>
                    <li><strong>Contact Info:</strong> Ajakan kontak</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="alert alert-warning">
        <h6><i class="bi bi-exclamation-triangle me-2"></i>Tips Pengelolaan</h6>
        <div class="row">
            <div class="col-md-6">
                <ul class="mb-0">
                    <li>Gunakan urutan 1-6 untuk mengatur tampilan</li>
                    <li>Nonaktifkan section untuk menyembunyikan sementara</li>
                    <li>Edit section untuk mengubah konten</li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="mb-0">
                    <li>Preview di halaman public: <a href="{{ route('about') }}" target="_blank" class="alert-link">Lihat Halaman</a></li>
                    <li>Gunakan icon Bootstrap untuk features</li>
                    <li>Pilih warna yang sesuai dengan brand</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
