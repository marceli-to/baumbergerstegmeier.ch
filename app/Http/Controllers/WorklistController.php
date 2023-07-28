<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\State;
use App\Models\Project;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class WorklistController extends BaseController
{
  protected $viewPath = 'pages.worklist.';

  /**
   * Show the worklist page
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view($this->viewPath . 'index', [
      'filter' => 'all',
      'projects' => Project::with('coverImage', 'states', 'categories')->published()->orderBy('order')->get(),
      'categories' => Category::with('publishedProjects')->orderBy('order')->get(),
      'states' => State::with('publishedProjects')->orderBy('order')->get(),
    ]);
  }

  /**
   * Show entries grouped by year
   */
  public function byYear()
  { 
    $projects = Project::with('coverImage', 'states', 'categories')->published()->orderBy('year', 'DESC')->get();
    return view($this->viewPath . 'index', [
      'filter' => 'year',
      'projects' => $projects->groupBy('year'),
      'categories' => Category::with('publishedProjects')->orderBy('order')->get(),
      'states' => State::with('publishedProjects')->orderBy('order')->get(),
    ]);
  }

  /**
   * Show entries by state
   */

  public function byState(State $state)
  {
    $projects = Project::with('coverImage', 'states', 'categories')->published()->whereHas('states', function($query) use ($state) {
      $query->where('state_id', $state->id);
    })->orderBy('order')->get();

    return view($this->viewPath . 'index', [
      'filter' => 'state',
      'projects' => $projects,
      'categories' => Category::with('publishedProjects')->orderBy('order')->get(),
      'states' => State::with('publishedProjects')->orderBy('order')->get(),
      'state' => $state
    ]);
  }

  /**
   * Show entries by category
   */

  public function byCategory(Category $category)
  {
    $projects = Project::with('coverImage', 'states', 'categories')->published()->whereHas('categories', function($query) use ($category) {
      $query->where('category_id', $category->id);
    })->orderBy('order')->get();

    return view($this->viewPath . 'index', [
      'filter' => 'category',
      'projects' => $projects,
      'categories' => Category::with('publishedProjects')->orderBy('order')->get(),
      'states' => State::with('publishedProjects')->orderBy('order')->get(),
      'category' => $category
    ]);
  }
}
