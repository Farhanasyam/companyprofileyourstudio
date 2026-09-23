@php
    $guide = null;

    if (request()->routeIs('admin.dashboard')) {
        $guide = [
            'icon' => 'speedometer2',
            'title' => 'Panduan Dashboard',
            'intro' => 'Gunakan dashboard untuk melihat kondisi website dan menuju aksi yang paling sering digunakan.',
            'steps' => [
                'Periksa kartu ringkasan untuk melihat jumlah produk, artikel, event, galeri, dan pesan masuk.',
                'Klik Lihat Semua pada Kontak Terbaru atau Artikel Terbaru untuk membuka daftar lengkap.',
                'Gunakan Aksi Cepat untuk menambahkan konten baru tanpa membuka menu sidebar.',
            ],
            'tip' => 'Angka Pesan Masuk berwarna merah berarti ada pesan kontak yang belum dibaca.',
        ];
    } elseif (request()->routeIs('admin.products.*')) {
        $guide = [
            'icon' => 'box',
            'title' => 'Panduan Produk',
            'intro' => 'Produk adalah barang yang tampil di katalog dan dapat dipilih oleh pengunjung saat membuat pesanan.',
            'steps' => [
                'Klik Tambah Produk, isi nama, kategori, deskripsi, gambar, dan informasi pendukung.',
                'Aktifkan status Aktif agar produk tampil di website. Gunakan Featured untuk produk unggulan.',
                'Gunakan Lihat untuk memeriksa tampilan, Edit untuk memperbarui, dan Hapus hanya jika data memang tidak diperlukan.',
            ],
            'tip' => 'Gunakan gambar yang jelas dan kategori yang tepat agar produk mudah ditemukan.',
        ];
    } elseif (request()->routeIs('admin.categories.*')) {
        $guide = [
            'icon' => 'tags',
            'title' => 'Panduan Kategori',
            'intro' => 'Kategori membantu pengunjung menyaring dan menemukan produk.',
            'steps' => [
                'Buat kategori dengan nama yang singkat dan mudah dipahami.',
                'Atur urutan untuk menentukan posisi kategori di website.',
                'Nonaktifkan kategori jika sementara tidak ingin menampilkannya.',
                'Kategori yang masih memiliki produk tidak dapat dihapus sebelum produknya dipindahkan.',
            ],
            'tip' => 'Selesaikan kategori terlebih dahulu sebelum menambahkan banyak produk.',
        ];
    } elseif (request()->routeIs('admin.articles.*')) {
        $guide = [
            'icon' => 'newspaper',
            'title' => 'Panduan Artikel',
            'intro' => 'Artikel digunakan untuk berita, tips, dan informasi yang ingin dibagikan kepada pengunjung.',
            'steps' => [
                'Klik Tambah Artikel, isi judul, ringkasan, dan konten artikel.',
                'Gunakan gambar featured agar artikel terlihat menarik di daftar dan halaman utama.',
                'Simpan sebagai Draft untuk dikerjakan nanti atau Published agar langsung tampil di website.',
                'Gunakan Featured untuk menampilkan artikel pada area unggulan.',
            ],
            'tip' => 'Gunakan judul yang jelas dan isi meta description untuk membantu SEO.',
        ];
    } elseif (request()->routeIs('admin.events.*')) {
        $guide = [
            'icon' => 'calendar-event',
            'title' => 'Panduan Event',
            'intro' => 'Event digunakan untuk mengumumkan kegiatan, jadwal, lokasi, dan informasi pendaftaran.',
            'steps' => [
                'Isi judul, tanggal mulai, deskripsi, dan lokasi event.',
                'Tambahkan gambar atau video agar informasi event lebih menarik.',
                'Pilih status event dan aktifkan Featured bila ingin menonjolkannya.',
                'Perbarui data event jika jadwal, lokasi, atau status berubah.',
            ],
            'tip' => 'Pastikan tanggal mulai dan selesai benar agar status upcoming dan past akurat.',
        ];
    } elseif (request()->routeIs('admin.galleries.*')) {
        $guide = [
            'icon' => 'images',
            'title' => 'Panduan Galeri',
            'intro' => 'Galeri mengatur gambar visual untuk hero, halaman tentang kami, produk, dan galeri umum.',
            'steps' => [
                'Upload gambar, beri judul, lalu pilih tipe tampilan yang sesuai.',
                'Gunakan Urutan untuk menentukan posisi gambar dalam kelompoknya.',
                'Aktifkan status agar gambar tampil di website.',
                'Gunakan filter Hero, About, Product, atau Gallery untuk menemukan data lebih cepat.',
            ],
            'tip' => 'Gunakan ukuran dan rasio gambar yang seragam dalam satu kelompok agar tampilan rapi.',
        ];
    } elseif (request()->routeIs('admin.contacts.*')) {
        $guide = [
            'icon' => 'envelope',
            'title' => 'Panduan Pesan Kontak',
            'intro' => 'Halaman ini berisi pesan yang dikirim pengunjung melalui formulir kontak website.',
            'steps' => [
                'Gunakan filter Belum Dibaca, Sudah Dibaca, atau Sudah Dibalas untuk menyaring pesan.',
                'Buka pesan untuk membaca detail dan menandainya sebagai sudah dibaca.',
                'Gunakan form balasan untuk mencatat jawaban kepada pengunjung.',
                'Hapus pesan yang sudah tidak diperlukan setelah memastikan informasinya aman.',
            ],
            'tip' => 'Prioritaskan pesan berstatus Belum Dibaca agar tidak ada pertanyaan pengunjung terlewat.',
        ];
    } elseif (request()->routeIs('admin.orders.*')) {
        $guide = [
            'icon' => 'cart-check',
            'title' => 'Panduan Riwayat Order',
            'intro' => 'Riwayat Order menyimpan pesanan yang dikirim pengunjung melalui fitur WhatsApp.',
            'steps' => [
                'Buka detail order untuk melihat data pemesan, daftar produk, jumlah, dan catatan.',
                'Gunakan informasi kontak pada detail untuk menindaklanjuti pesanan.',
                'Pengaturan nomor WhatsApp dan template pesan tersedia di menu Order via WA.',
            ],
            'tip' => 'Pastikan nomor WhatsApp pada menu Order via WA aktif agar pesanan dapat diteruskan.',
        ];
    } elseif (request()->routeIs('admin.settings.seo')) {
        $guide = [
            'icon' => 'search',
            'title' => 'Panduan SEO',
            'intro' => 'SEO mengatur informasi yang dibaca mesin pencari dan saat halaman dibagikan ke media sosial.',
            'steps' => [
                'Isi Meta Title dan Meta Description dengan ringkas dan sesuai isi website.',
                'Atur Canonical URL dan Robots hanya jika memahami dampaknya pada indeks mesin pencari.',
                'Upload gambar Open Graph dan Twitter agar tautan website memiliki preview yang baik.',
                'Klik Simpan Pengaturan SEO setelah selesai mengubah data.',
            ],
            'tip' => 'Meta title sebaiknya sekitar 50-60 karakter dan description sekitar 120-160 karakter.',
        ];
    } elseif (request()->routeIs('admin.settings.*')) {
        $guide = [
            'icon' => 'gear',
            'title' => 'Panduan Pengaturan',
            'intro' => 'Gunakan pengaturan untuk mengelola identitas, kontak, logo, favicon, media sosial, dan fitur order.',
            'steps' => [
                'Pada Umum, ubah nama perusahaan, logo, favicon, dan pengaturan dasar website.',
                'Pada Kontak & Lokasi, atur alamat, nomor telepon, email, dan peta.',
                'Pada Order via WA, atur nomor tujuan dan template pesan pesanan.',
                'Klik Simpan Semua Pengaturan setelah selesai mengubah data.',
            ],
            'tip' => 'Logo bersifat opsional. Jika tidak diisi, nama perusahaan akan tampil sebagai pengganti logo.',
        ];
    } elseif (request()->routeIs('admin.profile.*')) {
        $guide = [
            'icon' => 'person-gear',
            'title' => 'Panduan Profil Admin',
            'intro' => 'Gunakan halaman ini untuk memperbarui identitas dan keamanan akun admin.',
            'steps' => [
                'Perbarui nama dan email agar informasi akun selalu benar.',
                'Gunakan password baru yang panjang dan tidak dipakai di layanan lain.',
                'Simpan perubahan setelah semua data diperiksa.',
            ],
            'tip' => 'Jangan membagikan password admin kepada pengguna lain.',
        ];
    }
@endphp

@if($guide)
<div class="admin-feature-guide mb-4">
    <div class="admin-feature-guide__header">
        <div class="d-flex align-items-start gap-3">
            <span class="admin-feature-guide__icon"><i class="bi bi-{{ $guide['icon'] }}"></i></span>
            <div>
                <h5 class="mb-1">{{ $guide['title'] }}</h5>
                <p class="mb-0">{{ $guide['intro'] }}</p>
            </div>
        </div>
        <button class="btn btn-sm btn-outline-primary admin-feature-guide__toggle" type="button" data-bs-toggle="collapse" data-bs-target="#adminFeatureGuide" aria-expanded="true" aria-controls="adminFeatureGuide">
            <i class="bi bi-question-circle me-1"></i><span>Panduan</span>
        </button>
    </div>
    <div class="collapse show" id="adminFeatureGuide">
        <div class="admin-feature-guide__body">
            <div class="row g-3">
                <div class="col-lg-8">
                    <ol class="admin-feature-guide__steps mb-0">
                        @foreach($guide['steps'] as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </div>
                <div class="col-lg-4">
                    <div class="admin-feature-guide__tip">
                        <i class="bi bi-lightbulb me-2"></i><strong>Tips:</strong> {{ $guide['tip'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
