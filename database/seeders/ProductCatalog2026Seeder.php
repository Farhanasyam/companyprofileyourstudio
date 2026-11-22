<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Katalog produk dari daftar barang YourStudio (November 2026), TANPA harga.
 *
 * - Barang dengan nama sama (beda SKU/varian) digabung jadi satu produk; SKU dicatat di spesifikasi.
 * - Produk dicocokkan lewat slug: produk lama dengan slug sama diperbarui (gambar & link marketplace tetap).
 * - Produk & kategori yang TIDAK ada di daftar ini DIHAPUS PERMANEN (beserta file gambar/videonya),
 *   sehingga katalog hanya berisi barang dari daftar.
 *   PERINGATAN: menjalankan ulang seeder ini juga akan menghapus produk yang ditambahkan lewat admin
 *   setelahnya. Jalankan sekali saja: php artisan db:seed --class=ProductCatalog2026Seeder
 */
class ProductCatalog2026Seeder extends Seeder
{
    /** Kategori di daftar barang => slug kategori di website */
    private const CATEGORY_MAP = [
        'Aksesoris Clay' => 'aksesoris-clay',
        'Cat Akrilik' => 'cat-akrilik',
        'Alat Pembentuk' => 'alat-pembentuk',
        'Finishing Clay' => 'finishing-clay',
        'Kuas' => 'kuas',
        'Cat Air' => 'cat-air',
        'Clay' => 'clay',
        'Easel' => 'easel',
        'Kanvas' => 'kanvas',
        'Palet' => 'palet',
        'Paket Bundling' => 'paket-bundling',
        'Alat Tulis & Journaling' => 'alat-tulis-journaling',
        'Stiker DIY' => 'stiker-diy',
    ];

    /** Kategori baru yang belum ada di website */
    private const NEW_CATEGORIES = [
        'alat-tulis-journaling' => ['name' => 'Alat Tulis & Journaling', 'name_en' => 'Stationery & Journaling', 'description' => 'Marker, pensil gambar, notebook, sticky notes, dan perlengkapan journaling', 'description_en' => 'Markers, drawing pencils, notebooks, sticky notes and journaling supplies'],
        'stiker-diy' => ['name' => 'Stiker DIY', 'name_en' => 'DIY Stickers', 'description' => 'Stiker ruangan 3D dan stiker dekorasi untuk dirakit sendiri', 'description_en' => '3D room stickers and decorative stickers to assemble yourself'],
        'cat-akrilik' => ['name' => 'Cat Akrilik', 'name_en' => 'Acrylic Paint', 'description' => 'Cat akrilik untuk melukis di berbagai media', 'description_en' => 'Acrylic paint for painting on various surfaces'],
        'paket-bundling' => ['name' => 'Paket Bundling', 'name_en' => 'DIY Bundles', 'description' => 'Paket DIY bundling', 'description_en' => 'DIY bundle kits'],
    ];

    /** Deskripsi singkat per kategori (ID, EN) */
    private const CATEGORY_COPY = [
        'aksesoris-clay' => ['Alat bantu untuk membuat dan merapikan karya clay.', 'Helpful tools for making and tidying clay projects.'],
        'alat-tulis-journaling' => ['Alat tulis dan perlengkapan journaling untuk menggambar dan mencatat.', 'Stationery and journaling supplies for drawing and note-taking.'],
        'stiker-diy' => ['Stiker DIY untuk dirakit dan menghias ruangan atau barang.', 'DIY stickers to assemble and decorate rooms or items.'],
        'cat-akrilik' => ['Cat akrilik untuk melukis di kanvas, kayu, clay, dan berbagai media.', 'Acrylic paint for canvas, wood, clay and other surfaces.'],
        'alat-pembentuk' => ['Alat untuk membentuk, mengukir, dan merapikan clay.', 'Tools for shaping, carving and smoothing clay.'],
        'finishing-clay' => ['Perlengkapan finishing agar karya lebih rapi, mengilap, dan awet.', 'Finishing supplies for neat, glossy, long-lasting artwork.'],
        'kuas' => ['Kuas untuk melukis dengan cat air maupun cat akrilik.', 'Brushes for watercolor and acrylic painting.'],
        'cat-air' => ['Perlengkapan melukis cat air untuk pemula hingga mahir.', 'Watercolor supplies for beginners to advanced artists.'],
        'clay' => ['Clay untuk membuat kerajinan tangan dan karya 3D.', 'Clay for handmade crafts and 3D artwork.'],
        'easel' => ['Penyangga kanvas untuk melukis dan memajang karya.', 'Stand for painting and displaying your canvas.'],
        'kanvas' => ['Kanvas siap pakai untuk melukis dan berkarya.', 'Ready-to-use canvas for painting.'],
        'palet' => ['Palet dan pisau palet untuk mencampur dan mengaplikasikan cat.', 'Palettes and palette knives for mixing and applying paint.'],
        'paket-bundling' => ['Paket DIY lengkap, siap untuk langsung berkreasi.', 'Complete DIY kit, ready for you to start creating.'],
    ];

