<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

/**
 * Favicon situs: sama di semua halaman (publik & admin) = logo/favicon toko.
 * Urutan: setting "favicon" -> setting "logo" -> public/images/favicon.png (logo bawaan, ikut ter-deploy lewat git).
 * Hanya file yang benar-benar ada yang dipakai, jadi aman di hosting yang folder storage-nya belum lengkap.
 */
class FaviconHelper
{
    /** Logo bawaan di folder public (selalu ada setelah deploy) */
    public const BUNDLED = 'images/favicon.png';

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

    /** @return array{url: string, type: string, file: string} url publik, mime, path file di disk */
    public static function resolve(): array
    {
        if (self::$resolved !== null) {
            return self::$resolved;
        }

        foreach (['favicon', 'logo'] as $key) {
            $path = ltrim((string) Setting::get($key), '/');
            if ($path !== '' && Storage::disk('public')->exists($path)) {
                return self::$resolved = [
                    'url' => '/storage/' . ImageHelper::encodePathForUrl($path),
                    'type' => self::mime($path),
                    'file' => Storage::disk('public')->path($path),
                ];
            }
        }

        $file = public_path(self::BUNDLED);
        return self::$resolved = [
            // ?v= agar browser mengambil ulang bila logo bawaan diganti (file ini di-cache lama oleh .htaccess)
            'url' => '/' . self::BUNDLED . (is_file($file) ? '?v=' . filemtime($file) : ''),
            'type' => 'image/png',
            'file' => $file,
        ];
    }

    private static function mime(string $path): string
    {
        return self::MIME[strtolower(pathinfo($path, PATHINFO_EXTENSION))] ?? 'image/png';
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
