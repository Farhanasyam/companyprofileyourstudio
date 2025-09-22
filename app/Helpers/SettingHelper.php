<?php

namespace App\Helpers;

class SettingHelper
{
    /**
     * Get localized setting value
     */
    public static function getLocalized($key, $default = null)
    {
        $locale = app()->getLocale();
        $localizedKey = $key . '_' . $locale;
        
        // Try to get localized value first
        $value = \App\Models\Setting::get($localizedKey);
        
        // If no localized value, fallback to default key
        if (!$value) {
            $value = \App\Models\Setting::get($key, $default);
        }
        
        return $value;
    }

    /**
     * Get company name based on current locale
     */
    public static function getCompanyName()
    {
        return self::getLocalized('company_name', 'YourStudio');
    }

    /**
     * Get company tagline based on current locale
     */
    public static function getCompanyTagline()
    {
        return self::getLocalized('company_tagline', 'Wujudkan Kreativitas Anda Bersama Kami');
    }

    /**
     * Get company description based on current locale
     */
    public static function getCompanyDescription()
    {
        return self::getLocalized('company_description', 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik untuk mewujudkan kreativitas Anda');
    }

    /**
     * Get meta title based on current locale
     */
    public static function getMetaTitle()
    {
        return self::getLocalized('meta_title', 'YourStudio - Toko Alat Lukis dan Clay Terpercaya');
    }

    /**
     * Get meta description based on current locale
     */
    public static function getMetaDescription()
    {
        return self::getLocalized('meta_description', 'Toko alat lukis dan clay terpercaya dengan kualitas terbaik');
    }
}