    /**
     * Daftar barang: "Kategori | Nama barang | SKU[, SKU varian lain]" (sesuai file harga_barang_2026_11.sql).
     */
    private const ITEMS = <<<'TXT'
Alat Tulis & Journaling | Acrylic Marker Enmy Isi 12 | PREMIUMACM-ISI12
Alat Tulis & Journaling | Acrylic Marker Enmy Isi 24 | PREMIUMACM-ISI24
Alat Tulis & Journaling | Acrylic Marker Enmy Isi 36 | PREMIUMACM-ISI36
Alat Tulis & Journaling | Acrylic Marker Isi 12 | ACM-ISI12
Alat Tulis & Journaling | Acrylic Marker Isi 24 | ACM-ISI24
Alat Tulis & Journaling | Acrylic Marker Isi 36 | ACM-ISI36
Cat Akrilik | Acrylic Pad Giorgione | AP-GIOR-200G
Alat Pembentuk | Alat Ukir Isi 10 | AUKR-ISI10
Alat Pembentuk | Alat Ukir Isi 14 | AUKR-ISI14
Alat Pembentuk | Alat Ukir Isi 8 | AUKR-ISI8
Finishing Clay | Amplas Grit 1500 | AMPLAS
Alat Tulis & Journaling | Bag Gift Capybara | BAG-CAPYBARA
Alat Tulis & Journaling | Ballpoint Karakter | Y1067
Alat Tulis & Journaling | Ballpoint Karakter Pouch | Y1068
Kuas | Brush Watercolor - Datar | WCBRUSH-FLAT
Kuas | Brush Watercolor - Runcing | WCBRUSH-POINT
Cat Air | Cat Air VTEC 12X3ml | CAS-VTEC-WC-AIR
Cat Akrilik | Cat Akrilik Basic 6x3ml | CAS-BASIC-3ML
Cat Akrilik | Cat Akrilik Pastel 6x3ml | CAS-PASTEL-3ML
Cat Akrilik | Cat Akrilik Set Isi 33 | CAS-YS-ISI33
Cat Akrilik | Cat Akrilik VTEC 12x3ml | CAS-VTEC-MINI-3ML
Cat Akrilik | Cat Akrilik VTEC 6x35ml | CAS-VTEC-35ML
Cat Akrilik | Cat Akrilik VTEC Basic 6x12ml | CAS-VTEC-BASIC
Cat Akrilik | Cat Akrilik VTEC Pastel 6x12ml | CAS-VTEC-PASTEL
Cat Akrilik | Cat Akrilik VTEC Set 12x5ml | CAS-VTEC-5ML
Aksesoris Clay | Cetakan Akrilik 10cm | CETAKAN-AKR
Clay | Clay Das BLACK 700gr | CDAS-BLACK-700
Clay | Clay DAS Stone 1000gr | CDAS-STONE-1000
Clay | Clay DAS Terracotta 1000gr | CDAS-TERA-1000
Clay | Clay DAS Terracotta 500gr | CDAS-TERA-500
Clay | Clay DAS White 1000gr | CDAS-WHITE-1000
Clay | Clay DAS White 250gr | CDAS-WHITE-250
Clay | Clay DAS White 500gr | CDAS-WHITE-500
Clay | Clay DAS Wood 350gr | CDAS-WOOD-350
Clay | Clay DAS Wood 700gr | CDAS-WOOD-700
Alat Tulis & Journaling | Color Wheels 14cm | COLWHEEL14CM-MINI
Alat Tulis & Journaling | Color Wheels 24cm | COLWHEEL24CM-BIG
Alat Pembentuk | Cukil Isi 3 | CUKIL-ISI3
Alat Pembentuk | Cukil Isi 6 | CUKIL-ISI6
Aksesoris Clay | Cutter Knife | CKA-CUTTER
Aksesoris Clay | Cutter Knife + Refill Isi 5 | CKA-PEN-REFILL
Aksesoris Clay | Cutting Mat A3 | CUTMAT-A3-HIJAU
Aksesoris Clay | Cutting Mat A4 | CUTMAT-A4-HIJAU
Aksesoris Clay | Cutting Mat A5 | CUTMAT-A5-HIJAU
Alat Tulis & Journaling | Drawing Pencil Maries 2B | DPS-2B
Alat Tulis & Journaling | Drawing Pencil Maries 2H | DPS-2H
Alat Tulis & Journaling | Drawing Pencil Maries 3B | DPS-3B
Alat Tulis & Journaling | Drawing Pencil Maries 4B | DPS-4B
Alat Tulis & Journaling | Drawing Pencil Maries 5B | DPS-5B
Alat Tulis & Journaling | Drawing Pencil Maries 6B | DPS-6B
Alat Tulis & Journaling | Drawing Pencil Maries 7B | DPS-7B
Alat Tulis & Journaling | Drawing Pencil Maries 8B | DPS-8B
Alat Tulis & Journaling | Drawing Pencil Maries B | DPS-B
Alat Tulis & Journaling | Drawing Pencil Maries HB | DPS-HB
Easel | Easel Kayu Mini | PBN-EASEL
Clay | Foam Clay Jumbo | FC-CLAY-JUMBO
Clay | Foam Clay Medium | FC-CLAY-BESAR
Clay | Foam Clay Mini | FC-CLAY-KECIL
Cat Air | Giotto Acquerelli | CA-GIOTTO-ISI12
Alat Tulis & Journaling | Journalling Capybara - Magic | DIY-JK-MAGIC
Alat Tulis & Journaling | Journalling Capybara - Notebook | DIY-JK-DANCE
Alat Tulis & Journaling | Journalling Capybara Super | DIY-JK-MARIO
Alat Tulis & Journaling | Journalling Set - Blue | DIY-JK-BLUE
Alat Tulis & Journaling | Journalling Set - Pink | DIY-JK-PINK
Alat Tulis & Journaling | Journalling Set - Purple | DIY-JK-PURPLE
Kanvas | Kanvas Bulat 15 cm | KH-RN-15CM
Kanvas | Kanvas Bulat 20 cm | KH-RN-20CM
Kanvas | Kanvas Bulat 25 cm | KH-RN-25CM
Kanvas | Kanvas Mont Marte Easel 10cm | KM-MINI-DISPLAY
Kanvas | Kanvas Mont Marte Standing 20x20cm | KP-MM-STANDING
Kanvas | Kanvas Panel 10x10cm Isi 5 | KP-MM-ISI5
Kanvas | Kanvas Panel 12x17cm Isi 3 | KP-MM-ISI3
Kanvas | Kanvas Panel 20x25cm Isi 2 | KP-MM-ISI2
Kanvas | Kanvas Persegi 15x20cm | KH-SQ-15X20CM
Kanvas | Kanvas Persegi 20x20cm | KH-SQ-20X20CM
Kanvas | Kanvas Persegi 20x25cm | KH-SQ-20X25CM
Kanvas | Kanvas Persegi 20x30cm | KH-SQ-20x30CM
Kanvas | Kanvas Persegi 30x30cm | KH-SQ-30X30CM
Kuas | Kuas Datar 16cm | KC-YS-DATAR
Kuas | Kuas Nylon Multifungsi Isi 10 | KUAS-MF-ISI10
Kuas | Kuas Runcing 16cm | KC-YS-RUNCING
Kuas | Kuas VTEC 101 Kokoh Isi 6 | KUAS-VTEC-ISI6
Kuas | Kuas VTEC 102 Halus Isi 6 | KUAS-VTEC-ISI6-HALUS
Alat Tulis & Journaling | Marker Pad Mont Marte A5 | MARKERPAD
Aksesoris Clay | Mirror Standard | PM-STANDART-MIRROR
Aksesoris Clay | Modelling Schoolplast LYRA Isi 10 | MCS-LYRA
Alat Tulis & Journaling | Notebook Capybara | NOTES-CAPY-BLUE, NOTES-CAPY-BROWN, NOTES-CAPY-PINK, NOTES-CAPY-PURPLE
Alat Tulis & Journaling | Notes Mini Renceng | NOTESMINI-A, NOTESMINI-B
Kanvas | Paint By Number 20x20cm | PBN-F.1, PBN-F.2, PBN-F.3, PBN-F.4, PBN-F.5, PBN-F.6, PBN-F.7, PBN-K.1, PBN-K.2, PBN-K.3, PBN-K.4, PBN-K.5, PBN-K.6, PBN-K.7, PBN-K.8, PBN-K.9, PBN-K.10, PBN-S.1, PBN-S.2, PBN-S.3, PBN-S.4, PBN-S.5, PBN-S.6, PBN-S.7, PBN-S.8, PBN-S.9, PBN-S.10, PBN-S.11, PBN-S.13, PBN-S.14
Palet | Palet Lukis Mini | PL-YS-MINI
Palet | Palette Knife No.1 Trapesium | PK-T-NO1
Palet | Palette Knife No.2 Runcing | PK-S-NO2
Palet | Palette Knife No.3 Oval | PK-S-NO3
Palet | Palette Knife No.4 Segitiga M | PK-S-NO4
Palet | Palette Knife No.5 Segitiga L | PK-S-NO5
Palet | Palette Knife No.6 Segitiga XL | PK-S-NO6
Palet | Palette Knife No.7 Persegi | PK-P-NO7
Alat Tulis & Journaling | Pencil Case Capybara - Brown | PC-CAPY-BROWN
Alat Tulis & Journaling | Pencil Case Capybara - White | PC-CAPY-CREAM
Alat Pembentuk | Pottery Isi 8 | POTOOLS-ISI8
Aksesoris Clay | Rolling Pin Clay 16cm | ROLLINGPIN
Aksesoris Clay | Scrapper | SCRAPPER
Aksesoris Clay | Scrapper Motif | SCRAPPER-MOTIF
Aksesoris Clay | Spackling Paste 250gr | SPACKLING
Aksesoris Clay | Sponge 10cm | SPONGE
Stiker DIY | Sticker Your Studio | STICKER-YS
Alat Tulis & Journaling | Sticky Notes Capybara - Book | SN-CAPY-EAT
Alat Tulis & Journaling | Sticky Notes Capybara - Orange | SN-CAPY-STUDY
Alat Tulis & Journaling | Sticky Notes Capybara - Sleep | SN-CAPY-SLEEP
Finishing Clay | Varnish Reeves 75ml | VG-REEVES-75ML
Finishing Clay | Varnish VTEC 100ml | AMG-VTEC-100ML
Finishing Clay | Varnish YS 100ml | VG-YS-100ML
Finishing Clay | Varnish YS 30ml | VG-YS-30ML
Cat Air | Watercolor Paper Canson - A4 | WP-CN-A4
Cat Air | Watercolor Paper Canson - A5 | WP-CN-A5
Cat Air | Watercolor Paper Canson - A6 | WP-CN-A6
Cat Air | Watercolour Pad Giorgione A4 | WPGIOR-A4
Cat Air | Watercolour Pad Giorgione A5 | WPGIOR-A5
Paket Bundling | DIY Bearbrick Pouring - Keychain | BKEY-BEAR-A, BKEY-BEAR-B, BKEY-BEAR-C, BKEY-CINNA-A, BKEY-CINNA-B, BKEY-CINNA-C, BKEY-KAWS-A, BKEY-KAWS-B, BKEY-KAWS-C, BKEY-KUROMI-A, BKEY-KUROMI-B, BKEY-KUROMI-C, BKEY-LOTSO-A, BKEY-LOTSO-B, BKEY-LOTSO-C, BKEY-MELODY-A, BKEY-MELODY-B, BKEY-MELODY-C
Paket Bundling | DIY Bearbrick Pouring - Manekin | BKEY-MANEKIN-A, BKEY-MANEKIN-B, BKEY-MANEKIN-C
Paket Bundling | DIY Clay Keychain - Paket Fun (WHITE) 250 Gram | DIY-250-BASIC, DIY-250-PASTEL
Paket Bundling | DIY Clay Keychain - Paket Standard (WHITE) 500 Gram | DIY-500-BASIC, DIY-500-PASTEL
Paket Bundling | DIY Clay Kit (TERACOTTA) 1000 Gram | DIY-1000-TERA
Paket Bundling | DIY Clay Kit (TERACOTTA) 500 Gram | DIY-500-TERA
Paket Bundling | DIY Clay Kit (WHITE) 1000 Gram | DIY-1000-WHITE
Paket Bundling | DIY Clay Kit (WHITE) 250 Gram | DIY-250-WHITE
Paket Bundling | DIY Clay Kit (WHITE) 500 Gram | DIY-500-WHITE
Paket Bundling | DIY Coaster Painting - Coaster Abstrak Cloud | DIYCK-ABSTRACT-BASIC, DIYCK-ABSTRACT-PASTEL
Paket Bundling | DIY Coaster Painting - Coaster Bubble, Bintang | DIYCK-BINTANG-BASIC, DIYCK-BINTANG-PASTEL, DIYCK-BUBBLE-BASIC, DIYCK-BUBBLE-PASTEL
Paket Bundling | DIY Coaster Painting - Coaster Bulat, Pita | DIYCK-BULAT-BASIC, DIYCK-BULAT-PASTEL, DIYCK-PITA-BASIC, DIYCK-PITA-PASTEL
Paket Bundling | DIY Frame Clay - Persegi / Lingkaran | DIY-FC-FRAME
Paket Bundling | DIY Kanvas Painting - Kanvas 20x20 | PO-AWAN-BASIC, PO-AWAN-PASTEL, PO-BUNGA-BASIC, PO-BUNGA-PASTEL, PO-DONAT-BASIC, PO-DONAT-PASTEL
Paket Bundling | DIY Kanvas Painting - Kanvas 20x30 | PO-KELINCI-BASIC, PO-KELINCI-PASTEL, PO-MATAHARI-BASIC, PO-MATAHARI-PASTEL
Paket Bundling | DIY Kanvas Panels Painting - 10x10 | DIY-KPVTEC-ISI5
Paket Bundling | DIY Kanvas Panels Painting - 12x17 | DIY-KPVTEC-ISI3
Paket Bundling | DIY Kanvas Panels Painting - 20x25 | DIY-KPVTEC-ISI2
Paket Bundling | DIY Mirror Clay - Abstract | MC-ABSTRACT
Paket Bundling | DIY Mirror Clay - Standard | MC-OVAL
Paket Bundling | DIY Mirror Painting - Abstract | PM-ABSTRACT-BASIC, PM-ABSTRACT-PASTEL
Paket Bundling | DIY Mirror Painting - Standard | PM-STANDART-BASIC, PM-STANDART-PASTEL
Paket Bundling | DIY Totebag Painting - Motif | TP-KANTONG, TP-SERUT
Paket Bundling | DIY Totebag Painting - Polos | TP-POLOS
Stiker DIY | DIY Stiker Ruangan 3D - Bakery | 1-STYS-BROWN
Stiker DIY | DIY Stiker Ruangan 3D - Laundry Room | 1-STYS-GREEN
Stiker DIY | DIY Stiker Ruangan 3D - Home Party | 1-STYS-GREY
Stiker DIY | DIY Stiker Ruangan 3D - Supermarket | 1-STYS-RED
Stiker DIY | DIY Stiker Ruangan 3D - Restaurant | 2-STYS-BLUE
Stiker DIY | DIY Stiker Ruangan 3D - Capybara | 2-STYS-CAPYBARA
Stiker DIY | DIY Stiker Ruangan 3D - My Little Pony | 2-STYS-PINK
Stiker DIY | DIY Stiker Ruangan 3D - Bear Bakery | 2-STYS-YELLOW
Stiker DIY | DIY Stiker Ruangan 3D - Burger Shop | 3-STYS-BEIGE
Stiker DIY | DIY Stiker Ruangan 3D - Dessert Shop | 3-STYS-PASTEL
Stiker DIY | DIY Stiker Ruangan 3D - Library | 3-STYS-PURPLE
Stiker DIY | DIY Stiker Ruangan 3D - Tutu Mart | 3-STYS-SAGE
Stiker DIY | DIY Stiker Ruangan 3D - Bear Cafe | 4-STYS-BEAR4
Stiker DIY | DIY Stiker Ruangan 3D - Bear Restaurant | 4-STYS-BROWN4
Stiker DIY | DIY Stiker Ruangan 3D - Capybara Library | 4-STYS-CAPY4
Stiker DIY | DIY Stiker Ruangan 3D - Bear Home | 4-STYS-PINK4
Stiker DIY | DIY Stiker Ruangan 3D - Pinset Biru | STYS-PINSETBIRU
Stiker DIY | DIY Stiker Ruangan 3D - Pinset Pink | STYS-PINSETPINK
TXT;

