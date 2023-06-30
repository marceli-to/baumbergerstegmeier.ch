<?php
namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Teaser;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
  protected $viewPath = 'pages.home.';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the homepage
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    return view($this->viewPath . 'index',
      [
        'coverProject'=> $this->getCoverProject(),
        'teasers' => $this->getTeasers(),
      ]
    );
  }

  private function getCoverProject()
  {
    // Get a random project by scope 'landing', the image must have an image with 'cover'
    $projects = Project::landing()->with('states', 'categories', 'coverImage')->get();

    // Filter out projects without a cover image
    $projects = $projects->filter(function ($project) {
      return $project->coverImage !== null;
    });

    // Select a random project from the collection
    if ($projects->count() > 0)
    {
      return $projects->random();
    }
    return NULL;
  }

  private function getTeasers()
  {
    $query = Teaser::publish()->with('image', 'project', 'article.publishedImage')->where('type', 'home');
    $items = $query->orderBy('position')->get();

    dd($items, $items->groupBy('column'));

    return $items->groupBy('column')->values();
  }

}
