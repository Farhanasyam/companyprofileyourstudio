<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutUs;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'section' => 'hero',
                'title' => 'Tentang Kami',
                'title_en' => 'About Us',
                'subtitle' => 'Pelajari lebih lanjut tentang YourStudio',
                'subtitle_en' => 'Learn more about YourStudio',
                'content' => null,
                'content_en' => null,
                'description' => null,
                'description_en' => null,
                'features' => null,
                'features_en' => null,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'section' => 'history',
                'title' => 'Sejarah Kami',
                'title_en' => 'Our Story',
                'subtitle' => null,
                'subtitle_en' => null,
                'content' => 'YourStudio adalah toko alat lukis dan clay terpercaya yang telah melayani kebutuhan kreativitas masyarakat selama bertahun-tahun.',
                'content_en' => 'YourStudio is a trusted paint and clay tools store that has been serving the creative needs of the community for years.',
                'description' => 'Kami didirikan dengan visi untuk menjadi partner terpercaya dalam mewujudkan kreativitas setiap individu. Dengan pengalaman dan keahlian yang mendalam di bidang seni dan kerajinan, kami menyediakan produk-produk berkualitas tinggi untuk memenuhi kebutuhan para seniman, mahasiswa, dan penggemar seni.',
                'description_en' => 'We were established with the vision to become a trusted partner in realizing every individual\'s creativity. With deep experience and expertise in the field of arts and crafts, we provide high-quality products to meet the needs of artists, students, and art enthusiasts.',
                'features' => null,
                'features_en' => null,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'section' => 'vision',
                'title' => 'Visi',
                'title_en' => 'Vision',
                'subtitle' => null,
                'subtitle_en' => null,
                'content' => 'Menjadi toko alat lukis dan clay terdepan yang menginspirasi dan mendukung setiap individu untuk mewujudkan kreativitas mereka melalui produk berkualitas tinggi dan layanan yang prima.',
                'content_en' => 'To become the leading paint and clay tools store that inspires and supports every individual to realize their creativity through high-quality products and excellent service.',
                'description' => null,
                'description_en' => null,
                'features' => null,
                'features_en' => null,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'section' => 'mission',
                'title' => 'Misi',
                'title_en' => 'Mission',
                'subtitle' => null,
                'subtitle_en' => null,
                'content' => 'Menyediakan produk alat lukis dan clay berkualitas tinggi dengan harga yang terjangkau, memberikan layanan konsultasi yang profesional, dan menciptakan komunitas yang mendukung perkembangan seni dan kreativitas.',
                'content_en' => 'To provide high-quality paint and clay products at affordable prices, deliver professional consultation services, and create a community that supports the development of art and creativity.',
                'description' => null,
                'description_en' => null,
                'features' => null,
                'features_en' => null,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'section' => 'why_choose_us',
                'title' => 'Mengapa Memilih Kami?',
                'title_en' => 'Why Choose Us?',
                'subtitle' => 'Keunggulan yang membuat kami berbeda',
                'subtitle_en' => 'Advantages that make us different',
                'content' => null,
                'content_en' => null,
                'description' => null,
                'description_en' => null,
                'features' => [
                    [
                        'title' => 'Kualitas Terjamin',
                        'description' => 'Semua produk kami telah melalui proses seleksi ketat untuk memastikan kualitas terbaik.',
                        'icon' => 'bi-award',
                        'color' => 'warning'
                    ],
                    [
                        'title' => 'Tim Ahli',
                        'description' => 'Tim kami terdiri dari para ahli di bidang seni yang siap memberikan konsultasi terbaik.',
                        'icon' => 'bi-people',
                        'color' => 'info'
                    ],
                    [
                        'title' => 'Pelayanan Ramah',
                        'description' => 'Kami berkomitmen memberikan pelayanan yang ramah dan memuaskan untuk setiap pelanggan.',
                        'icon' => 'bi-heart',
                        'color' => 'danger'
                    ]
                ],
                'features_en' => [
                    [
                        'title' => 'Quality Guaranteed',
                        'description' => 'All our products have gone through a strict selection process to ensure the best quality.',
                        'icon' => 'bi-award',
                        'color' => 'warning'
                    ],
                    [
                        'title' => 'Expert Team',
                        'description' => 'Our team consists of experts in the field of art who are ready to provide the best consultation.',
                        'icon' => 'bi-people',
                        'color' => 'info'
                    ],
                    [
                        'title' => 'Friendly Service',
                        'description' => 'We are committed to providing friendly and satisfying service for every customer.',
                        'icon' => 'bi-heart',
                        'color' => 'danger'
                    ]
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'section' => 'contact_info',
                'title' => 'Hubungi Kami',
                'title_en' => 'Get In Touch',
                'subtitle' => 'Ada pertanyaan atau butuh bantuan? Tim kami siap membantu Anda.',
                'subtitle_en' => 'Have questions or need help? Our team is ready to assist you.',
                'content' => null,
                'content_en' => null,
                'description' => null,
                'description_en' => null,
                'features' => null,
                'features_en' => null,
                'is_active' => true,
                'sort_order' => 6,
            ]
        ];

        foreach ($sections as $section) {
            AboutUs::updateOrCreate(
                ['section' => $section['section']],
                $section
            );
        }
    }
}
