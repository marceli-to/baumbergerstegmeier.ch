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
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  { 
    $searchTerm = NULL;
    if ($request->input('searchTerm'))
    {
      $searchTerm = $request->input('searchTerm');
      $projects = Project::with('coverImage', 'states', 'categories')
                    ->whereLike('title', $searchTerm)
                    ->orWhereLike('text', $searchTerm)
                    ->orWhereLike('info', $searchTerm)
                    ->orWhereLike('location', $searchTerm)
                    ->orWhereLike('type', $searchTerm)
                    ->orderBy('year', 'DESC')
                    ->published()
                    ->get();
    }
    else
    {
      $projects = Project::with('coverImage', 'states', 'categories')
                    ->published()
                    ->orderBy('year', 'DESC')
                    ->get();
    }

    return view($this->viewPath . 'index', [
      'filter' => 'all',
      'searchTerm' => $searchTerm,
      'projects' => $projects,
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
    $projects = Project::with('coverImage', 'states', 'categories')->published()->where('state_id', $state->id)->orderBy('year', 'DESC')->get();

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
    })->orderBy('year', 'DESC')->get();

    return view($this->viewPath . 'index', [
      'filter' => 'category',
      'projects' => $projects,
      'categories' => Category::with('publishedProjects')->orderBy('order')->get(),
      'states' => State::with('publishedProjects')->orderBy('order')->get(),
      'category' => $category
    ]);
  }
}
