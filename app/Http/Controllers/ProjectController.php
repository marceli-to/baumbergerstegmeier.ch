<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Project;
use App\Models\Category;
use App\Models\State;
use App\Models\Teaser;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
  protected $viewPath = 'pages.project.';

  /**
   * Show the project page
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
  }

  /**
   * Show a project
   * @param String $state
   * @param String $category
   * @param Project $project
   *
   * @return \Illuminate\Http\Response
   */
  public function show($state, $category, Project $project)
  {
    return view($this->viewPath . 'show', [
      'project' => Project::with('coverImage')->find($project->id),
      'teasers' => $this->getTeasers($project),
      'category' => Category::where('slug', $category)->first(),
      'state' => State::where('slug', $state)->first(),
      'browse' => $this->getBrowse($project->id, $state, $category),
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
    return view($this->viewPath . 'show', [
      'project' => Project::with('coverImage')->find($project->id),
      'teasers' => $this->getTeasers($project),
      'category' => Category::first(),
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
    return $data;
  }

  /**
   * Get project browse navigation
   * 
   * @param Integer $projectId
   * @param String $stateSlug
   * @param String $categorySlug
   * @return Array $items
   */

  private function getBrowse($projectId = NULL, $stateSlug = NULL, $categorySlug = NULL)
  {
    // Get category and state by slug
    $category = Category::where('slug', $categorySlug)->first();
    $state = State::where('slug', $stateSlug)->first();

    // Get all projects in the same category and state. 
    // Category and state are in a many to many relationship
    $projects = Project::featured()->with('coverImage')->whereHas('categories', function($q) use ($category) {
      $q->where('category_id', $category->id);
    })->whereHas('states', function($q) use ($state) {
      $q->where('state_id', $state->id);
    })->orderBy('order')->get();
      
    $keys  = [];
    $items = [];

    foreach($projects as $p)
    {
      $keys[] = (int) $p->id;
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

    $items = [
      'prev' => Project::find($prevId),
      'next' => Project::find($nextId),
    ];
    return $items;
  }
}
