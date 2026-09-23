@extends('admin.layout')

@section('title', 'Manajemen Event')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-calendar-event me-2"></i>Manajemen Event
    </h2>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Event
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-event fs-1 mb-2"></i>
                <h3>{{ $events->total() }}</h3>
                <p class="mb-0">Total Event</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check fs-1 mb-2"></i>
                <h3>{{ $stats['published'] }}</h3>
                <p class="mb-0">Published</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-plus fs-1 mb-2"></i>
                <h3>{{ $stats['upcoming'] }}</h3>
                <p class="mb-0">Upcoming</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-star fs-1 mb-2"></i>
                <h3>{{ $stats['featured'] }}</h3>
                <p class="mb-0">Featured</p>
            </div>
        </div>
    </div>
</div>

<!-- Events Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>Daftar Event
        </h5>
    </div>
    <div class="card-body">
        @if($events->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>
                                    @if($event->image)
                                        <img src="{{ $event->image_url }}" 
                                             alt="{{ $event->title }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-calendar-event text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $event->title }}</strong>
                                        @if($event->title_en)
                                            <br><small class="text-muted">{{ $event->title_en }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $event->start_date->format('d M Y, H:i') }}</strong>
                                        @if($event->end_date)
                                            <br><small class="text-muted">Sampai: {{ $event->end_date->format('d M Y, H:i') }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($event->location)
                                        <span>{{ $event->location }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($event->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @elseif($event->status === 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                    @if(!$event->is_active)
                                        <br><span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($event->is_featured)
                                        <span class="badge bg-primary">
                                            <i class="bi bi-star-fill"></i> Featured
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.events.show', $event) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.events.edit', $event) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Hapus"
                                                onclick="confirmDelete('{{ route('admin.events.destroy', $event) }}', 'Event', 'Apakah Anda yakin ingin menghapus event ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-calendar-x" style="font-size: 4rem; color: var(--medium-brown);"></i>
                </div>
                <h4 style="color: var(--dark-brown);" class="mt-3 fw-bold">Belum Ada Event</h4>
                <p style="color: var(--medium-brown);" class="fs-5">Mulai buat event pertama Anda!</p>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Event Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Panduan CRUD Event -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan CRUD Event
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="eventGuideAccordion">
                    <!-- Cara Membuat Event -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#createEvent">
                                <i class="bi bi-plus-circle me-2"></i>Cara Membuat Event Baru
                            </button>
                        </h2>
                        <div id="createEvent" class="accordion-collapse collapse" data-bs-parent="#eventGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik tombol <strong>"Tambah Event"</strong> di halaman ini</li>
                                    <li>Isi <strong>Judul Event</strong> (wajib) - gunakan judul yang menarik</li>
                                    <li>Isi <strong>Deskripsi Singkat</strong> - ringkasan event dalam 1-2 kalimat</li>
                                    <li>Isi <strong>Deskripsi Lengkap</strong> (wajib) - detail lengkap tentang event</li>
                                    <li>Set <strong>Tanggal & Waktu Mulai</strong> (wajib) - pastikan tanggal di masa depan</li>
                                    <li>Set <strong>Tanggal & Waktu Selesai</strong> (opsional) - untuk event multi-hari</li>
                                    <li>Isi <strong>Lokasi</strong> - tempat event diadakan</li>
                                    <li>Upload <strong>Gambar Utama</strong> (opsional) - gambar representatif event</li>
                                    <li>Upload <strong>Video</strong> (opsional) - video promosi event</li>
                                    <li>Upload <strong>Galeri</strong> (opsional) - multiple gambar/video</li>
                                    <li>Set <strong>Status</strong> - pilih "Published" untuk tampil di website</li>
                                    <li>Centang <strong>"Featured"</strong> jika event unggulan</li>
                                    <li>Klik <strong>"Simpan Event"</strong></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Edit Event -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editEvent">
                                <i class="bi bi-pencil me-2"></i>Cara Edit Event
                            </button>
                        </h2>
                        <div id="editEvent" class="accordion-collapse collapse" data-bs-parent="#eventGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>pensil (✏️)</strong> pada event yang ingin diedit</li>
                                    <li>Ubah informasi yang diperlukan</li>
                                    <li>Untuk mengganti media, upload file baru (akan mengganti yang lama)</li>
                                    <li>Klik <strong>"Update Event"</strong> untuk menyimpan perubahan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Hapus Event -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#deleteEvent">
                                <i class="bi bi-trash me-2"></i>Cara Hapus Event
                            </button>
                        </h2>
                        <div id="deleteEvent" class="accordion-collapse collapse" data-bs-parent="#eventGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>tempat sampah (🗑️)</strong> pada event yang ingin dihapus</li>
                                    <li>Konfirmasi penghapusan di popup yang muncul</li>
                                    <li>Event dan semua media terkait akan dihapus permanen</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Trik -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#eventTips">
                                <i class="bi bi-lightbulb me-2"></i>Tips & Trik
                            </button>
                        </h2>
                        <div id="eventTips" class="accordion-collapse collapse" data-bs-parent="#eventGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Judul:</strong> Gunakan kata kunci yang menarik dan mudah diingat</li>
                                    <li><strong>Deskripsi:</strong> Jelaskan manfaat dan apa yang akan dipelajari peserta</li>
                                    <li><strong>Tanggal:</strong> Pastikan tanggal mulai di masa depan</li>
                                    <li><strong>Gambar:</strong> Gunakan gambar berkualitas tinggi (min 800x600px)</li>
                                    <li><strong>Status:</strong> "Draft" untuk menyimpan sementara, "Published" untuk tampil di website</li>
                                    <li><strong>Featured:</strong> Gunakan untuk event unggulan yang ingin ditonjolkan</li>
                                    <li><strong>Multilingual:</strong> Isi versi English untuk pengunjung internasional</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
