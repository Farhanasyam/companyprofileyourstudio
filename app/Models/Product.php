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

    // Clear caches when product changes (order modal + produk unggulan di beranda)
    protected static function booted()
    {
        static::saved(function () {
            \Cache::forget('order_modal_products_id');
            \Cache::forget('order_modal_products_en');
            foreach ([3, 6, 9] as $limit) {
                \Cache::forget("featured_products_{$limit}");
            }
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


    // Accessor for image URL (path relatif, ter-encode agar nama file dengan spasi/karakter khusus tetap bisa dimuat)
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $encoded = \App\Helpers\ImageHelper::encodePathForUrl($this->image);
            return $encoded !== '' ? '/storage/' . $encoded : null;
        }
        return null;
    }

    /** URL gambar untuk tampilan (beranda, kartu): pakai image, atau main_image, atau gambar pertama dari images[]) */
    public function getDisplayImageUrlAttribute()
    {
        if ($this->image) {
            $encoded = \App\Helpers\ImageHelper::encodePathForUrl($this->image);
            return $encoded !== '' ? '/storage/' . $encoded : null;
        }
        if ($this->main_image) {
            $encoded = \App\Helpers\ImageHelper::encodePathForUrl($this->main_image);
            return $encoded !== '' ? '/storage/' . $encoded : null;
        }
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            $first = $this->images[0];
            $path = is_string($first) ? $first : ($first['path'] ?? $first['url'] ?? null);
            if ($path) {
                $encoded = \App\Helpers\ImageHelper::encodePathForUrl($path);
                if ($encoded !== '') {
                    return '/storage/' . $encoded;
                }
            }
        }
        return null;
    }

    // Accessor for video URL (path relatif, ter-encode)
    public function getVideoUrlAttribute()
    {
        if ($this->video) {
            $encoded = \App\Helpers\ImageHelper::encodePathForUrl($this->video);
            return $encoded !== '' ? '/storage/' . $encoded : null;
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
