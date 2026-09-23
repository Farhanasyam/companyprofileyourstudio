<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected static ?array $valueCache = null;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    // Static methods for easy access
    public static function get($key, $default = null)
    {
        static::loadValueCache();

        return static::$valueCache[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        static::loadValueCache();
        static::$valueCache[$key] = $value;

        return $setting;
    }

    public static function getGroup($group)
    {
        return static::where('group', $group)->pluck('value', 'key');
    }

    protected static function loadValueCache(): void
    {
        if (static::$valueCache === null) {
            static::$valueCache = static::query()->pluck('value', 'key')->all();
        }
    }

    // Scope
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
