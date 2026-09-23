<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Sitemap XML dinamis (cache 1 jam) agar selalu memakai APP_URL dan data terbaru.
     */
    public function sitemap()
    {
        $xml = Cache::remember('sitemap_xml', 3600, function () {
            $urls = [
                [route('home'), null, 'daily', '1.0'],
                [route('products.index'), null, 'weekly', '0.9'],
                [route('about'), null, 'monthly', '0.8'],
                [route('articles.index'), null, 'weekly', '0.8'],
                [route('events.index'), null, 'weekly', '0.8'],
                [route('gallery'), null, 'monthly', '0.7'],
                [route('contact'), null, 'monthly', '0.7'],
            ];

            foreach (Category::active()->get() as $category) {
                $urls[] = [route('products.category', $category), $category->updated_at, 'weekly', '0.7'];
            }
            foreach (Product::active()->get() as $product) {
                $urls[] = [route('products.show', $product), $product->updated_at, 'monthly', '0.8'];
            }
            foreach (Article::published()->get() as $article) {
                $urls[] = [route('articles.show', $article), $article->updated_at, 'monthly', '0.6'];
            }
            foreach (Event::published()->get() as $event) {
                $urls[] = [route('events.show', $event), $event->updated_at, 'weekly', '0.6'];
            }

            $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            foreach ($urls as [$loc, $lastmod, $changefreq, $priority]) {
                $out .= "  <url>\n";
                $out .= '    <loc>' . e($loc) . "</loc>\n";
                if ($lastmod) {
                    $out .= '    <lastmod>' . $lastmod->toDateString() . "</lastmod>\n";
                }
                $out .= "    <changefreq>{$changefreq}</changefreq>\n";
                $out .= "    <priority>{$priority}</priority>\n";
                $out .= "  </url>\n";
            }
            $out .= '</urlset>';

            return $out;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Disallow: /login',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
