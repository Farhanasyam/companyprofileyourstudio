<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class OptimizeCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize application cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cache optimization...');
        
        // Clear all caches
        $this->clearCaches();
        
        // Warm up caches
        $this->warmUpCaches();
        
        // Optimize cache configuration
        $this->optimizeCacheConfig();
        
        $this->info('Cache optimization completed successfully!');
    }
    
    /**
     * Clear all caches
     */
    private function clearCaches()
    {
        $this->info('Clearing caches...');
        
        // Clear application cache
        Artisan::call('cache:clear');
        $this->line('✓ Application cache cleared');
        
        // Clear route cache
        Artisan::call('route:clear');
        $this->line('✓ Route cache cleared');
        
        // Clear view cache
        Artisan::call('view:clear');
        $this->line('✓ View cache cleared');
        
        // Clear config cache
        Artisan::call('config:clear');
        $this->line('✓ Config cache cleared');
    }
    
    /**
     * Warm up caches
     */
    private function warmUpCaches()
    {
        $this->info('Warming up caches...');
        
        // Cache routes
        Artisan::call('route:cache');
        $this->line('✓ Routes cached');
        
        // Cache config
        Artisan::call('config:cache');
        $this->line('✓ Config cached');
        
        // Cache views
        Artisan::call('view:cache');
        $this->line('✓ Views cached');
        
        // Warm up application caches
        $this->warmUpApplicationCaches();
    }
    
    /**
     * Warm up application-specific caches
     */
    private function warmUpApplicationCaches()
    {
        $this->info('Warming up application caches...');
        
        // Cache homepage data
        $this->warmUpHomepageCache();
        
        // Cache events data
        $this->warmUpEventsCache();
        
        // Cache products data
        $this->warmUpProductsCache();
        
        // Cache articles data
        $this->warmUpArticlesCache();
    }
    
    /**
     * Warm up homepage cache
     */
    private function warmUpHomepageCache()
    {
        try {
            $controller = new \App\Http\Controllers\OptimizedController();
            $controller->getHomepageData();
            $this->line('✓ Homepage cache warmed up');
        } catch (\Exception $e) {
            $this->error('✗ Failed to warm up homepage cache: ' . $e->getMessage());
        }
    }
    
    /**
     * Warm up events cache
     */
    private function warmUpEventsCache()
    {
        try {
            $controller = new \App\Http\Controllers\OptimizedController();
            $controller->getEventsData();
            $this->line('✓ Events cache warmed up');
        } catch (\Exception $e) {
            $this->error('✗ Failed to warm up events cache: ' . $e->getMessage());
        }
    }
    
    /**
     * Warm up products cache
     */
    private function warmUpProductsCache()
    {
        try {
            $controller = new \App\Http\Controllers\OptimizedController();
            $controller->getProductsData();
            $this->line('✓ Products cache warmed up');
        } catch (\Exception $e) {
            $this->error('✗ Failed to warm up products cache: ' . $e->getMessage());
        }
    }
    
    /**
     * Warm up articles cache
     */
    private function warmUpArticlesCache()
    {
        try {
            $controller = new \App\Http\Controllers\OptimizedController();
            $controller->getArticlesData();
            $this->line('✓ Articles cache warmed up');
        } catch (\Exception $e) {
            $this->error('✗ Failed to warm up articles cache: ' . $e->getMessage());
        }
    }
    
    /**
     * Optimize cache configuration
     */
    private function optimizeCacheConfig()
    {
        $this->info('Optimizing cache configuration...');
        
        // Set optimal cache settings
        $cacheSettings = [
            'cache.default' => 'file',
            'cache.stores.file.path' => storage_path('framework/cache/data'),
            'cache.prefix' => env('APP_NAME', 'laravel') . '_cache_'
        ];
        
        foreach ($cacheSettings as $key => $value) {
            config([$key => $value]);
            $this->line("✓ Set {$key} = {$value}");
        }
    }
}
