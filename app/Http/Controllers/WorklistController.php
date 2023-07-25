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
      'projects' => Project::with('coverImage')->published()->orderBy('order')->get(),
      'categories' => Category::with('publishedProjects')->orderBy('order')->get(),
      'states' => State::with('publishedProjects')->orderBy('order')->get(),
    ]);
  }
}
