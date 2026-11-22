<?php

use Database\Seeders\ProductCatalog2026Seeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Terapkan katalog barang Nov 2026 (160 produk, varian, kategori baru, deskripsi) cukup lewat
 * `php artisan migrate` (dijalankan deploy.sh). Migration hanya berjalan SEKALI per database.
 *
 * - Sebelum mengubah apa pun, data produk/kategori/varian lama disimpan ke storage/app/backups/*.json
 * - Instalasi baru (database masih kosong) dilewati: katalog diisi oleh `php artisan db:seed`
 * - Menghapus file statis lama public/robots.txt & public/sitemap.xml yang menutupi versi dinamis
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->removeStaleStaticFiles();

        $isFreshInstall = DB::table('categories')->count() === 0 && DB::table('products')->count() === 0;
        if ($isFreshInstall) {
            return;
        }

        $this->backup();

        app(ProductCatalog2026Seeder::class)->setContainer(app())->__invoke();
    }

    public function down(): void
    {
        // Tidak dibalik otomatis. Data sebelum migration ada di storage/app/backups/catalog-before-2026-*.json
    }

    private function removeStaleStaticFiles(): void
    {
        foreach (['robots.txt', 'sitemap.xml'] as $file) {
            $path = public_path($file);
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    private function backup(): void
    {
        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $data = [];
        foreach (['categories', 'products', 'product_variants'] as $table) {
            if (Schema::hasTable($table)) {
                $data[$table] = DB::table($table)->get();
            }
        }

        File::put(
            $dir . '/catalog-before-2026-' . now()->format('Ymd-His') . '.json',
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
};
