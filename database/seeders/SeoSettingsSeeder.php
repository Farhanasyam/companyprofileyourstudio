<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SeoSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seoSettings = [
            [
                'key' => 'meta_title',
                'value' => 'YourStudio - Toko Alat Lukis dan Clay Terpercaya',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta title untuk halaman utama website'
            ],
            [
                'key' => 'meta_description',
                'value' => 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik. Menyediakan berbagai macam alat lukis, cat, kuas, canvas, dan clay untuk semua kebutuhan seni Anda.',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Meta description untuk halaman utama website'
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'alat lukis, clay, toko seni, cat lukis, kuas, canvas, seni, melukis, keramik',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta keywords untuk website'
            ],
            [
                'key' => 'og_title',
                'value' => 'YourStudio - Toko Alat Lukis dan Clay Terpercaya',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Open Graph title untuk social media sharing'
            ],
            [
                'key' => 'og_description',
                'value' => 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik. Temukan berbagai macam alat seni untuk mengekspresikan kreativitas Anda.',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Open Graph description untuk social media sharing'
            ],
            [
                'key' => 'og_image',
                'value' => '',
                'type' => 'image',
                'group' => 'seo',
                'description' => 'Open Graph image untuk social media sharing (1200x630px)'
            ],
            [
                'key' => 'twitter_title',
                'value' => 'YourStudio - Toko Alat Lukis dan Clay Terpercaya',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Twitter Card title'
            ],
            [
                'key' => 'twitter_description',
                'value' => 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik. Temukan berbagai macam alat seni untuk mengekspresikan kreativitas Anda.',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Twitter Card description'
            ],
            [
                'key' => 'twitter_image',
                'value' => '',
                'type' => 'image',
                'group' => 'seo',
                'description' => 'Twitter Card image (1200x630px)'
            ],
            [
                'key' => 'canonical_url',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Canonical URL untuk halaman utama'
            ],
            [
                'key' => 'robots',
                'value' => 'index, follow',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Robots meta tag'
            ],
            [
                'key' => 'sitemap_priority',
                'value' => '1.0',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Priority untuk sitemap XML'
            ],
            
            // Maps Settings
            [
                'key' => 'maps_iframe',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613!3d-6.2087634!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0x6b45e67356080477!2sNational%20Monument!5e0!3m2!1sen!2sid!4v1679234567890!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Google Maps iframe embed code'
            ],
            [
                'key' => 'maps_address',
                'value' => 'Jl. Merdeka Utara, Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10110',
                'type' => 'textarea',
                'group' => 'seo',
                'description' => 'Full business address'
            ]
        ];

        foreach ($seoSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}