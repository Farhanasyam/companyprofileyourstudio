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
                'subtitle' => 'Mewujudkan kreativitas Anda dengan alat lukis dan clay berkualitas',
                'subtitle_en' => 'Bringing your creativity to life with quality paint and clay tools',
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
                'title' => 'Cerita Kami',
                'title_en' => 'Our Story',
                'subtitle' => null,
                'subtitle_en' => null,
                'content' => 'YourStudio lahir dari kecintaan pada seni dan keinginan untuk memudahkan setiap orang mengakses alat kreatif yang berkualitas.',
                'content_en' => 'YourStudio was born from a love of art and the desire to make quality creative tools accessible to everyone.',
                'description' => 'Sejak berdiri, kami berkomitmen menjadi mitra terpercaya bagi pelukis, pengrajin clay, mahasiswa seni, dan siapa pun yang ingin mengekspresikan ide melalui karya. Kami memilih setiap produk dengan teliti—dari cat air, cat minyak, kuas, kanvas, hingga tanah liat dan perlengkapan clay—agar Anda bisa fokus berkreasi tanpa khawatir kualitas bahan. Tim kami siap membantu Anda memilih produk yang tepat dan memberikan tips serta inspirasi untuk proyek Anda.',
                'description_en' => 'Since our founding, we have been committed to being a trusted partner for painters, clay crafters, art students, and anyone who wants to express ideas through their work. We carefully select every product—from watercolors, oil paints, brushes, canvas, to clay and clay supplies—so you can focus on creating without worrying about material quality. Our team is ready to help you choose the right products and provide tips and inspiration for your projects.',
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
                'content' => 'Menjadi mitra utama bagi setiap orang yang ingin mewujudkan ide kreatif—dari pemula hingga profesional—melalui produk alat lukis dan clay terpilih serta layanan yang mendukung dari hati.',
                'content_en' => 'To be the go-to partner for everyone who wants to bring creative ideas to life—from beginners to professionals—through curated paint and clay tools and heartfelt service.',
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
                'content' => 'Menyediakan alat lukis dan clay berkualitas dengan harga wajar; memberikan saran dan konsultasi yang membantu; serta membangun komunitas yang saling menginspirasi lewat workshop dan konten seni.',
                'content_en' => 'To provide quality paint and clay tools at fair prices; to offer helpful advice and consultation; and to build a community that inspires each other through workshops and art content.',
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
                'subtitle_en' => 'What sets us apart',
                'content' => null,
                'content_en' => null,
                'description' => null,
                'description_en' => null,
                'features' => [
                    [
                        'title' => 'Kualitas Terjamin',
                        'description' => 'Setiap produk kami diseleksi dengan ketat sehingga Anda mendapat bahan terbaik untuk lukis dan clay.',
                        'icon' => 'bi-award',
                        'color' => 'warning'
                    ],
                    [
                        'title' => 'Tim Berpengalaman',
                        'description' => 'Tim kami paham kebutuhan seniman dan siap memberi rekomendasi produk serta tips berkarya.',
                        'icon' => 'bi-people',
                        'color' => 'primary'
                    ],
                    [
                        'title' => 'Pelayanan Ramah',
                        'description' => 'Kami melayani dengan senang hati dan berusaha memastikan Anda puas dari pemilihan sampai pengiriman.',
                        'icon' => 'bi-heart',
                        'color' => 'danger'
                    ]
                ],
                'features_en' => [
                    [
                        'title' => 'Quality Guaranteed',
                        'description' => 'Every product is carefully selected so you get the best materials for painting and clay.',
                        'icon' => 'bi-award',
                        'color' => 'warning'
                    ],
                    [
                        'title' => 'Experienced Team',
                        'description' => 'Our team understands artists\' needs and is ready to recommend products and share creative tips.',
                        'icon' => 'bi-people',
                        'color' => 'primary'
                    ],
                    [
                        'title' => 'Friendly Service',
                        'description' => 'We serve with care and strive to ensure your satisfaction from selection to delivery.',
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
