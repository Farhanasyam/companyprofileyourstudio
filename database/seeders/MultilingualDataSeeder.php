<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Event;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

class MultilingualDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories with multilingual content
        $categories = [
            [
                'name' => 'Cat Air',
                'name_en' => 'Watercolor',
                'slug' => 'cat-air',
                'description' => 'Berbagai jenis cat air berkualitas tinggi untuk melukis',
                'description_en' => 'Various high-quality watercolor paints for painting',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kuas Lukis',
                'name_en' => 'Paint Brushes',
                'slug' => 'kuas-lukis',
                'description' => 'Kuas lukis dengan berbagai ukuran dan jenis bulu',
                'description_en' => 'Paint brushes with various sizes and bristle types',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kanvas',
                'name_en' => 'Canvas',
                'slug' => 'kanvas',
                'description' => 'Kanvas berkualitas tinggi untuk melukis',
                'description_en' => 'High-quality canvas for painting',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create products with multilingual content
        $watercolorCategory = Category::where('slug', 'cat-air')->first();
        $brushCategory = Category::where('slug', 'kuas-lukis')->first();
        $canvasCategory = Category::where('slug', 'kanvas')->first();

        $products = [
            [
                'category_id' => $watercolorCategory->id,
                'name' => 'Cat Air Winsor & Newton Professional',
                'name_en' => 'Winsor & Newton Professional Watercolor',
                'slug' => 'cat-air-winsor-newton-professional',
                'description' => 'Cat air profesional dengan pigmen berkualitas tinggi dan transparansi yang sempurna. Cocok untuk seniman profesional dan pemula yang serius.',
                'description_en' => 'Professional watercolor with high-quality pigments and perfect transparency. Suitable for professional artists and serious beginners.',
                'short_description' => 'Cat air profesional dengan pigmen berkualitas tinggi',
                'short_description_en' => 'Professional watercolor with high-quality pigments',
                'price' => 450000,
                'sku' => 'CAN-WN-001',
                'stock' => 50,
                'brand' => 'Winsor & Newton',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Cat Air Winsor & Newton Professional - YourStudio',
                'meta_title_en' => 'Winsor & Newton Professional Watercolor - YourStudio',
                'meta_description' => 'Cat air profesional Winsor & Newton dengan kualitas terbaik untuk seniman',
                'meta_description_en' => 'Professional Winsor & Newton watercolor with the best quality for artists',
            ],
            [
                'category_id' => $brushCategory->id,
                'name' => 'Kuas Sable Kolinsky #8',
                'name_en' => 'Kolinsky Sable Brush #8',
                'slug' => 'kuas-sable-kolinsky-8',
                'description' => 'Kuas sable kolinsky premium dengan kontrol yang sempurna dan daya serap yang optimal. Ideal untuk detail halus dan wash yang halus.',
                'description_en' => 'Premium kolinsky sable brush with perfect control and optimal absorption. Ideal for fine details and smooth washes.',
                'short_description' => 'Kuas premium dengan kontrol sempurna',
                'short_description_en' => 'Premium brush with perfect control',
                'price' => 125000,
                'sku' => 'BRU-KS-008',
                'stock' => 30,
                'brand' => 'Da Vinci',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Kuas Sable Kolinsky #8 - YourStudio',
                'meta_title_en' => 'Kolinsky Sable Brush #8 - YourStudio',
                'meta_description' => 'Kuas premium sable kolinsky untuk detail halus',
                'meta_description_en' => 'Premium kolinsky sable brush for fine details',
                'shopee_url' => 'https://shopee.co.id/your_____studio',
                'tiktok_url' => 'https://www.tiktok.com/@your_____studio',
            ],
            [
                'category_id' => $canvasCategory->id,
                'name' => 'Kanvas Stretched 40x50cm',
                'name_en' => 'Stretched Canvas 40x50cm',
                'slug' => 'kanvas-stretched-40x50cm',
                'description' => 'Kanvas stretched berkualitas tinggi dengan tekstur yang sempurna untuk melukis. Siap pakai dan tahan lama.',
                'description_en' => 'High-quality stretched canvas with perfect texture for painting. Ready to use and durable.',
                'short_description' => 'Kanvas berkualitas tinggi siap pakai',
                'short_description_en' => 'High-quality canvas ready to use',
                'price' => 75000,
                'sku' => 'CAN-40x50-001',
                'stock' => 100,
                'brand' => 'YourStudio',
                'is_featured' => false,
                'is_active' => true,
                'meta_title' => 'Kanvas Stretched 40x50cm - YourStudio',
                'meta_title_en' => 'Stretched Canvas 40x50cm - YourStudio',
                'meta_description' => 'Kanvas stretched berkualitas tinggi untuk melukis',
                'meta_description_en' => 'High-quality stretched canvas for painting',
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create events with multilingual content
        $events = [
            [
                'title' => 'Workshop Cat Air untuk Pemula',
                'title_en' => 'Watercolor Workshop for Beginners',
                'slug' => 'workshop-cat-air-untuk-pemula',
                'description' => 'Pelajari teknik dasar melukis dengan cat air dalam workshop yang menyenangkan dan interaktif. Cocok untuk pemula yang ingin memulai perjalanan seni mereka.',
                'description_en' => 'Learn basic watercolor painting techniques in a fun and interactive workshop. Suitable for beginners who want to start their artistic journey.',
                'short_description' => 'Workshop cat air untuk pemula dengan instruktur berpengalaman',
                'short_description_en' => 'Watercolor workshop for beginners with experienced instructors',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(7)->addHours(4),
                'location' => 'Studio YourStudio, Jakarta',
                'location_en' => 'YourStudio Studio, Jakarta',
                'price' => 150000,
                'max_participants' => 15,
                'current_participants' => 8,
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Workshop Cat Air untuk Pemula - YourStudio',
                'meta_title_en' => 'Watercolor Workshop for Beginners - YourStudio',
                'meta_description' => 'Bergabunglah dengan workshop cat air untuk pemula',
                'meta_description_en' => 'Join our watercolor workshop for beginners',
            ],
            [
                'title' => 'Seminar Seni Digital',
                'title_en' => 'Digital Art Seminar',
                'slug' => 'seminar-seni-digital',
                'description' => 'Eksplorasi dunia seni digital dengan teknologi terbaru. Pelajari penggunaan tablet grafis, software digital painting, dan teknik-teknik modern.',
                'description_en' => 'Explore the world of digital art with the latest technology. Learn about graphics tablets, digital painting software, and modern techniques.',
                'short_description' => 'Seminar tentang seni digital dan teknologi terkini',
                'short_description_en' => 'Seminar about digital art and current technology',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(14)->addHours(3),
                'location' => 'Auditorium Seni Jakarta',
                'location_en' => 'Jakarta Art Auditorium',
                'price' => 200000,
                'max_participants' => 50,
                'current_participants' => 25,
                'status' => 'published',
                'is_featured' => true,
                'is_active' => true,
                'meta_title' => 'Seminar Seni Digital - YourStudio',
                'meta_title_en' => 'Digital Art Seminar - YourStudio',
                'meta_description' => 'Seminar tentang seni digital dan teknologi',
                'meta_description_en' => 'Seminar about digital art and technology',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        // Create articles with multilingual content
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@yourstudio.com',
                'password' => bcrypt('password'),
            ]);
        }

        $articles = [
            [
                'user_id' => $user->id,
                'title' => 'Tips Memilih Cat Air yang Tepat',
                'title_en' => 'Tips for Choosing the Right Watercolor',
                'slug' => 'tips-memilih-cat-air-yang-tepat',
                'excerpt' => 'Panduan lengkap untuk memilih cat air yang sesuai dengan kebutuhan dan budget Anda.',
                'excerpt_en' => 'Complete guide to choosing watercolor that suits your needs and budget.',
                'content' => 'Memilih cat air yang tepat adalah langkah penting dalam perjalanan seni Anda. Berikut beberapa tips yang bisa membantu Anda...',
                'content_en' => 'Choosing the right watercolor is an important step in your artistic journey. Here are some tips that can help you...',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
                'meta_title' => 'Tips Memilih Cat Air yang Tepat - YourStudio',
                'meta_title_en' => 'Tips for Choosing the Right Watercolor - YourStudio',
                'meta_description' => 'Panduan lengkap memilih cat air berkualitas',
                'meta_description_en' => 'Complete guide to choosing quality watercolor',
            ],
            [
                'user_id' => $user->id,
                'title' => 'Teknik Dasar Menggunakan Kuas',
                'title_en' => 'Basic Brush Techniques',
                'slug' => 'teknik-dasar-menggunakan-kuas',
                'excerpt' => 'Pelajari teknik dasar menggunakan kuas untuk hasil lukisan yang lebih baik.',
                'excerpt_en' => 'Learn basic brush techniques for better painting results.',
                'content' => 'Menguasai teknik menggunakan kuas adalah fondasi penting dalam melukis. Dalam artikel ini, kita akan membahas berbagai teknik...',
                'content_en' => 'Mastering brush techniques is an important foundation in painting. In this article, we will discuss various techniques...',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(3),
                'meta_title' => 'Teknik Dasar Menggunakan Kuas - YourStudio',
                'meta_title_en' => 'Basic Brush Techniques - YourStudio',
                'meta_description' => 'Pelajari teknik dasar menggunakan kuas lukis',
                'meta_description_en' => 'Learn basic paint brush techniques',
            ],
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }
    }
}