    public function run(): void
    {
        DB::transaction(function () {
            $categories = $this->ensureCategories();
            $keptIds = [];
            $created = 0;
            $updated = 0;
            $variants = 0;

            foreach ($this->parseItems() as $index => $item) {
                $category = $categories[self::CATEGORY_MAP[$item['category']]];
                [$copyId, $copyEn] = self::CATEGORY_COPY[$category->slug];
                $skus = $item['skus'];
                $variants += count($skus);

                // Produk bervarian: SKU disimpan sebagai varian (tabel product_variants), bukan teks spesifikasi
                $specs = count($skus) > 1
                    ? ['Satuan' => 'pcs']
                    : ['SKU' => $skus[0], 'Satuan' => 'pcs'];

                $variantNoteId = count($skus) > 1 ? ' Tersedia ' . count($skus) . ' varian, pilih varian yang diinginkan sebelum memesan.' : '';
                $variantNoteEn = count($skus) > 1 ? ' Available in ' . count($skus) . ' variants, choose your variant before ordering.' : '';

                $product = Product::firstOrNew(['slug' => Str::slug($item['name'])]);
                $product->exists ? $updated++ : $created++;

                // Field yang dikelola admin (gambar, video, link marketplace, unggulan) tidak disentuh
                $product->fill([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    // Deskripsi singkat dikosongkan: teks umum kategori tidak ditampilkan berulang di kartu katalog.
                    // Isi lewat admin bila ada deskripsi khusus produk.
                    'short_description' => null,
                    'short_description_en' => null,
                    'description' => $item['name'] . '. ' . $copyId . $variantNoteId,
                    'description_en' => $item['name'] . '. ' . $copyEn . $variantNoteEn,
                    'specifications' => $specs,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'meta_title' => $item['name'] . ' - YourStudio',
                    'meta_description' => Str::limit($item['name'] . '. ' . $copyId, 155),
                    'meta_description_en' => Str::limit($item['name'] . '. ' . $copyEn, 155),
                ]);
                $product->save();
                $this->syncVariants($product, count($skus) > 1 ? $skus : []);
                $keptIds[] = $product->id;
            }

            // Ganti katalog: produk & kategori yang tidak ada di daftar dihapus permanen
            $deletedProducts = $this->deleteProductsExcept($keptIds);

            // Seeder lama mengisi gambar produk dengan placeholder logo (images/logo1.png), bukan foto produk.
            // Dikosongkan agar kartu menampilkan placeholder kategori; foto asli diunggah lewat admin.
            Product::where('image', 'images/logo1.png')->update(['image' => null]);
            Product::where('main_image', 'images/logo1.png')->update(['main_image' => null]);
            $deletedCategories = Category::whereNotIn('slug', array_values(self::CATEGORY_MAP))
                ->whereDoesntHave('products')
                ->delete();

            Product::clearPublicCaches();
            Cache::forget('sitemap_xml');

            $this->command?->info("Produk dibuat: {$created}, diperbarui: {$updated} (total {$variants} SKU). Dihapus: {$deletedProducts} produk lama, {$deletedCategories} kategori lama.");
        });

        // Deskripsi lengkap per produk (menimpa deskripsi umum di atas)
        $this->call(ProductDescriptionSeeder::class);
    }

