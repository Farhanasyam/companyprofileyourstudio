<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Static methods for different gallery types
    public static function getHeroImages()
    {
        return self::active()->byType('hero')->ordered()->get();
    }

    public static function getAboutImages()
    {
        return self::active()->byType('about')->ordered()->get();
    }

    public static function getProductImages()
    {
        return self::active()->byType('product')->ordered()->get();
    }

    public static function getGalleryImages()
    {
        return self::active()->byType('gallery')->ordered()->get();
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
}
