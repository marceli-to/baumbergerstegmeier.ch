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
    return view($this->viewPath . 'show', [
      'project' => Project::with('coverImage')->find($project->id),
      'teasers' => $this->getTeasers($project),
      'state' => State::where('slug', $state)->first(),
      'has_category' => false,
      'browse' => $this->getBrowseByState($project->id, $state),
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
    return view($this->viewPath . 'show', [
      'project' => Project::with('coverImage')->find($project->id),
      'teasers' => $this->getTeasers($project),
      'category' => Category::where('slug', $category)->first(),
      'has_category' => true,
      'state' => State::where('slug', $state)->first(),
      'browse' => $this->getBrowseByStateAndCategory($project->id, $state, $category),
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
    $project = Project::with('state', 'coverImage')->find($project->id);
    return view($this->viewPath . 'show', [
      'project' => $project,
      'teasers' => $this->getTeasers($project),
      'has_category' => $project->state->hasCategories(),
      'category' => $project->state->hasCategories() ? Category::first() : null,
      'state' => State::first(),
    ]);
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
   * Get project browse navigatino for state only
   * 
   * @param Integer $projectId
   * @param String $stateSlug
   * @return Array $items
   */
  
  private function getBrowseByState($projectId = NULL, $state = NULL)
  {
    // Get state
    $state = State::where('slug', $state)->first();

    // Get Landing projects for this state, order the by position and column
    $projectsByState = ProjectLanding::where('state_id', $state->id)->orderBy('position')->orderBy('column')->get();

    $browseProjects = [
      'id' => $state->id,
      'state' => $state,
      'hasCategories' => false,
      'projects' => $projectsByState,
    ];

    $keys  = [];
    $items = [];

    foreach($browseProjects['projects'] as $p)
    {
      $keys[] = $p->project_id;
    }

    // Get current key
    $key = array_search($projectId, $keys);

    if ($key == 0)
    {
      $prevId = end($keys);
      $nextId = isset($keys[$key+1]) ? $keys[$key+1] : NULL;
    }
    else if ($key == count($keys) - 1)
    {
      $prevId = $keys[$key-1];
      $nextId = $keys[0];
    }
    else
    {
      $prevId = $keys[$key-1];
      $nextId = $keys[$key+1];
    }

    // Get prev and next item by category, state and project
    $items = [
      'prev' => [
        'project' => Project::find($prevId),
        'route' => route('page.project.showByState', ['state' => $state->slug, 'project' => Project::find($prevId)->slug])
      ],
      'next' => [
        'project' => Project::find($nextId),
        'route' => route('page.project.showByState', ['state' => $state->slug, 'project' => Project::find($nextId)->slug])
      ],
    ];

    return $items;
  }

  /**
   * Get project browse navigation for state and category
   * 
   * @param Integer $projectId
   * @param String $stateSlug
   * @param String $categorySlug
   * @return Array $items
   */

  private function getBrowseByStateAndCategory($projectId = NULL, $state = NULL, $category = NULL)
  {
    // Get state
    $state = State::where('slug', $state)->first();

    // Get category
    $category = Category::where('slug', $category)->first();

    $projectsByStateAndCategory = ProjectLanding::where('category_id', $category->id)->orderBy('position')->orderBy('column')->get();

    $browseProjects = [
      'id' => $state->id,
      'state' => $state,
      'hasCategories' => true,
      'projects' => $projectsByStateAndCategory,
    ];

    $keys  = [];
    $items = [];

    foreach($browseProjects['projects'] as $p)
    {
      $keys[] = $p->project_id;
    }

      foreach($browseProjects['projects'] as $p)
      {
        $keys[] = $p->project_id;
      }
  
      // Get current key
      $key = array_search($projectId, $keys);
  
      if ($key == 0)
      {
        $prevId = end($keys);
        $nextId = isset($keys[$key+1]) ? $keys[$key+1] : NULL;
      }
      else if ($key == count($keys) - 1)
      {
        $prevId = $keys[$key-1];
        $nextId = $keys[0];
      }
      else
      {
        $prevId = $keys[$key-1];
        $nextId = $keys[$key+1];
      }


      // Get prev and next item by category, state and project
      $items = [
        'prev' => [
          'project' => Project::find($prevId),
          'route' => route('page.project.showByStateAndCategory', ['state' => $state->slug, 'category' => $category, 'project' => Project::find($prevId)->slug])
        ],
        'next' => [
          'project' => Project::find($nextId),
          'route' => route('page.project.showByStateAndCategory', ['state' => $state->slug, 'category' => $category, 'project' => Project::find($nextId)->slug])
        ],
      ];
      return $items;
    
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