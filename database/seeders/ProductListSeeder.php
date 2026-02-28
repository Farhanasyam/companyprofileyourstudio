<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder untuk 98 produk dari daftar nama. Setiap produk masuk ke kategori yang ada di database.
     */
    public function run(): void
    {
        $categoriesBySlug = Category::orderBy('sort_order')->get()->keyBy('slug');
        $defaultCategory = Category::orderBy('sort_order')->first();

        if (!$defaultCategory) {
            $this->command->error('Tidak ada kategori di database. Jalankan CategoriesSeeder dulu: php artisan db:seed --class=CategoriesSeeder');
            return;
        }

        $names = [
            'Clay White 250 Gram',
            'Clay White 500 Gram',
            'Clay White 1000 Gram',
            'Clay Teracotta 500 Gram',
            'Clay Teracotta 1000 Gram',
            'Clay Wood 350 Gram',
            'Clay Wood 700 Gram',
            'Clay Stone 1000 Gram',
            'Foam Clay',
            'Kuas Datar',
            'Kuas Runcing',
            'Kuas isi 10',
            'Amplas',
            'Cat Pastel',
            'Cat Basic',
            'Alat Ukir isi 14',
            'Alat Ukir isi 8',
            'Akrilik Bulat',
            'Vernish 30 ml',
            'Vernish 100ml',
            'Cutter Knife',
            'Cutter Knife + Refill',
            'Scrapper Motif',
            'Spackling Paste',
            'Sponge',
            'Cat Akrilik-Dearbrick',
            'Roller',
            'Scrapper',
            'Kanvas Bulat 15 cm',
            'Kanvas Bulat 20 cm',
            'Kanvas Bulat 25 cm',
            'Kanvas Persegi 15x20',
            'Kanvas Persegi 20x20',
            'Kanvas Persegi 20x25',
            'Kanvas Persegi 20x30',
            'Kanvas Persegi 30x30',
            'Palete Knive Sentiliza S',
            'Palete Knive Sentiliza M',
            'Palete Knive Sentiliza L',
            'Palete Knive Persegi M',
            'Cat Akrilik Basic',
            'Cat Akrilik Pastel',
            'Pinset',
            'Pengait Puku',
            'Ring Keychain S',
            'Ring Keychain M',
            'Sambungan Keychain',
            'Mirror',
            'Coaster Bulat, Pita',
            'Coaster Bulat, Bintang',
            'Coaster Abstrak Cloud',
            'Palet Lukis Mini',
            'Kanvas Mont Marte Easel',
            'Kanvas Panel 10x10 isi 5',
            'Kanvas Panel 12x17 isi 3',
            'Kanvas Panel 20x25 isi 2',
            'Kanvas Panel 20x20 with standing board',
            'Varnish VTCC 100ml',
            'Varnish Reeves 75ml',
            'Giotto Acquerelli',
            'Canson Kertas A6',
            'Canson Kertas A4',
            'Acrylic Marker 12',
            'Acrylic Marker 24',
            'Cat Akrilik 33 Set',
            'Journalling set blue',
            'Journalling set pink',
            'Journalling set purple',
            'Capybara magic',
            'Capybara super',
            'Capybara Notebook',
            'Capybara stickynotes orange',
            'Capybara stickynotes sleep',
            'Capybara StickyNotes Book',
            'Pencilcase brown',
            'Pencilcase white',
            'Acrylic Pad A5',
            'Watercolour Pad A5',
            'Watercolour Pad A4',
            'VTEC Cat Akrilik 5ml x 12 warna',
            'VTEC Cat Akrilik 35ml x 6 warna',
            'Totebag',
            'Frame',
            'Acrylic Marker 36',
            'Stiker',
            'Bag Gift Capybara',
            'Notes Capybara',
            'Cutting Mat A3',
            'Cutting Mat A4',
            'Cutting Mat A5',
            'Premium Marker 12',
            'Premium Marker 24',
            'Premium Marker 36',
            'Paint By Number',
            'Easel',
            'Alat Ukir isi 10',
            'VTEC Kuas 101 Kasar',
            'VTEC Kuas 102 Halus',
        ];

        foreach ($names as $index => $name) {
            $productSlug = Str::slug($name);
            $baseSlug = $productSlug;
            $counter = 0;
            while (Product::where('slug', $productSlug)->exists()) {
                $counter++;
                $productSlug = $baseSlug . '-' . $counter;
            }

            $categorySlug = $this->resolveCategorySlug($name);
            $category = $categoriesBySlug->get($categorySlug) ?? $defaultCategory;

            Product::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => $productSlug,
                'description' => 'Produk ' . $name . ' tersedia di toko kami. Kunjungi toko untuk informasi harga dan stok.',
                'short_description' => $name,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => $index + 1,
            ]);
        }

        $this->command->info('ProductListSeeder: ' . count($names) . ' produk berhasil dibuat ke kategori yang ada di database.');
    }

    /**
     * Tentukan slug kategori berdasarkan nama produk (sesuai CategoriesSeeder).
     */
    private function resolveCategorySlug(string $name): string
    {
        $n = mb_strtolower($name);

        // Tanah Liat: clay, foam clay
        if (str_contains($n, 'clay') && !str_contains($n, 'foam')) {
            return 'tanah-liat';
        }
        if (str_contains($n, 'foam clay')) {
            return 'tanah-liat';
        }

        // Kanvas: kanvas, canvas, panel
        if (str_contains($n, 'kanvas') || str_contains($n, 'canvas')) {
            return 'kanvas';
        }

        // Kuas
        if (str_contains($n, 'kuas')) {
            return 'kuas';
        }

        // Palet
        if (str_contains($n, 'palet') || str_contains($n, 'palete knive')) {
            return 'palet';
        }

        // Easel (standalone)
        if ($n === 'easel') {
            return 'easel';
        }

        // Cat / cat air / akrilik / marker cat / watercolour
        if (str_contains($n, 'cat ') || str_contains($n, 'cat akrilik') || str_contains($n, 'akrilik')
            || str_contains($n, 'acrylic marker') || str_contains($n, 'premium marker')
            || str_contains($n, 'watercolour pad') || str_contains($n, 'acrylic pad')
            || str_contains($n, 'giotto') || str_contains($n, 'canson kertas')
            || str_contains($n, 'paint by number') || str_contains($n, 'vtec cat')) {
            return 'cat-air';
        }

        // Finishing: vernish, varnish
        if (str_contains($n, 'vernish') || str_contains($n, 'varnish')) {
            return 'finishing-clay';
        }

        // Alat Pembentuk: alat ukir, amplas, cutter, scrapper, roller, pinset, pengait, spackling, sponge
        if (str_contains($n, 'alat ukir') || str_contains($n, 'amplas') || str_contains($n, 'cutter')
            || str_contains($n, 'scrapper') || str_contains($n, 'roller') || str_contains($n, 'pinset')
            || str_contains($n, 'pengait') || str_contains($n, 'spackling') || (str_contains($n, 'sponge') && !str_contains($n, 'pad'))) {
            return 'alat-pembentuk';
        }

        // Aksesoris: keychain, coaster, mirror, totebag, frame, stiker, gift, notes, pencilcase, cutting mat, journalling, capybara, pad (paper)
        if (str_contains($n, 'keychain') || str_contains($n, 'coaster') || str_contains($n, 'mirror')
            || str_contains($n, 'totebag') || str_contains($n, 'frame') || str_contains($n, 'stiker')
            || str_contains($n, 'bag gift') || str_contains($n, 'notes ') || str_contains($n, 'pencilcase')
            || str_contains($n, 'cutting mat') || str_contains($n, 'journalling') || str_contains($n, 'capybara')
            || str_contains($n, 'stickynotes') || str_contains($n, 'notebook')) {
            return 'aksesoris-clay';
        }

        return 'aksesoris-clay'; // fallback
    }
}
