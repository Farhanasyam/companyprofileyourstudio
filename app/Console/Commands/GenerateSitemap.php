<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
    protected $description = 'Refresh cache sitemap (sitemap dilayani dinamis oleh route /sitemap.xml)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Cache::forget('sitemap_xml');

        $this->info('Sitemap cache cleared. /sitemap.xml akan dibuat ulang pada request berikutnya.');
    }
}
