<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

/**
 * Logo toko untuk navbar, loader, sidebar admin, dan SEO.
 * Urutan: logo dari Pengaturan admin (bila filenya ada di storage) -> public/images/logo.png (logo bawaan,
 * ikut ter-deploy lewat git). Jadi logo tetap tampil di hosting walau folder storage belum diunggah.
 */
class BrandHelper
{
    public const BUNDLED_LOGO = 'images/logo.png';

    private static ?string $logoUrl = null;

    /** URL relatif logo (mis. /storage/settings/xxx.png atau /images/logo.png?v=123), atau null bila tidak ada sama sekali */
    public static function logoUrl(): ?string
    {
        if (self::$logoUrl !== null) {
            return self::$logoUrl ?: null;
        }

        $path = ltrim((string) Setting::get('logo'), '/');
        if ($path !== '' && Storage::disk('public')->exists($path)) {
            return self::$logoUrl = '/storage/' . ImageHelper::encodePathForUrl($path);
        }

        $file = public_path(self::BUNDLED_LOGO);
        self::$logoUrl = is_file($file) ? '/' . self::BUNDLED_LOGO . '?v=' . filemtime($file) : '';

        return self::$logoUrl ?: null;
    }

    /** URL absolut logo (untuk JSON-LD / Open Graph) */
    public static function logoAbsoluteUrl(): ?string
    {
        $url = self::logoUrl();
        return $url ? rtrim(request()->getSchemeAndHttpHost(), '/') . $url : null;
    }
}
