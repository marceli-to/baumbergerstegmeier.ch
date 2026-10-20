<?php
/**
 * Status and body of every public page (crawled from the home page and a
 * few entry points) and every read-only admin API GET, one file per URL,
 * to compare a refactor against the code before it. After cra.ch.
 *
 *   APP_DEBUG=false CACHE_DRIVER=array CACHE_STORE=array php .rewrite/tools/snapshot.php /tmp/snap-before
 *   diff -r -x queries.tsv -x urls.txt /tmp/snap-before /tmp/snap-after
 *
 * Each request runs in a freshly booted app. API requests act as the first
 * admin; nothing is written (`state/` toggles are GETs that write, so they
 * are left out). `urls.txt` lists every /img/ URL the public pages emit.
 */

use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$root = dirname(__DIR__, 2);
require $root . '/vendor/autoload.php';

$out = $argv[1] ?? exit("usage: php snapshot.php <outdir>\n");
@mkdir($out, 0777, true);

function boot()
{
  global $root;
  $app = require $root . '/bootstrap/app.php';
  $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
  return $app;
}

function fetch(string $uri, bool $asAdmin): array
{
  $app = boot();
  $queries = 0;
  DB::listen(function () use (&$queries) { $queries++; });
  if ($asAdmin)
  {
    $user = User::where('role', 'admin')->orderBy('id')->first();
    Auth::guard('web')->setUser($user);
    Auth::guard('sanctum')->setUser($user);
  }
  $kernel = $app->make(Kernel::class);
  $request = Request::create($uri, 'GET', [], [], [], [
    'HTTP_ACCEPT' => str_starts_with($uri, '/api/') ? 'application/json' : 'text/html',
    'HTTP_HOST' => 'baumbergerstegmeier.ch.test',
    'HTTPS' => 'on',
  ]);
  $response = $kernel->handle($request);
  $location = $response->headers->get('Location');
  $body = $response->getContent();
  $kernel->terminate($request, $response);
  $app->flush();
  return [$response->getStatusCode(), $location, $body, $queries];
}

function normalise(string $body): string
{
  // Per-run values: CSRF token, Mix/Vite asset hashes
  $body = preg_replace('/(name="_token" value=|name="csrf-token" content=)"[^"]*"/', '$1"…"', $body);
  return $body;
}

// Ids from the database
$app = boot();
$ids = fn (string $table) => DB::table($table)->orderBy('id')->pluck('id')->all();
$tables = [
  'article' => 'articles', 'award' => 'awards', 'contact' => 'contact',
  'cv/category' => 'cv_categories', 'cvs' => 'cvs', 'employee/category' => 'employee_categories',
  'employee' => 'employees', 'file' => 'files', 'image' => 'images', 'job-article' => 'job_articles',
  'job' => 'job', 'profile' => 'profile', 'project/category' => 'categories',
  'project/state' => 'states', 'project' => 'projects', 'publication' => 'publications', 'team' => 'teams',
];
$idsByRoute = [];
foreach ($tables as $route => $table)
{
  $idsByRoute[$route] = $ids($table);
}
$employees = $ids('employees');
$categories = $ids('categories');
$states = $ids('states');
$projects = $ids('projects');
$app->flush();

$api = [
  '/api/user', '/api/articles', '/api/awards', '/api/contact', '/api/cv/categories',
  '/api/employee/categories', '/api/employees', '/api/files', '/api/images', '/api/job',
  '/api/job-articles', '/api/linklist', '/api/profile', '/api/project/categories',
  '/api/project/landing', '/api/project/states', '/api/projects', '/api/publications', '/api/teams',
];
foreach ($idsByRoute as $route => $list)
{
  foreach ($list as $id)
  {
    $api[] = "/api/{$route}/{$id}";
  }
}
foreach ($employees as $id)
{
  $api[] = "/api/cv/{$id}";
}
foreach ($categories as $id)
{
  $api[] = "/api/project/landing/items/category/{$id}";
  $api[] = "/api/projects/category/{$id}";
}
foreach ($states as $id)
{
  $api[] = "/api/project/landing/items/state/{$id}";
  $api[] = "/api/projects/state/{$id}";
}
foreach (['home', 'project'] as $type)
{
  $api[] = "/api/teasers/{$type}";
}
foreach ($projects as $id)
{
  $api[] = "/api/teasers/project/{$id}";
}

$counts = [];
$imgUrls = [];
$save = function (string $uri, bool $asAdmin) use ($out, &$counts) {
  [$status, $location, $body, $queries] = fetch($uri, $asAdmin);
  $body = normalise($body);
  $file = ($asAdmin ? 'admin' : 'guest') . str_replace(['/', '?'], '_', $uri);
  $file = strlen($file) > 200 ? substr($file, 0, 160) . '-' . md5($file) : $file;
  file_put_contents("{$out}/{$file}.txt", "{$status} {$location}\n{$body}");
  $counts[] = "{$queries}\t{$file}";
  return [$status, $body];
};

// Public pages: crawl internal links
$queue = ['/', '/projekte', '/werkliste', '/werkliste/jahr', '/buero/profil', '/buero/team',
  '/buero/publikationen', '/buero/auszeichnungen', '/buero/jobs', '/kontakt',
  '/login', '/password/reset', '/gibt-es-nicht'];
$seen = [];
while ($queue)
{
  $uri = array_shift($queue);
  if (isset($seen[$uri])) continue;
  $seen[$uri] = true;
  [$status, $body] = $save($uri, false);
  if ($status !== 200) continue;
  preg_match_all('#(?:href|src|srcset|data-srcset|content)="([^"]+)"#', $body, $m);
  foreach ($m[1] as $value)
  {
    // srcset entries are separated by ", "; crop coords contain bare commas
    foreach (preg_split('/,\s+/', $value) as $part)
    {
      $url = preg_replace('#^https?://baumbergerstegmeier\.ch\.test#', '', explode(' ', trim($part))[0]);
      if (str_starts_with($url, '/img/'))
      {
        $imgUrls[$url] = true;
        continue;
      }
      $url = strtok($url, '#');
      if (!$url || !str_starts_with($url, '/') || str_starts_with($url, '//')) continue;
      if (preg_match('#^/(assets|storage|build|api|administration|logout|email|mailto)#', $url)) continue;
      if (preg_match('#\.(pdf|jpe?g|png|svg|ico|xml|txt|css|js)$#i', $url)) continue;
      if (!isset($seen[$url])) $queue[] = $url;
    }
  }
}

// Unpublished projects: preview, as admin
foreach ($projects as $id)
{
  $save("/projekt/vorschau/{$id}", true);
}

foreach ($api as $uri)
{
  $save($uri, true);
}

ksort($imgUrls);
file_put_contents("{$out}/urls.txt", implode("\n", array_keys($imgUrls)) . "\n");
file_put_contents("{$out}/queries.tsv", implode("\n", $counts) . "\n");

echo count($counts) . " requests, " . count($seen) . " public pages, " . count($imgUrls) . " image URLs\n";
