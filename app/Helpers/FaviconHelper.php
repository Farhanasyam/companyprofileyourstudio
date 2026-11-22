<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

/**
 * Favicon situs: sama di semua halaman (publik & admin) = logo/favicon toko.
 * Urutan: setting "favicon" -> setting "logo" -> /favicon.ico. Hanya file yang benar-benar ada yang dipakai.
 */
class FaviconHelper
{
    private const MIME = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
    ];

    private static ?array $resolved = null;

    /** @return array{url: string, type: string} */
    public static function resolve(): array
    {
        if (self::$resolved !== null) {
            return self::$resolved;
        }

        foreach (['favicon', 'logo'] as $key) {
            $path = ltrim((string) Setting::get($key), '/');
            if ($path !== '' && Storage::disk('public')->exists($path)) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                return self::$resolved = [
                    'url' => '/storage/' . ImageHelper::encodePathForUrl($path),
                    'type' => self::MIME[$ext] ?? 'image/png',
                ];
            }
        }

        return self::$resolved = ['url' => '/favicon.ico', 'type' => 'image/x-icon'];
    }

    public static function getFaviconUrl($model = null): string
    {
        return self::resolve()['url'];
    }

    /** Tag favicon. Parameter $model diabaikan (dulu dipakai untuk favicon per halaman detail). */
    public static function renderFaviconTags($model = null): string
    {
        ['url' => $url, 'type' => $type] = self::resolve();
        $url = e($url);
        $type = e($type);

        return "<link rel=\"icon\" type=\"{$type}\" href=\"{$url}\">\n"
            . "    <link rel=\"shortcut icon\" type=\"{$type}\" href=\"{$url}\">\n"
            . "    <link rel=\"apple-touch-icon\" href=\"{$url}\">\n";
    }
}
