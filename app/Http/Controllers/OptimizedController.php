<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Product;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class OptimizedController extends Controller
{
    /**
     * Get optimized homepage data with caching
     */
    public function getHomepageData()
    {
        return Cache::remember('homepage_data', 3600, function () {
            return [
                'featuredProducts' => Product::getFeaturedProducts(6),
                'featuredArticles' => Article::getFeaturedArticles(3),
                'upcomingEvents' => Event::getUpcomingEvents(3),
                'categories' => Category::active()
                    ->ordered()
                    ->withCount('products')
                    ->take(6)
                    ->get()
            ];
        });
    }
    
    /**
     * Get optimized events data with caching
     */
    public function getEventsData()
    {
        return Cache::remember('events_data', 1800, function () {
            return [
                'upcomingEvents' => Event::getUpcomingEvents(50),
                'ongoingEvents' => Event::published()
                    ->active()
                    ->ongoing()
                    ->get(),
                'pastEvents' => Event::published()
                    ->active()
                    ->where('end_date', '<', now())
                    ->latest('start_date')
                    ->take(6)
                    ->get()
            ];
        });
    }
    
    /**
     * Get optimized products data with caching
     */
    public function getProductsData($categoryId = null)
    {
        $cacheKey = $categoryId ? "products_category_{$categoryId}" : 'products_all';
        
        return Cache::remember($cacheKey, 1800, function () use ($categoryId) {
            $query = Product::active()->with('category');
            
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
            
            return $query->orderBy('sort_order')->paginate(12);
        });
    }
    
    /**
     * Get optimized articles data with caching
     */
    public function getArticlesData()
    {
        return Cache::remember('articles_data', 1800, function () {
            return Article::published()
                ->with('category')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
    }
    
    /**
     * Clear all caches
     */
    public function clearCache()
    {
        Cache::flush();
        return response()->json(['message' => 'Cache cleared successfully']);
    }
    
    /**
     * Clear specific cache
     */
    public function clearSpecificCache($key)
    {
        Cache::forget($key);
        return response()->json(['message' => "Cache '{$key}' cleared successfully"]);
    }
}