    /**
     * Samakan varian produk dengan daftar kode. Varian yang sudah ada (dicocokkan lewat kode) dipertahankan
     * beserta nama & gambarnya; kode yang tidak ada lagi di daftar dihapus.
     */
    private function syncVariants(Product $product, array $codes): void
    {
        $existing = $product->variants()->get()->keyBy('code');
        foreach ($codes as $i => $code) {
            $variant = $existing->pull($code) ?? $product->variants()->make(['code' => $code]);
            $variant->sort_order = $i + 1;
            $variant->save();
        }
        $existing->each->delete();
    }

    /** Hapus produk di luar daftar beserta file media yang tidak dipakai produk lain. */
    private function deleteProductsExcept(array $keptIds): int
    {
        $mediaOf = fn (Product $p) => array_filter(array_merge(
            [$p->image, $p->main_image, $p->video],
            (array) ($p->images ?? []),
            (array) ($p->videos ?? [])
        ), fn ($path) => is_string($path) && $path !== '');

        $keptMedia = Product::whereIn('id', $keptIds)->get()->flatMap($mediaOf)->unique()->all();
        $oldProducts = Product::whereNotIn('id', $keptIds)->get();

        foreach ($oldProducts as $product) {
            foreach (array_diff($mediaOf($product), $keptMedia) as $path) {
                Storage::disk('public')->delete(ltrim($path, '/'));
            }
            $product->delete();
        }

        return $oldProducts->count();
    }

