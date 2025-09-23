<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
        'title_en',
        'content',
        'content_en',
        'subtitle',
        'subtitle_en',
        'description',
        'description_en',
        'features',
        'features_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'features_en' => 'array',
        'is_active' => 'boolean',
    ];

    // Scope untuk section aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Method untuk mendapatkan section tertentu
    public static function getSection($section)
    {
        return static::where('section', $section)->active()->first();
    }

    // Method untuk mendapatkan semua section aktif
    public static function getAllSections()
    {
        return static::active()->ordered()->get()->keyBy('section');
    }

    // Method untuk mendapatkan konten berdasarkan bahasa
    public function getLocalizedTitle($locale = 'id')
    {
        return $locale === 'en' && $this->title_en ? $this->title_en : $this->title;
    }

    public function getLocalizedSubtitle($locale = 'id')
    {
        return $locale === 'en' && $this->subtitle_en ? $this->subtitle_en : $this->subtitle;
    }

    public function getLocalizedContent($locale = 'id')
    {
        return $locale === 'en' && $this->content_en ? $this->content_en : $this->content;
    }

    public function getLocalizedDescription($locale = 'id')
    {
        return $locale === 'en' && $this->description_en ? $this->description_en : $this->description;
    }

    public function getLocalizedFeatures($locale = 'id')
    {
        return $locale === 'en' && $this->features_en ? $this->features_en : $this->features;
    }
}
