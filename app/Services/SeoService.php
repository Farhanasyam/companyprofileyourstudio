<?php

namespace App\Services;

use App\Models\Setting;
use App\Helpers\SettingHelper;

class SeoService
{
    protected $title;
    protected $description;
    protected $keywords;
    protected $image;
    protected $url;
    protected $type = 'website';

    public function __construct()
    {
        $this->title = SettingHelper::getMetaTitle();
        $this->description = SettingHelper::getMetaDescription();
        $this->keywords = Setting::get('meta_keywords', 'alat lukis, clay, cat air, cat minyak, kuas, kanvas, tanah liat');
        $this->image = $this->defaultImage();
        $this->url = url()->current();
    }

    /** URL absolut dari domain saat ini (agar OG/image meta benar di hosting) */
    protected function absoluteUrl(string $path): string
    {
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        $base = rtrim(request()->getSchemeAndHttpHost(), '/');
        return $base . '/' . ltrim($path, '/');
    }

    /** URL absolut untuk file di storage berdasarkan key setting, atau null */
    protected function storageSettingUrl(string $key): ?string
    {
        $path = Setting::get($key);
        return $path ? $this->absoluteUrl('storage/' . ltrim($path, '/')) : null;
    }

    /** Gambar default: og_image dari admin, lalu logo, lalu tidak ada */
    protected function defaultImage(): ?string
    {
        return $this->storageSettingUrl('og_image') ?: $this->storageSettingUrl('logo');
    }

    /**
     * Override global dari admin (og_title, twitter_*, canonical_url) hanya
     * berlaku di beranda, supaya tidak menimpa data per halaman.
     */
    protected function isHomepage(): bool
    {
        return request()->routeIs('home');
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function setImage($image)
    {
        // Kosong = tetap pakai gambar default; relatif (/storage/...) = jadikan absolut
        if ($image) {
            $this->image = $this->absoluteUrl($image);
        }
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getType()
    {
        return $this->type;
    }

    public function generateMetaTags()
    {
        $home = $this->isHomepage();

        $ogTitle = $home ? SettingHelper::getLocalized('og_title', $this->title) : $this->title;
        $ogDescription = $home ? SettingHelper::getLocalized('og_description', $this->description) : $this->description;
        $ogImage = $this->image;

        $twitterTitle = $home ? SettingHelper::getLocalized('twitter_title', $ogTitle) : $this->title;
        $twitterDescription = $home ? SettingHelper::getLocalized('twitter_description', $ogDescription) : $this->description;
        $twitterImage = ($home ? $this->storageSettingUrl('twitter_image') : null) ?: $this->image;

        // Canonical = URL halaman itu sendiri (tanpa query string);
        // canonical_url dari admin hanya dipakai untuk beranda.
        $canonical = ($home ? Setting::get('canonical_url') : null) ?: $this->url;

        return [
            // Basic Meta Tags
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'canonical' => $canonical,
            'robots' => Setting::get('robots', 'index, follow'),

            // Open Graph Tags
            'og:title' => $ogTitle,
            'og:description' => $ogDescription,
            'og:image' => $ogImage,
            'og:url' => $this->url,
            'og:type' => $this->type,
            'og:site_name' => SettingHelper::getCompanyName(),

            // Twitter Card Tags
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $twitterTitle,
            'twitter:description' => $twitterDescription,
            'twitter:image' => $twitterImage,
            'twitter:site' => '@yourstudio',

            // Additional Meta Tags
            'author' => SettingHelper::getCompanyName(),
            'theme-color' => '#667eea',
        ];
    }

    public function renderMetaTags()
    {
        $metaTags = $this->generateMetaTags();
        $html = '';

        // Title
        $html .= '<title>' . e($metaTags['title']) . '</title>' . "\n";

        // Basic Meta Tags
        $html .= '<meta name="description" content="' . e($metaTags['description']) . '">' . "\n";
        $html .= '<meta name="keywords" content="' . e($metaTags['keywords']) . '">' . "\n";
        $html .= '<meta name="author" content="' . e($metaTags['author']) . '">' . "\n";
        $html .= '<meta name="robots" content="' . e($metaTags['robots']) . '">' . "\n";
        $html .= '<meta name="theme-color" content="' . e($metaTags['theme-color']) . '">' . "\n";

        // Canonical URL
        if ($metaTags['canonical']) {
            $html .= '<link rel="canonical" href="' . e($metaTags['canonical']) . '">' . "\n";
        }

        // Open Graph Tags
        $html .= '<meta property="og:title" content="' . e($metaTags['og:title']) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . e($metaTags['og:description']) . '">' . "\n";
        if ($metaTags['og:image']) {
            $html .= '<meta property="og:image" content="' . e($metaTags['og:image']) . '">' . "\n";
        }
        $html .= '<meta property="og:url" content="' . e($metaTags['og:url']) . '">' . "\n";
        $html .= '<meta property="og:type" content="' . e($metaTags['og:type']) . '">' . "\n";
        $html .= '<meta property="og:site_name" content="' . e($metaTags['og:site_name']) . '">' . "\n";

        // Twitter Card Tags
        $html .= '<meta name="twitter:card" content="' . e($metaTags['twitter:card']) . '">' . "\n";
        $html .= '<meta name="twitter:title" content="' . e($metaTags['twitter:title']) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . e($metaTags['twitter:description']) . '">' . "\n";
        if ($metaTags['twitter:image']) {
            $html .= '<meta name="twitter:image" content="' . e($metaTags['twitter:image']) . '">' . "\n";
        }
        $html .= '<meta name="twitter:site" content="' . e($metaTags['twitter:site']) . '">' . "\n";

        return $html;
    }

    public function generateJsonLd()
    {
        $companyName = SettingHelper::getCompanyName();
        $companyDescription = SettingHelper::getCompanyDescription();
        $companyAddress = Setting::get('company_address', '');
        $companyPhone = Setting::get('company_phone', '');
        $companyEmail = Setting::get('company_email', '');

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $companyName,
            'description' => $companyDescription,
            'url' => url('/'),
            'logo' => $this->storageSettingUrl('logo'),
        ];

        if ($companyAddress) {
            $jsonLd['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $companyAddress,
            ];
        }

        if ($companyPhone) {
            $jsonLd['telephone'] = $companyPhone;
        }

        if ($companyEmail) {
            $jsonLd['email'] = $companyEmail;
        }

        // Social Media
        $socialMedia = [];
        if (Setting::get('instagram_url')) {
            $socialMedia[] = Setting::get('instagram_url');
        }
        if (Setting::get('facebook_url')) {
            $socialMedia[] = Setting::get('facebook_url');
        }
        if (Setting::get('youtube_url')) {
            $socialMedia[] = Setting::get('youtube_url');
        }

        if (!empty($socialMedia)) {
            $jsonLd['sameAs'] = $socialMedia;
        }

        return json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function renderJsonLd()
    {
        return '<script type="application/ld+json">' . $this->generateJsonLd() . '</script>';
    }
}
