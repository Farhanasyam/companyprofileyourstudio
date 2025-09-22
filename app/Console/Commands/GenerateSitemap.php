<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap for SEO';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $sitemap .= $this->addUrl(url('/'), '1.0', 'daily');

        // Static pages
        $sitemap .= $this->addUrl(route('about'), '0.8', 'monthly');
        $sitemap .= $this->addUrl(route('contact'), '0.7', 'monthly');
        $sitemap .= $this->addUrl(route('products.index'), '0.9', 'weekly');
        $sitemap .= $this->addUrl(route('articles.index'), '0.8', 'weekly');

        // Products
        $products = \App\Models\Product::active()->get();
        foreach ($products as $product) {
            $sitemap .= $this->addUrl(route('products.show', $product), '0.8', 'monthly');
        }

        // Categories
        $categories = \App\Models\Category::active()->get();
        foreach ($categories as $category) {
            $sitemap .= $this->addUrl(route('products.category', $category), '0.7', 'weekly');
        }

        // Articles
        $articles = \App\Models\Article::published()->get();
        foreach ($articles as $article) {
            $sitemap .= $this->addUrl(route('articles.show', $article), '0.6', 'monthly');
        }

        $sitemap .= '</urlset>';

        // Save sitemap
        file_put_contents(public_path('sitemap.xml'), $sitemap);

        $this->info('Sitemap generated successfully at public/sitemap.xml');
    }

    private function addUrl($url, $priority, $changefreq)
    {
        return "  <url>\n" .
               "    <loc>{$url}</loc>\n" .
               "    <lastmod>" . date('Y-m-d') . "</lastmod>\n" .
               "    <changefreq>{$changefreq}</changefreq>\n" .
               "    <priority>{$priority}</priority>\n" .
               "  </url>\n";
    }
}
