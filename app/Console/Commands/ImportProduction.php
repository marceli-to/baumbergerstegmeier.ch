<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

/**
 * Loads a production dump and production's storage/app/public from the
 * gitignored _import/ folder (one .sql file and public/) into this
 * (non-production) install, following the deploy order in
 * .rewrite/08-deploy.md: migrate if anything is pending, drop the old
 * image-cache renditions, start Glide from an empty cache. With --check it
 * then requests every sitemap URL and every /img/ URL on those pages.
 *
 * Replaces the local database and storage/app/public without a backup.
 */
class ImportProduction extends Command
{
  protected $signature = 'data:import-production
    {--check : Request every sitemap URL and every image on those pages afterwards}
    {--force : Don\'t ask for confirmation}';

  protected $description = 'Replace the local database and storage/app/public with production\'s';

  public function handle(): int
  {
    if (app()->isProduction()) {
      $this->error('Refused: this would overwrite the production database.');
      return self::FAILURE;
    }

    $dir = base_path('_import');
    $dumps = glob("{$dir}/*.sql");
    $files = "{$dir}/public";
    $target = storage_path('app/public');
    $db = config('database.connections.mysql');

    if (count($dumps) !== 1) {
      $this->error('_import/ needs exactly one .sql file (the production dump), found ' . count($dumps) . '.');
      return self::FAILURE;
    }
    $dump = $dumps[0];
    if (!is_dir("{$files}/uploads")) {
      $this->error("No _import/public/uploads: copy production's storage/app/public to _import/public.");
      return self::FAILURE;
    }
    if (config('database.default') !== 'mysql') {
      $this->error('Only the mysql connection is supported.');
      return self::FAILURE;
    }

    $this->line("Database <comment>{$db['database']}</comment> on {$db['host']}:{$db['port']} is dropped and loaded from {$dump}.");
    $this->line("<comment>{$target}</comment> is deleted and replaced with {$files}.");
    if (!$this->option('force') && !$this->confirm('No backup is made. Go on?')) {
      return self::FAILURE;
    }

    // Database
    $this->components->task('Database: drop, create, import', function () use ($db, $dump) {
      $name = str_replace('`', '', $db['database']);
      $this->mysql($db, null, "DROP DATABASE IF EXISTS `{$name}`; CREATE DATABASE `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
      $this->mysql($db, $dump);
      DB::purge('mysql');
    });

    // Deploy step 4
    $pending = $this->pendingMigrations();
    if ($pending) {
      $this->warn(count($pending) . ' migration(s) pending, running them: ' . implode(', ', $pending));
      $this->call('migrate', ['--force' => true]);
    } else {
      $this->components->info('No migrations pending (production has run them all).');
    }

    // Files
    $this->components->task('Files: replace storage/app/public', function () use ($files, $target) {
      File::deleteDirectory($target);
      if (!@rename($files, $target)) {
        File::copyDirectory($files, $target);
        File::deleteDirectory($files);
      }
    });

    // Deploy step 7, and Glide from cold
    $this->components->task('Drop storage/app/public/cache and the Glide cache', function () use ($target) {
      File::deleteDirectory("{$target}/cache");
      File::deleteDirectory(storage_path('app/.glide-cache'));
    });

    $this->callSilently('storage:link', ['--force' => true]);
    $this->callSilently('optimize:clear');

    $this->report();

    if ($this->option('check')) {
      return $this->check();
    }

    return self::SUCCESS;
  }

  private function mysql(array $db, ?string $file, ?string $sql = null): void
  {
    $command = ['mysql', '-h', $db['host'], '-P', (string) $db['port'], '-u', $db['username']];
    $command = $sql ? [...$command, '-e', $sql] : [...$command, $db['database']];

    $process = Process::env(['MYSQL_PWD' => (string) $db['password']])->timeout(600);
    if ($file) {
      $process = $process->input(fopen($file, 'r'));
    }

    $process->run($command)->throw();
  }

  private function pendingMigrations(): array
  {
    $ran = DB::table('migrations')->pluck('migration')->all();
    $all = collect(File::files(database_path('migrations')))
      ->map(fn($f) => $f->getFilenameWithoutExtension())
      ->all();

    return array_values(array_diff($all, $ran));
  }

  private function report(): void
  {
    $missing = DB::table('images')->select('name', 'deleted_at', 'imageable_type')->get()
      ->reject(fn($i) => is_file(storage_path("app/public/uploads/{$i->name}")));
    $visible = $missing->whereNull('deleted_at');

    $this->table(['', 'count'], [
      ['projects', DB::table('projects')->count()],
      ['images', DB::table('images')->count()],
      ['files', DB::table('files')->count()],
      ['users (unverified)', DB::table('users')->count() . ' (' . DB::table('users')->whereNull('email_verified_at')->count() . ')'],
      ['image rows without a file', $missing->count() . ' (' . $visible->count() . ' not soft-deleted)'],
    ]);

    foreach ($visible as $image) {
      $this->warn("Missing file of a live image: {$image->name} ({$image->imageable_type})");
    }
  }

  /**
   * Every sitemap URL, then every /img/ URL on those pages, against APP_URL
   * (the local certificate is not verified).
   */
  private function check(): int
  {
    $base = rtrim(config('app.url'), '/');
    $http = fn() => Http::withoutVerifying()->timeout(120);
    $logBefore = is_file($log = storage_path('logs/laravel.log')) ? filesize($log) : 0;

    $sitemap = $http()->get("{$base}/sitemap.xml")->body();
    preg_match_all('#<loc>([^<]+)</loc>#', $sitemap, $m);
    $pages = array_map(fn($u) => $base . (parse_url($u, PHP_URL_PATH) ?? ''), $m[1]);

    $failed = [];
    $images = [];
    $this->withProgressBar($pages, function ($url) use ($http, &$failed, &$images) {
      $response = $http()->get($url);
      if ($response->status() !== 200) {
        $failed[] = "{$response->status()} {$url}";
      }
      // Relative (img, srcset) and absolute (og:image) URLs; not /assets/img/
      preg_match_all('#(?<=["\s])(?:https?://[^/"\s]+)?(/img/[^"\s]+)#', $response->body(), $m);
      foreach ($m[1] as $src) {
        $images[html_entity_decode($src)] = true;
      }
    });
    $this->newLine();
    $this->components->info(count($pages) . ' pages, ' . count($images) . ' image URLs');

    $bar = $this->output->createProgressBar(count($images));
    foreach (array_chunk(array_keys($images), 8) as $chunk) {
      $responses = Http::pool(fn(Pool $pool) => array_map(
        fn($src) => $pool->as($src)->withoutVerifying()->timeout(120)->get($base . $src),
        $chunk
      ));
      foreach ($responses as $src => $response) {
        $status = $response instanceof \Throwable ? 'error' : $response->status();
        if ($status !== 200) {
          $failed[] = "{$status} {$src}";
        }
      }
      $bar->advance(count($chunk));
    }
    $bar->finish();
    $this->newLine(2);

    $logGrew = is_file($log) && filesize($log) > $logBefore;

    foreach ($failed as $line) {
      $this->error($line);
    }
    if ($logGrew) {
      $this->warn('storage/logs/laravel.log has new entries.');
    }
    if (!$failed && !$logGrew) {
      $this->components->info('All pages and images answer 200, nothing new in the log.');
    }

    return $failed || $logGrew ? self::FAILURE : self::SUCCESS;
  }
}
