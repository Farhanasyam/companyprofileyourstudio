<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'title_en',
        'slug',
        'excerpt',
        'excerpt_en',
        'content',
        'content_en',
        'featured_image',
        'gallery',
        'status',
        'is_featured',
        'views',
        'meta_title',
        'meta_title_en',
        'meta_description',
        'meta_description_en',
        'tags',
        'published_at',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: tampilkan artikel yang status-nya published
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeOrderByPublishedAt($query, $direction = 'desc')
    {
        return $query->orderBy('published_at', $direction);
    }

    // Cache frequently accessed data
    public static function getLatestArticles($limit = 6)
    {
        return \Cache::remember("latest_articles_{$limit}", 3600, function () use ($limit) {
            return static::published()
                ->orderByPublishedAt()
                ->limit($limit)
                ->get();
        });
    }

    public static function getFeaturedArticles($limit = 3)
    {
        return \Cache::remember("featured_articles_{$limit}", 3600, function () use ($limit) {
            return static::published()
                ->featured()
                ->orderByPublishedAt()
                ->limit($limit)
                ->get();
        });
    }

    // Accessor
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutesToRead = round($wordCount / 200); // Average reading speed: 200 words per minute
        return max(1, $minutesToRead);
    }

    // Accessor for featured image URL (path relatif agar gambar benar di hosting tanpa bergantung APP_URL)
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return '/' . ltrim('storage/' . ltrim($this->featured_image, '/'), '/');
        }
        return null;
    }

    // Multilingual accessors
    public function getLocalizedTitleAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->title_en ? $this->title_en : $this->title;
    }

    public function getLocalizedExcerptAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->excerpt_en ? $this->excerpt_en : $this->excerpt;
    }

    public function getLocalizedContentAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->content_en ? $this->content_en : $this->content;
    }

    /** HTML aman untuk ditampilkan (decode entity jika konten pernah di-escape) */
    public function getExcerptHtmlAttribute()
    {
        $text = $this->localized_excerpt ?? $this->excerpt ?? '';
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /** HTML aman untuk ditampilkan (decode entity jika konten pernah di-escape) */
    public function getContentHtmlAttribute()
    {
        $text = $this->localized_content ?? $this->content ?? '';
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
