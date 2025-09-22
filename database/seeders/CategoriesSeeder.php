<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Kategori Alat Lukis
            [
                'name' => 'Cat Air',
                'slug' => 'cat-air',
                'description' => 'Berbagai jenis cat air berkualitas tinggi untuk melukis',
                'sort_order' => 1,
            ],
            [
                'name' => 'Cat Minyak',
                'slug' => 'cat-minyak',
                'description' => 'Cat minyak profesional untuk karya seni yang tahan lama',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kuas',
                'slug' => 'kuas',
                'description' => 'Kuas lukis berbagai ukuran dan jenis bulu',
                'sort_order' => 3,
            ],
            [
                'name' => 'Kanvas',
                'slug' => 'kanvas',
                'description' => 'Kanvas lukis berbagai ukuran dan kualitas',
                'sort_order' => 4,
            ],
            [
                'name' => 'Easel',
                'slug' => 'easel',
                'description' => 'Easel untuk memudahkan proses melukis',
                'sort_order' => 5,
            ],
            [
                'name' => 'Palet',
                'slug' => 'palet',
                'description' => 'Palet untuk mencampur cat',
                'sort_order' => 6,
            ],
            
            // Kategori Clay
            [
                'name' => 'Tanah Liat',
                'slug' => 'tanah-liat',
                'description' => 'Tanah liat berkualitas untuk kerajinan clay',
                'sort_order' => 7,
            ],
            [
                'name' => 'Alat Pembentuk',
                'slug' => 'alat-pembentuk',
                'description' => 'Berbagai alat untuk membentuk clay',
                'sort_order' => 8,
            ],
            [
                'name' => 'Oven Clay',
                'slug' => 'oven-clay',
                'description' => 'Oven khusus untuk memanggang clay',
                'sort_order' => 9,
            ],
            [
                'name' => 'Finishing Clay',
                'slug' => 'finishing-clay',
                'description' => 'Produk finishing untuk hasil clay yang sempurna',
                'sort_order' => 10,
            ],
            [
                'name' => 'Aksesoris Clay',
                'slug' => 'aksesoris-clay',
                'description' => 'Aksesoris dan perlengkapan tambahan untuk clay',
                'sort_order' => 11,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
