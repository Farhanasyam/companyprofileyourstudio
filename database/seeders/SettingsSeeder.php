<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Information (Indonesian)
            ['key' => 'company_name', 'value' => 'YourStudio', 'type' => 'text', 'group' => 'company', 'description' => 'Nama perusahaan'],
            ['key' => 'company_tagline', 'value' => 'Wujudkan Kreativitas Anda Bersama Kami', 'type' => 'text', 'group' => 'company', 'description' => 'Tagline perusahaan'],
            ['key' => 'company_description', 'value' => 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik untuk mewujudkan kreativitas Anda', 'type' => 'textarea', 'group' => 'company', 'description' => 'Deskripsi perusahaan'],
            
            // Company Information (English)
            ['key' => 'company_name_en', 'value' => 'YourStudio', 'type' => 'text', 'group' => 'company', 'description' => 'Company name (English)'],
            ['key' => 'company_tagline_en', 'value' => 'Bring Your Creativity to Life With Us', 'type' => 'text', 'group' => 'company', 'description' => 'Company tagline (English)'],
            ['key' => 'company_description_en', 'value' => 'Trusted paint and clay tools store with the best quality to bring your creativity to life', 'type' => 'textarea', 'group' => 'company', 'description' => 'Company description (English)'],
            ['key' => 'company_address', 'value' => 'Jl. Contoh Alamat No. 123, Kota Anda', 'type' => 'textarea', 'group' => 'company', 'description' => 'Alamat perusahaan'],
            ['key' => 'company_phone', 'value' => '+62 812-3456-7890', 'type' => 'text', 'group' => 'company', 'description' => 'Nomor telepon'],
            ['key' => 'company_email', 'value' => 'info@yourstudio.com', 'type' => 'text', 'group' => 'company', 'description' => 'Email perusahaan'],
            ['key' => 'company_whatsapp', 'value' => '+62 812-3456-7890', 'type' => 'text', 'group' => 'company', 'description' => 'Nomor WhatsApp'],
            ['key' => 'whatsapp_event_registration', 'value' => '+62 812-3456-7890', 'type' => 'text', 'group' => 'company', 'description' => 'Nomor WhatsApp untuk pendaftaran event'],
            ['key' => 'company_operating_hours', 'value' => 'Senin - Jumat: 08:00 - 17:00, Sabtu: 08:00 - 15:00', 'type' => 'text', 'group' => 'company', 'description' => 'Jam operasional'],
            ['key' => 'company_latitude', 'value' => '-6.2088', 'type' => 'text', 'group' => 'company', 'description' => 'Latitude untuk Google Maps'],
            ['key' => 'company_longitude', 'value' => '106.8456', 'type' => 'text', 'group' => 'company', 'description' => 'Longitude untuk Google Maps'],
            ['key' => 'google_maps_embed', 'value' => '', 'type' => 'textarea', 'group' => 'company', 'description' => 'Google Maps embed code'],
            
            // Social Media
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/yourstudio', 'type' => 'text', 'group' => 'social', 'description' => 'URL Instagram'],
            ['key' => 'shopee_url', 'value' => 'https://shopee.co.id/your_____studio', 'type' => 'text', 'group' => 'social', 'description' => 'URL Shopee'],
            ['key' => 'facebook_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'description' => 'URL Facebook'],
            ['key' => 'tiktok_url', 'value' => 'https://www.tiktok.com/@your_____studio', 'type' => 'text', 'group' => 'social', 'description' => 'URL TikTok'],
            
            // SEO (Indonesian)
            ['key' => 'meta_title', 'value' => 'YourStudio - Toko Alat Lukis dan Clay Terpercaya', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta title website'],
            ['key' => 'meta_description', 'value' => 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik. Temukan berbagai produk untuk mewujudkan kreativitas Anda.', 'type' => 'textarea', 'group' => 'seo', 'description' => 'Meta description website'],
            
            // SEO (English)
            ['key' => 'meta_title_en', 'value' => 'YourStudio - Trusted Paint and Clay Tools Store', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta title website (English)'],
            ['key' => 'meta_description_en', 'value' => 'Trusted paint and clay tools store with the best quality. Find various products to bring your creativity to life.', 'type' => 'textarea', 'group' => 'seo', 'description' => 'Meta description website (English)'],
            ['key' => 'meta_keywords', 'value' => 'alat lukis, clay, cat air, cat minyak, kuas, kanvas, tanah liat', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta keywords'],
            
            // Open Graph
            ['key' => 'og_title', 'value' => 'YourStudio - Toko Alat Lukis dan Clay Terpercaya', 'type' => 'text', 'group' => 'seo', 'description' => 'Open Graph title'],
            ['key' => 'og_description', 'value' => 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik. Temukan berbagai produk untuk mewujudkan kreativitas Anda.', 'type' => 'textarea', 'group' => 'seo', 'description' => 'Open Graph description'],
            ['key' => 'og_image', 'value' => '', 'type' => 'image', 'group' => 'seo', 'description' => 'Open Graph image'],
            
            
            // Technical SEO
            ['key' => 'canonical_url', 'value' => '', 'type' => 'text', 'group' => 'seo', 'description' => 'Canonical URL'],
            ['key' => 'robots', 'value' => 'index, follow', 'type' => 'text', 'group' => 'seo', 'description' => 'Robots meta tag'],
            ['key' => 'sitemap_priority', 'value' => '0.8', 'type' => 'text', 'group' => 'seo', 'description' => 'Sitemap priority'],
            
            // General
            ['key' => 'logo', 'value' => '', 'type' => 'image', 'group' => 'general', 'description' => 'Logo perusahaan'],
            ['key' => 'favicon', 'value' => '', 'type' => 'image', 'group' => 'general', 'description' => 'Favicon website'],
            ['key' => 'hero_image', 'value' => '', 'type' => 'image', 'group' => 'general', 'description' => 'Gambar hero section'],
            ['key' => 'about_image', 'value' => '', 'type' => 'image', 'group' => 'general', 'description' => 'Gambar halaman tentang kami'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
