<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Project;
use App\Models\State;
use App\Support\Seo;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

/**
 * /sitemap.xml and /llms.txt. robots.txt is a static file written by
 * `php artisan seo:robots` (routes/console.php).
 */
class SeoController extends Controller
{
  /**
   * The fixed pages: route name => [link text, llms.txt description]
   */
  protected const PAGES = [
    'page.home' => ['Startseite', 'Ausgewählte Projekte und Aktuelles'],
    'page.worklist' => ['Werkliste', 'Alle Projekte, filterbar nach Jahr, Status und Programm'],
    'page.worklist.year' => ['Werkliste nach Jahr', null],
    'page.office.profile' => ['Profil', 'Geschichte, Partner und Tätigkeitsfeld des Büros'],
    'page.office.team' => ['Team', null],
    'page.office.publications' => ['Publikationen', 'Veröffentlichungen in Fach- und Tagespresse'],
    'page.office.awards' => ['Auszeichnungen', null],
    'page.office.jobs' => ['Jobs', 'Offene Stellen'],
    'page.contact' => ['Kontakt', 'Adresse, Impressum, Datenschutz'],
  ];

  public function sitemap(): Response
  {
    $urls = collect(array_keys(self::PAGES))->map(fn ($name) => ['loc' => route($name), 'lastmod' => null])
      ->concat($this->listings()->map(fn ($page) => ['loc' => $page['url'], 'lastmod' => null]))
      ->concat($this->projects()->map(fn ($p) => ['loc' => $p->getShowRoute(), 'lastmod' => $p->updated_at]));

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $url)
    {
      $xml .= "  <url>\n    <loc>" . e($url['loc']) . "</loc>\n";
      if ($url['lastmod'])
      {
        $xml .= '    <lastmod>' . $url['lastmod']->toAtomString() . "</lastmod>\n";
      }
      $xml .= "  </url>\n";
    }
    $xml .= "</urlset>\n";

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
  }

  /**
   * Markdown summary of the site for LLMs (https://llmstxt.org)
   */
  public function llms(): Response
  {
    $org = config('seo.organization');

    $lines = [
      '# ' . config('seo.title'),
      '',
      '> ' . config('seo.about'),
      '',
      "- Kontakt: {$org['name']}, {$org['street']}, {$org['zip']} {$org['city']}, {$org['phone']}, {$org['email']}",
      '',
      '## Website',
      '',
      ...collect(self::PAGES)->map(fn ($page, $name) => '- [' . $page[0] . '](' . route($name) . ')' . ($page[1] ? ': ' . $page[1] : ''))->values(),
      ...$this->listings()->map(fn ($page) => $this->item($page['title'], $page['url'], '')),
      '',
      '## Projekte',
      '',
      ...$this->projects()->map(fn ($p) => $this->item(
        implode(', ', array_filter([$p->title, $p->location])),
        $p->getShowRoute(),
        implode(': ', array_filter([$p->type, Seo::text($p->description ?: $p->text, 200)]))
      )),
      '',
      '## Optional',
      '',
      '- [Sitemap](' . url('sitemap.xml') . ')',
      '',
    ];

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/markdown; charset=UTF-8']);
  }

  /**
   * Projects with a detail page (published and featured, as the worklist
   * links them), newest first
   */
  protected function projects(): Collection
  {
    return Project::featured()
      ->with('states', 'categories')
      ->orderBy('year', 'DESC')
      ->orderBy('order')
      ->get()
      ->filter(fn ($p) => $p->getShowRoute() !== '#')
      ->values();
  }

  /**
   * Project overviews (as in the menu) and the worklist by status and
   * programme: [title, url]
   */
  protected function listings(): Collection
  {
    $pages = collect();
    $states = State::publish()->orderBy('order')->get();
    $categories = Category::publish()->orderBy('order')->get();

    foreach ($states as $state)
    {
      if ($state->has_landing)
      {
        $pages->push(['title' => 'Projekte: ' . $state->description, 'url' => route('page.project.showLandingByState', ['state' => $state->slug])]);
      }
      if ($state->hasCategories())
      {
        foreach ($categories as $category)
        {
          $featured = $category->featuredProjects()->whereHas('states', fn ($q) => $q->where('states.id', $state->id))->exists();
          if ($featured)
          {
            $pages->push(['title' => 'Projekte: ' . $state->description . ', ' . $category->description, 'url' => route('page.project.showLandingByCategory', ['state' => $state->slug, 'category' => $category->slug])]);
          }
        }
      }
    }

    foreach ($states as $state)
    {
      $pages->push(['title' => 'Werkliste: ' . $state->description, 'url' => route('page.worklist.state', ['state' => $state->slug])]);
    }
    foreach ($categories as $category)
    {
      $pages->push(['title' => 'Werkliste: ' . $category->description, 'url' => route('page.worklist.category', ['category' => $category->slug])]);
    }

    return $pages;
  }

  protected function item(string $title, string $url, string $text): string
  {
    $title = str_replace(['[', ']'], ['(', ')'], Seo::text($title, 200));

    return "- [{$title}]({$url})" . ($text !== '' ? ": {$text}" : '');
  }
}
