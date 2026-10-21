<?php
use Illuminate\Support\Facades\Artisan;

// Closure commands; the classes in app/Console/Commands are discovered

/**
 * Writes public/robots.txt for the current environment. Runs on every
 * composer install (see composer.json), so each server gets the right file:
 * production allows crawling and points to the sitemap, all others block
 * everything. A static file, because the web server answers /robots.txt
 * without asking Laravel.
 */
Artisan::command('seo:robots', function () {
  $robots = app()->isProduction()
    ? "User-agent: *\nDisallow: /administration\nDisallow: /api/\nDisallow: /login\nDisallow: /password/\nDisallow: /projekt/vorschau/\n\nSitemap: " . url('sitemap.xml') . "\n"
    : "User-agent: *\nDisallow: /\n";

  file_put_contents(public_path('robots.txt'), $robots);

  $this->info('robots.txt written for ' . app()->environment() . '.');
})->purpose('Write public/robots.txt for the current environment');