    /** @return array<string, Category> keyed by slug */
    private function ensureCategories(): array
    {
        // Kategori "Tanah Liat" (slug tanah-liat) diganti nama menjadi "Clay" (slug clay).
        // URL lama /categories/tanah-liat diarahkan ke /categories/clay di routes/web.php.
        $legacyClay = Category::where('slug', 'tanah-liat')->first();
        if ($legacyClay && !Category::where('slug', 'clay')->exists()) {
            $legacyClay->update(['slug' => 'clay']);
        }
        Category::where('slug', 'clay')->update([
            'name' => 'Clay',
            'name_en' => 'Clay',
            'description' => 'Clay untuk membuat kerajinan tangan dan karya 3D',
            'description_en' => 'Clay for handmade crafts and 3D artwork',
        ]);

        $maxOrder = (int) Category::max('sort_order');
        foreach (self::NEW_CATEGORIES as $slug => $attrs) {
            $category = Category::firstOrNew(['slug' => $slug]);
            if (!$category->exists) {
                $category->fill($attrs + ['is_active' => true, 'sort_order' => ++$maxOrder])->save();
            }
        }

        // Kategori dasar yang belum ada di database (mis. di hosting) dibuat otomatis
        foreach (self::CATEGORY_MAP as $name => $slug) {
            if (!Category::where('slug', $slug)->exists()) {
                Category::create(['name' => $name, 'slug' => $slug, 'is_active' => true, 'sort_order' => ++$maxOrder]);
            }
        }

        return Category::whereIn('slug', array_values(self::CATEGORY_MAP))->get()->keyBy('slug')->all();
    }

    /** @return array<int, array{category: string, name: string, skus: string[]}> */
    private function parseItems(): array
    {
        $items = [];
        foreach (preg_split('/\R/', trim(self::ITEMS)) as $line) {
            [$category, $name, $skus] = array_map('trim', explode('|', $line));
            $items[] = [
                'category' => $category,
                'name' => $name,
                'skus' => array_map('trim', explode(',', $skus)),
            ];
        }

        return $items;
    }
}
