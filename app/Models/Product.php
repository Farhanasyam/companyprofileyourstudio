<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'short_description',
        'short_description_en',
        'image',
        'images',
        'main_image',
        'video',
        'videos',
        'specifications',
        'is_featured',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_title_en',
        'meta_description',
        'meta_description_en',
        'shopee_url',
        'tiktok_url',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'specifications' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor

    // Clear order-modal cache when product changes (agar dropdown order tetap up-to-date)
    protected static function booted()
    {
        static::saved(function () {
            \Cache::forget('order_modal_products_id');
            \Cache::forget('order_modal_products_en');
        });
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrderBySortOrder($query, $direction = 'asc')
    {
        return $query->orderBy('sort_order', $direction);
    }

    // Cache frequently accessed data
    public static function getFeaturedProducts($limit = 6)
    {
        return \Cache::remember("featured_products_{$limit}", 3600, function () use ($limit) {
            return static::active()
                ->featured()
                ->orderBySortOrder()
                ->limit($limit)
                ->get();
        });
    }

    public static function getProductsByCategory($categoryId, $limit = 12)
    {
        return \Cache::remember("products_category_{$categoryId}_{$limit}", 3600, function () use ($categoryId, $limit) {
            return static::active()
                ->where('category_id', $categoryId)
                ->orderBySortOrder()
                ->limit($limit)
                ->get();
        });
    }


    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    // Accessor for video URL
    public function getVideoUrlAttribute()
    {
        if ($this->video) {
            return asset('storage/' . $this->video);
        }
        return null;
    }

    // Multilingual accessors
    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->name_en ? $this->name_en : $this->name;
    }

    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->description_en ? $this->description_en : $this->description;
    }

    public function getLocalizedShortDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->short_description_en ? $this->short_description_en : $this->short_description;
    }

    public function getLocalizedMetaTitleAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->meta_title_en ? $this->meta_title_en : $this->meta_title;
    }

    public function getLocalizedMetaDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->meta_description_en ? $this->meta_description_en : $this->meta_description;
    }
}
