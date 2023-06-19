<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Models\Project;
use App\Models\Category;
use App\Models\State;
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
      'project' => $project,
      'category' => Category::where('slug', $category)->first(),
      'state' => State::where('slug', $state)->first(),
    ]);
  }
}
