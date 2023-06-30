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

}
