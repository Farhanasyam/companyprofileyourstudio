<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'description',
        'description_en',
        'short_description',
        'short_description_en',
        'image',
        'video',
        'gallery',
        'start_date',
        'end_date',
        'location',
        'location_en',
        'status',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_title_en',
        'meta_description',
        'meta_description_en',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Accessors (path relatif, ter-encode agar nama file dengan spasi/karakter khusus tetap bisa dimuat)
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $encoded = \App\Helpers\ImageHelper::encodePathForUrl($this->image);
            return $encoded !== '' ? '/storage/' . $encoded : null;
        }
        return null;
    }

    public function getVideoUrlAttribute()
    {
        if ($this->video) {
            $encoded = \App\Helpers\ImageHelper::encodePathForUrl($this->video);
            return $encoded !== '' ? '/storage/' . $encoded : null;
        }
        return null;
    }

    public function getIsUpcomingAttribute()
    {
        return $this->start_date > now();
    }

    public function getIsOngoingAttribute()
    {
        return $this->start_date <= now() && (!$this->end_date || $this->end_date >= now());
    }

    public function getIsPastAttribute()
    {
        return $this->end_date && $this->end_date < now();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    public function scopePast($query)
    {
        return $query->where(function($q) {
            $q->where('end_date', '<', now())
              ->orWhere(function($subQ) {
                  $subQ->whereNull('end_date')
                       ->where('start_date', '<', now()->subDay());
              });
        });
    }

    public function scopeOrderByStartDate($query, $direction = 'asc')
    {
        return $query->orderBy('start_date', $direction);
    }

    public function scopeWithCountdown($query)
    {
        return $query->select('*')
            ->addSelect(\DB::raw('TIMESTAMPDIFF(SECOND, NOW(), start_date) as seconds_until_start'))
            ->where('start_date', '>', now());
    }

    // Cache frequently accessed data
    public static function getUpcomingEvents($limit = 6)
    {
        return \Cache::remember("upcoming_events_{$limit}", 3600, function () use ($limit) {
            return static::active()
                ->upcoming()
                ->orderByStartDate()
                ->limit($limit)
                ->get();
        });
    }

    public static function getFeaturedEvents($limit = 3)
    {
        return \Cache::remember("featured_events_{$limit}", 3600, function () use ($limit) {
            return static::active()
                ->featured()
                ->upcoming()
                ->orderByStartDate()
                ->limit($limit)
                ->get();
        });
    }

    // Generate unique slug
    public static function createSlug($title)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    // Multilingual accessors
    public function getLocalizedTitleAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->title_en ? $this->title_en : $this->title;
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

    public function getLocalizedLocationAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->location_en ? $this->location_en : $this->location;
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

    // Ultra-simplified countdown accessor (only days)
    public function getCountdownAttribute()
    {
        $now = Carbon::now();
        $eventDate = $this->start_date;
        $timeLeft = $now->diffInSeconds($eventDate, false);

        if ($timeLeft <= 0) {
            return [
                'days' => 0,
                'total_seconds' => 0
            ];
        }

        $days = floor($timeLeft / 86400);

        return [
            'days' => $days,
            'total_seconds' => $timeLeft
        ];
    }

    // Event status accessor
    public function getEventStatusAttribute()
    {
        $now = Carbon::now();
        $eventDate = $this->start_date;
        
        if ($now->lt($eventDate)) {
            return 'upcoming';
        } elseif ($now->diffInMinutes($eventDate) <= 5) {
            return 'starting';
        } else {
            return 'ended';
        }
    }

    // Method untuk mendapatkan waktu yang tersisa dalam format readable (days only)
    public function getTimeLeftFormatted()
    {
        $countdown = $this->countdown;
        
        if ($countdown['total_seconds'] <= 0) {
            return 'Event telah berakhir';
        }
        
        if ($countdown['days'] > 0) {
            return $countdown['days'] . ' hari lagi';
        } else {
            return 'Hari ini';
        }
    }
}
