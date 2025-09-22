@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">Tentang Kami</h1>
            <p class="lead">Pelajari lebih lanjut tentang {{ \App\Models\Setting::get('company_name', 'YourStudio') }}</p>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5 bg-gradient-primary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Sejarah Kami</h2>
                <p class="lead">
                    {{ \App\Models\Setting::get('company_description', 'YourStudio adalah toko alat lukis dan clay terpercaya yang telah melayani kebutuhan kreativitas masyarakat selama bertahun-tahun.') }}
                </p>
                <p>
                    Kami didirikan dengan visi untuk menjadi partner terpercaya dalam mewujudkan kreativitas setiap individu. 
                    Dengan pengalaman dan keahlian yang mendalam di bidang seni dan kerajinan, kami menyediakan produk-produk 
                    berkualitas tinggi untuk memenuhi kebutuhan para seniman, mahasiswa, dan penggemar seni.
                </p>
            </div>
            <div class="col-lg-6">
                @if($aboutImages->count() > 0)
                    <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($aboutImages as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" 
                                         class="d-block w-100 rounded" 
                                         alt="{{ $image->title }}"
                                         style="height: 400px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        @if($aboutImages->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-image fs-1"></i>
                            <p class="mt-2">About Images</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-eye text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Visi</h4>
                        <p class="text-muted">
                            Menjadi toko alat lukis dan clay terdepan yang menginspirasi dan mendukung 
                            setiap individu untuk mewujudkan kreativitas mereka melalui produk berkualitas tinggi 
                            dan layanan yang prima.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-bullseye text-white fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Misi</h4>
                        <p class="text-muted">
                            Menyediakan produk alat lukis dan clay berkualitas tinggi dengan harga yang terjangkau, 
                            memberikan layanan konsultasi yang profesional, dan menciptakan komunitas yang mendukung 
                            perkembangan seni dan kreativitas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih Kami?</h2>
            <p class="text-muted">Keunggulan yang membuat kami berbeda</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-award text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Kualitas Terjamin</h5>
                    <p class="text-muted">
                        Semua produk kami telah melalui proses seleksi ketat untuk memastikan kualitas terbaik.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-info rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-people text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Tim Ahli</h5>
                    <p class="text-muted">
                        Tim kami terdiri dari para ahli di bidang seni yang siap memberikan konsultasi terbaik.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-heart text-white fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Pelayanan Ramah</h5>
                    <p class="text-muted">
                        Kami berkomitmen memberikan pelayanan yang ramah dan memuaskan untuk setiap pelanggan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">Hubungi Kami</h2>
                <p class="lead mb-4">
                    Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda.
                </p>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-geo-alt text-primary fs-3 me-3"></i>
                            <div class="text-start">
                                <strong>Alamat</strong>
                                <br>
                                <small class="text-muted">{{ \App\Models\Setting::get('company_address', 'Jl. Contoh No. 123, Jakarta') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-telephone text-primary fs-3 me-3"></i>
                            <div class="text-start">
                                <strong>Telepon</strong>
                                <br>
                                <small class="text-muted">{{ \App\Models\Setting::get('company_phone', '+62 123 456 789') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-envelope text-primary fs-3 me-3"></i>
                            <div class="text-start">
                                <strong>Email</strong>
                                <br>
                                <small class="text-muted">{{ \App\Models\Setting::get('company_email', 'info@yourstudio.com') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-chat-dots me-2"></i>Kirim Pesan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
