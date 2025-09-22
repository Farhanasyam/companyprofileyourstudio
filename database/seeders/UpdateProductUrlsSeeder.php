<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductUrlsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update produk kuas lukis dengan URL Shopee dan TikTok
        $brushProduct = Product::where('slug', 'kuas-sable-kolinsky-8')->first();
        
        if ($brushProduct) {
            $brushProduct->update([
                'shopee_url' => 'https://shopee.co.id/your_____studio',
                'tiktok_url' => 'https://www.tiktok.com/@your_____studio',
            ]);
            
            $this->command->info('Updated brush product with Shopee and TikTok URLs');
        } else {
            $this->command->warn('Brush product not found');
        }

        // Update produk cat air dengan URL Shopee dan TikTok
        $watercolorProduct = Product::where('slug', 'cat-air-winsor-newton-professional')->first();
        
        if ($watercolorProduct) {
            $watercolorProduct->update([
                'shopee_url' => 'https://shopee.co.id/your_____studio',
                'tiktok_url' => 'https://www.tiktok.com/@your_____studio',
            ]);
            
            $this->command->info('Updated watercolor product with Shopee and TikTok URLs');
        } else {
            $this->command->warn('Watercolor product not found');
        }

        // Update produk kanvas dengan URL Shopee dan TikTok
        $canvasProduct = Product::where('slug', 'kanvas-stretched-40x50cm')->first();
        
        if ($canvasProduct) {
            $canvasProduct->update([
                'shopee_url' => 'https://shopee.co.id/your_____studio',
                'tiktok_url' => 'https://www.tiktok.com/@your_____studio',
            ]);
            
            $this->command->info('Updated canvas product with Shopee and TikTok URLs');
        } else {
            $this->command->warn('Canvas product not found');
        }

        $this->command->info('Product URLs update completed!');
    }
}