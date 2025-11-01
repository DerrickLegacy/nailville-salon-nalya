<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for Nailville Salon website.';

    public function handle()
    {
        $domain = config('app.url', 'https://staging-nailville-salon.kenvies.com');
        $path = public_path('sitemap.xml');

        $this->info("Generating sitemap for {$domain}...");

        // Crawl the main site
        $sitemap = SitemapGenerator::create($domain)
            ->getSitemap();

        // Add extra URLs manually if they aren’t crawlable
        $sitemap
            ->add(Url::create('/about-us')->setLastModificationDate(Carbon::yesterday()))
            ->add(Url::create('/services')->setLastModificationDate(Carbon::yesterday()))
            ->add(Url::create('/gallery')->setLastModificationDate(Carbon::yesterday()))
            ->add(Url::create('/contact')->setLastModificationDate(Carbon::yesterday()));

        // Save sitemap
        $sitemap->writeToFile($path);

        $this->info("✅ Sitemap generated at: {$path}");
    }
}
