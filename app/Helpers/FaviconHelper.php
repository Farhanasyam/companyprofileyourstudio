<?php

namespace App\Helpers;

class FaviconHelper
{
    /**
     * Get favicon for current page
     */
    public static function getFavicon($model = null)
    {
        // For detail pages, use logo1.png
        if ($model) {
            return 'images/logo1.png';
        }
        
        // Default favicon from settings for other pages
        $defaultFavicon = \App\Models\Setting::get('favicon');
        return $defaultFavicon;
    }
    
    /**
     * Get favicon URL for current page
     */
    public static function getFaviconUrl($model = null)
    {
        $favicon = self::getFavicon($model);
        
        if ($favicon) {
            return asset('storage/' . $favicon);
        }
        
        return asset('favicon.ico');
    }
    
    /**
     * Render favicon HTML tags
     */
    public static function renderFaviconTags($model = null)
    {
        $faviconUrl = self::getFaviconUrl($model);
        
        return "
        <link rel=\"icon\" type=\"image/x-icon\" href=\"{$faviconUrl}\">
        <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"{$faviconUrl}\">
        <link rel=\"apple-touch-icon\" href=\"{$faviconUrl}\">
        ";
    }
}
