<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Cache produk (beranda & modal order) menyimpan nama kategori, jadi ikut dibersihkan
    protected static function booted()
    {
        static::saved(fn () => Product::clearPublicCaches());
        static::deleted(fn () => Product::clearPublicCaches());
    }

    // Relationship
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
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

    /** Ikon Bootstrap Icons untuk placeholder produk/kategori tanpa gambar */
    public const PLACEHOLDER_ICONS = [
        'cat-air' => 'bi-droplet-half',
        'cat-akrilik' => 'bi-palette2',
        'kuas' => 'bi-brush',
        'kanvas' => 'bi-easel',
        'easel' => 'bi-easel2',
        'palet' => 'bi-palette',
        'clay' => 'bi-cup',
        'alat-pembentuk' => 'bi-tools',
        'finishing-clay' => 'bi-stars',
        'aksesoris-clay' => 'bi-scissors',
        'alat-tulis-journaling' => 'bi-pencil',
        'stiker-diy' => 'bi-stickies',
        'paket-bundling' => 'bi-gift',
    ];

    public function getPlaceholderIconAttribute(): string
    {
        return self::PLACEHOLDER_ICONS[$this->slug] ?? 'bi-box-seam';
    }

    /** Kelas warna placeholder (ph--slug), lihat modern-styles.css */
    public function getPlaceholderClassAttribute(): string
    {
        return 'ph ph--' . (isset(self::PLACEHOLDER_ICONS[$this->slug]) ? $this->slug : 'default');
    }
}
