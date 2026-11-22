<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ganti kategori "Tanah Liat" menjadi "Clay" (slug tanah-liat -> clay) langsung lewat `php artisan migrate`,
 * tanpa perlu menjalankan seeder. URL lama /categories/tanah-liat dialihkan ke /categories/clay (routes/web.php).
 * Aman dijalankan di database mana pun: bila kategori lama tidak ada, tidak ada yang berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $legacy = DB::table('categories')->where('slug', 'tanah-liat')->first();
        $clay = DB::table('categories')->where('slug', 'clay')->first();

        if ($legacy && $clay) {
            // Keduanya ada: pindahkan produk ke "clay", lalu hapus kategori lama
            DB::table('products')->where('category_id', $legacy->id)->update(['category_id' => $clay->id, 'updated_at' => $now]);
            DB::table('categories')->where('id', $legacy->id)->delete();
        } elseif ($legacy) {
            DB::table('categories')->where('id', $legacy->id)->update(['slug' => 'clay', 'updated_at' => $now]);
        }

        DB::table('categories')->where('slug', 'clay')->update([
            'name' => 'Clay',
            'name_en' => 'Clay',
            'description' => 'Clay untuk membuat kerajinan tangan dan karya 3D',
            'description_en' => 'Clay for handmade crafts and 3D artwork',
            'updated_at' => $now,
        ]);

        // Teks lain yang masih menyebut "tanah liat"
        $this->replaceIn('about_us', ['description', 'description_en', 'content', 'content_en'], [
            'Kami memilih setiap produk dengan teliti—dari cat air, cat minyak, kuas, kanvas, hingga tanah liat dan perlengkapan clay—agar Anda bisa fokus berkreasi'
                => 'Kami memilih setiap produk dengan teliti, mulai dari cat air, cat akrilik, kuas, kanvas, clay, sampai paket DIY, agar Anda bisa fokus berkreasi',
            'Kami memilih setiap produk dengan teliti—dari cat air, cat minyak, kuas, kanvas, hingga clay dan perlengkapannya—agar Anda bisa fokus berkreasi'
                => 'Kami memilih setiap produk dengan teliti, mulai dari cat air, cat akrilik, kuas, kanvas, clay, sampai paket DIY, agar Anda bisa fokus berkreasi',
            'We carefully select every product—from watercolors, oil paints, brushes, canvas, to clay and clay supplies—so you can focus on creating'
                => 'We carefully select every product, from watercolours and acrylic paints to brushes, canvas, clay and DIY kits, so you can focus on creating',
            'hingga tanah liat dan perlengkapan clay' => 'hingga clay dan perlengkapannya',
            'Tanah Liat' => 'Clay',
            'tanah liat' => 'clay',
        ]);
        $this->replaceIn('products', ['name', 'description', 'short_description', 'meta_description'], [
            'seperti tanah liat bakar' => 'seperti gerabah',
            'maupun tanah liat biasa' => 'maupun jenis clay lainnya',
            'Tanah Liat' => 'Clay',
            'tanah liat' => 'clay',
        ]);

        // Meta keywords: hapus "tanah liat" (sudah ada kata "clay")
        foreach (DB::table('settings')->where('key', 'meta_keywords')->where('value', 'like', '%tanah liat%')->get(['id', 'value']) as $row) {
            $value = trim(preg_replace('/\s*,?\s*tanah liat\s*/i', ', ', $row->value), ', ');
            DB::table('settings')->where('id', $row->id)->update(['value' => preg_replace('/(,\s*)+/', ', ', $value), 'updated_at' => $now]);
        }

        // Cache berisi nama kategori lama (beranda, modal order, sitemap)
        foreach (['order_modal_products_id', 'order_modal_products_en', 'sitemap_xml'] as $key) {
            Cache::forget($key);
        }
        foreach ([3, 6, 9] as $limit) {
            Cache::forget("featured_products_{$limit}");
        }
    }

    public function down(): void
    {
        // Hanya mengembalikan nama & slug kategori (teks lain dibiarkan)
        DB::table('categories')->where('slug', 'clay')->update([
            'name' => 'Tanah Liat',
            'slug' => 'tanah-liat',
            'description' => 'Tanah liat berkualitas untuk kerajinan clay',
            'updated_at' => now(),
        ]);
    }

    /** Ganti teks di kolom tertentu (hanya kolom yang ada & baris yang memang mengandung teks lama). */
    private function replaceIn(string $table, array $columns, array $pairs): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }
        $columns = array_values(array_filter($columns, fn ($c) => Schema::hasColumn($table, $c)));
        if (!$columns) {
            return;
        }

        $query = DB::table($table)->select(array_merge(['id'], $columns));
        $query->where(function ($q) use ($columns, $pairs) {
            foreach ($columns as $col) {
                foreach (array_keys($pairs) as $needle) {
                    $q->orWhere($col, 'like', '%' . $needle . '%');
                }
            }
        });

        foreach ($query->get() as $row) {
            $changes = [];
            foreach ($columns as $col) {
                if ($row->$col === null) {
                    continue; // NULL tetap NULL
                }
                $new = strtr($row->$col, $pairs);
                if ($new !== $row->$col) {
                    $changes[$col] = $new;
                }
            }
            if ($changes) {
                DB::table($table)->where('id', $row->id)->update($changes);
            }
        }
    }
};
