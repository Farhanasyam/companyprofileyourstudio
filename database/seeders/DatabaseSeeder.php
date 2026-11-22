<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            SeoSettingsSeeder::class,
            CategoriesSeeder::class,
            EventSeeder::class,
            EventDataSeeder::class,
            AboutUsSeeder::class,
            MultilingualDataSeeder::class, // contoh event & artikel (produk contohnya dibersihkan di bawah)
            // Katalog resmi (daftar barang Nov 2026) + deskripsi. Harus paling akhir: produk & kategori di luar
            // daftar (termasuk dari seeder lama ProductListSeeder/MultilingualDataSeeder) dihapus di sini.
            ProductCatalog2026Seeder::class,
        ]);
    }
}
