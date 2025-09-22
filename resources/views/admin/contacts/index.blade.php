@extends('admin.layout')

@section('title', 'Kontak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Pesan Kontak</h2>
    <a href="{{ route('admin.contacts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Kontak
    </a>
</div>

<!-- Filter Tabs -->
<div class="card mb-4">
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ request()->get('status') === null ? 'active' : '' }}" 
                   href="{{ route('admin.contacts.index') }}">
                    Semua ({{ \App\Models\Contact::count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('status') === 'unread' ? 'active' : '' }}" 
                   href="{{ route('admin.contacts.index', ['status' => 'unread']) }}">
                    Belum Dibaca ({{ \App\Models\Contact::unread()->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('status') === 'read' ? 'active' : '' }}" 
                   href="{{ route('admin.contacts.index', ['status' => 'read']) }}">
                    Sudah Dibaca ({{ \App\Models\Contact::read()->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->get('status') === 'replied' ? 'active' : '' }}" 
                   href="{{ route('admin.contacts.index', ['status' => 'replied']) }}">
                    Sudah Dibalas ({{ \App\Models\Contact::replied()->count() }})
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($contacts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr class="{{ $contact->status === 'unread' ? 'table-warning' : '' }}">
                                <td>{{ $contact->id }}</td>
                                <td>
                                    <strong>{{ $contact->name }}</strong>
                                    @if($contact->phone)
                                        <br><small class="text-muted">{{ $contact->phone }}</small>
                                    @endif
                                </td>
                                <td>{{ $contact->email }}</td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $contact->subject }}">
                                        {{ $contact->subject }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $contact->status_badge }}">
                                        {{ $contact->status_text }}
                                    </span>
                                    @if($contact->status === 'unread')
                                        <i class="bi bi-circle-fill text-warning ms-1" title="Belum dibaca"></i>
                                    @endif
                                </td>
                                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.contacts.show', $contact) }}" 
                                           class="btn btn-sm btn-outline-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.contacts.edit', $contact) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kontak ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $contacts->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-envelope fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">Belum ada pesan kontak</h4>
                <p class="text-muted">Pesan kontak dari pengunjung akan muncul di sini</p>
            </div>
        @endif
    </div>
</div>

<!-- Panduan CRUD Kontak -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan Manajemen Pesan Kontak
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="contactGuideAccordion">
                    <!-- Cara Melihat Pesan -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#viewContact">
                                <i class="bi bi-eye me-2"></i>Cara Melihat Pesan Kontak
                            </button>
                        </h2>
                        <div id="viewContact" class="accordion-collapse collapse" data-bs-parent="#contactGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>mata (👁️)</strong> pada pesan yang ingin dilihat</li>
                                    <li>Pesan akan ditandai sebagai <strong>"Sudah Dibaca"</strong> otomatis</li>
                                    <li>Anda dapat melihat detail lengkap: nama, email, telepon, subjek, dan pesan</li>
                                    <li>Gunakan tombol <strong>"Balas via Email"</strong> untuk merespons pelanggan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Membalas Pesan -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#replyContact">
                                <i class="bi bi-reply me-2"></i>Cara Membalas Pesan
                            </button>
                        </h2>
                        <div id="replyContact" class="accordion-collapse collapse" data-bs-parent="#contactGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka detail pesan dengan klik ikon <strong>mata (👁️)</strong></li>
                                    <li>Klik tombol <strong>"Balas via Email"</strong></li>
                                    <li>Email client akan terbuka dengan alamat penerima sudah terisi</li>
                                    <li>Tulis balasan yang sopan dan informatif</li>
                                    <li>Kirim email dan pesan akan otomatis ditandai sebagai <strong>"Sudah Dibalas"</strong></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Edit Status -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editContactStatus">
                                <i class="bi bi-pencil me-2"></i>Cara Edit Status Pesan
                            </button>
                        </h2>
                        <div id="editContactStatus" class="accordion-collapse collapse" data-bs-parent="#contactGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>pensil (✏️)</strong> pada pesan yang ingin diedit</li>
                                    <li>Ubah status pesan sesuai kebutuhan:
                                        <ul>
                                            <li><strong>Unread:</strong> Belum dibaca (default)</li>
                                            <li><strong>Read:</strong> Sudah dibaca</li>
                                            <li><strong>Replied:</strong> Sudah dibalas</li>
                                        </ul>
                                    </li>
                                    <li>Klik <strong>"Update Kontak"</strong> untuk menyimpan perubahan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Cara Hapus Pesan -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#deleteContact">
                                <i class="bi bi-trash me-2"></i>Cara Hapus Pesan
                            </button>
                        </h2>
                        <div id="deleteContact" class="accordion-collapse collapse" data-bs-parent="#contactGuideAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Klik ikon <strong>tempat sampah (🗑️)</strong> pada pesan yang ingin dihapus</li>
                                    <li>Konfirmasi penghapusan di popup yang muncul</li>
                                    <li>Pesan akan dihapus permanen dari database</li>
                                    <li><strong>⚠️ Peringatan:</strong> Tindakan ini tidak dapat dibatalkan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Filter & Pencarian -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filterContact">
                                <i class="bi bi-funnel me-2"></i>Filter & Pencarian Pesan
                            </button>
                        </h2>
                        <div id="filterContact" class="accordion-collapse collapse" data-bs-parent="#contactGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Semua:</strong> Menampilkan semua pesan kontak</li>
                                    <li><strong>Belum Dibaca:</strong> Pesan yang belum dibuka (highlight kuning)</li>
                                    <li><strong>Sudah Dibaca:</strong> Pesan yang sudah dibuka</li>
                                    <li><strong>Sudah Dibalas:</strong> Pesan yang sudah direspons</li>
                                    <li><strong>Pencarian:</strong> Gunakan fitur pencarian browser (Ctrl+F) untuk mencari nama atau email</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Trik -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactTips">
                                <i class="bi bi-lightbulb me-2"></i>Tips & Trik
                            </button>
                        </h2>
                        <div id="contactTips" class="accordion-collapse collapse" data-bs-parent="#contactGuideAccordion">
                            <div class="accordion-body">
                                <ul class="mb-0">
                                    <li><strong>Respon Cepat:</strong> Balas pesan dalam 24 jam untuk kepuasan pelanggan</li>
                                    <li><strong>Status Tracking:</strong> Gunakan status untuk melacak progress respons</li>
                                    <li><strong>Backup Email:</strong> Simpan email penting sebagai backup</li>
                                    <li><strong>Template Balasan:</strong> Buat template balasan untuk efisiensi</li>
                                    <li><strong>Follow Up:</strong> Tandai pesan yang perlu follow up</li>
                                    <li><strong>Data Privacy:</strong> Hapus pesan lama untuk menjaga privasi pelanggan</li>
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
