<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Project;
use App\Models\ProjectLanding;
use App\Models\Category;
use App\Models\State;
use App\Models\Teaser;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
  protected $viewPath = 'pages.project.';

  /**
   * Show project landing page by state
   * 
   * @param State $state
   * @return \Illuminate\Http\Response
   */
  public function showLandingByState(State $state)
  {
    $items = ProjectLanding::with('project', 'project.coverImage')
      ->where('state_id', $state->id)
      ->orderBy('position')
      ->orderBy('column')
      ->get();

    return view($this->viewPath . 'landing', [
      'teaser_columns' => $this->buildColumns($items),
      'teasers' => $items,
      'state' => $state,
      'is_category' => false,
    ]);
  }

  /**
   * Show project landing page by state and category
   * 
   * @param State $state
   * @param Category $category
   * @return \Illuminate\Http\Response
   */

  public function showLandingByCategory(State $state, $category)
  {
    $category = Category::where('slug', $category)->first();
    $items = ProjectLanding::with('project', 'project.coverImage')
      ->where('category_id', $category->id)
      ->orderBy('position')
      ->orderBy('column')
      ->get();

    return view($this->viewPath . 'landing', [
      'teaser_columns' => $this->buildColumns($items),
      'teasers' => $items,
      'category' => $category,
      'state' => $state,
      'is_category' => true,
    ]);

  }

  /**
   * Show a project by state
   * @param String $state
   * @param String $category
   * @param Project $project
   *
   * @return \Illuminate\Http\Response
   */
  public function showByState($state, Project $project)
  {
    $this->ensureDetailPage($project);
    return view($this->viewPath . 'show', [
      'project' => Project::with('coverImage')->find($project->id),
      'teasers' => $this->getTeasers($project),
      'state' => State::where('slug', $state)->first(),
      'has_category' => false,
      'browse' => $this->getBrowseByState($project, $state),
    ]);
  }

  /**
   * Show a project by state and category
   * @param String $state
   * @param String $category
   * @param Project $project
   *
   * @return \Illuminate\Http\Response
   */
  public function showByStateAndCategory($state, $category, Project $project)
  {
    $this->ensureDetailPage($project);
    return view($this->viewPath . 'show', [
      'project' => Project::with('coverImage')->find($project->id),
      'teasers' => $this->getTeasers($project),
      'category' => Category::where('slug', $category)->first(),
      'has_category' => true,
      'state' => State::where('slug', $state)->first(),
      'browse' => $this->getBrowseByStateAndCategory($project, $state, $category),
    ]);
  }

  /**
   * Show a project in preview mode
   * @param Project $project
   *
   * @return \Illuminate\Http\Response
   */
  public function preview(Project $project)
  {
    $project = Project::with('states', 'coverImage')->find($project->id);
    $state = $project->states->first();
    return view($this->viewPath . 'show', [
      'project' => $project,
      'teasers' => $this->getTeasers($project),
      'has_category' => $state ? $state->hasCategories() : false,
      'category' => ($state && $state->hasCategories()) ? Category::first() : null,
      'state' => $state ?? State::first(),
    ]);
  }

  /**
   * Only published projects with a detail page have a public page; the
   * admin's preview (/projekt/vorschau) shows any
   *
   * @param Project $project
   * @return void
   */
  private function ensureDetailPage(Project $project)
  {
    abort_unless($project->publish == 1 && $project->feature == 1, 404);
  }

  private function getTeasers(Project $project)
  {
    $items = Teaser::projects()
              ->with('image', 'project', 'article.publishedImage')
              ->where('project_id', $project->id)
              ->orderBy('position')
              ->get();
    return $this->buildColumns($items);
  }

  /**
   * Prev/next navigation within a state's overview
   *
   * @param Project $project
   * @param String $state
   * @return Array
   */
  private function getBrowseByState(Project $project, $state)
  {
    $state = State::where('slug', $state)->firstOrFail();
    $items = ProjectLanding::where('state_id', $state->id)->orderBy('position')->orderBy('column')->get();

    return $this->browse($items, $project, fn (Project $other) => route('page.project.showByState', [
      'state' => $state->slug,
      'project' => $other->slug,
    ]));
  }

  /**
   * Prev/next navigation within a category's overview
   *
   * @param Project $project
   * @param String $state
   * @param String $category
   * @return Array
   */
  private function getBrowseByStateAndCategory(Project $project, $state, $category)
  {
    $state = State::where('slug', $state)->firstOrFail();
    $category = Category::where('slug', $category)->firstOrFail();
    $items = ProjectLanding::where('category_id', $category->id)->orderBy('position')->orderBy('column')->get();

    return $this->browse($items, $project, fn (Project $other) => route('page.project.showByStateAndCategory', [
      'state' => $state->slug,
      'category' => $category->slug,
      'project' => $other->slug,
    ]));
  }

  /**
   * The projects before and after this one in an overview's order (wrapping
   * around), among those with a detail page. Empty when the project isn't in
   * the overview or is the only one there.
   *
   * @param \Illuminate\Support\Collection $items landing items
   * @param Project $project
   * @param \Closure $route (Project) => url
   * @return Array
   */
  private function browse($items, Project $project, \Closure $route): Array
  {
    $projects = Project::featured()->whereIn('id', $items->pluck('project_id'))->get()->keyBy('id');
    $ids = $items->pluck('project_id')->unique()->filter(fn ($id) => $projects->has($id))->values();

    $key = $ids->search($project->id);
    if ($key === false || $ids->count() < 2) {
      return [];
    }

    $prev = $projects[$ids[($key - 1 + $ids->count()) % $ids->count()]];
    $next = $projects[$ids[($key + 1) % $ids->count()]];

    return [
      'prev' => ['project' => $prev, 'route' => $route($prev)],
      'next' => ['project' => $next, 'route' => $route($next)],
    ];
  }

  /**
   * Build columns for teaser and landing items
   * 
   * @param Array $items
   * @return Array $data
   */
  private function buildColumns($items = []): Array
  {
    $data = [];
    foreach($items as $item)
    {
      if ($item->column == 0)
      {
        $data[0][] = $item;
      }
      if ($item->column == 1)
      {
        $data[1][] = $item;
      }
      if ($item->column == 2)
      {
        $data[2][] = $item;
      }
    }
    ksort($data);
    return $data;
  }
}