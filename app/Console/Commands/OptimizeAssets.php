<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OptimizeAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize assets for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting assets optimization...');
        
        // Optimize images
        $this->optimizeImages();
        
        // Minify CSS
        $this->minifyCSS();
        
        // Minify JavaScript
        $this->minifyJavaScript();
        
        // Generate WebP images
        $this->generateWebPImages();
        
        $this->info('Assets optimization completed successfully!');
    }
    
    /**
     * Optimize images
     */
    private function optimizeImages()
    {
        $this->info('Optimizing images...');
        
        $imagePath = storage_path('app/public');
        $images = File::allFiles($imagePath);
        
        foreach ($images as $image) {
            if (in_array($image->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $this->line("Optimizing: {$image->getFilename()}");
                // Add image optimization logic here
            }
        }
    }
    
    /**
     * Minify CSS files
     */
    private function minifyCSS()
    {
        $this->info('Minifying CSS files...');
        
        $cssFiles = [
            public_path('css/modern-styles.css'),
            public_path('css/countdown.css'),
            public_path('css/critical.css')
        ];
        
        foreach ($cssFiles as $cssFile) {
            if (File::exists($cssFile)) {
                $content = File::get($cssFile);
                $minified = $this->minifyCSSContent($content);
                
                $minifiedFile = str_replace('.css', '.min.css', $cssFile);
                File::put($minifiedFile, $minified);
                
                $this->line("✓ Minified: " . basename($cssFile));
            }
        }
    }
    
    /**
     * Minify JavaScript files
     */
    private function minifyJavaScript()
    {
        $this->info('Minifying JavaScript files...');
        
        $jsFiles = [
            public_path('js/optimized.js'),
            public_path('js/countdown.js')
        ];
        
        foreach ($jsFiles as $jsFile) {
            if (File::exists($jsFile)) {
                $content = File::get($jsFile);
                $minified = $this->minifyJSContent($content);
                
                $minifiedFile = str_replace('.js', '.min.js', $jsFile);
                File::put($minifiedFile, $minified);
                
                $this->line("✓ Minified: " . basename($jsFile));
            }
        }
    }
    
    /**
     * Generate WebP images
     */
    private function generateWebPImages()
    {
        $this->info('Generating WebP images...');
        
        $imagePath = storage_path('app/public');
        $images = File::allFiles($imagePath);
        
        foreach ($images as $image) {
            if (in_array($image->getExtension(), ['jpg', 'jpeg', 'png'])) {
                $this->line("Generating WebP: {$image->getFilename()}");
                // Add WebP generation logic here
            }
        }
    }
    
    /**
     * Minify CSS content
     */
    private function minifyCSSContent($css)
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Remove unnecessary spaces
        $css = str_replace([' {', '{ ', ' }', '} ', '; ', ' ;', ', ', ' ,'], ['{', '{', '}', '}', ';', ';', ',', ','], $css);
        
        return trim($css);
    }
    
    /**
     * Minify JavaScript content
     */
    private function minifyJSContent($js)
    {
        // Remove single-line comments
        $js = preg_replace('~//[^\r\n]*~', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Remove unnecessary whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Remove spaces around operators
        $js = preg_replace('/\s*([{}();,=+\-*\/])\s*/', '$1', $js);
        
        return trim($js);
    }
}
