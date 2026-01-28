<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Category;
use App\Models\State;
use Illuminate\Http\Request;

class BaseController extends Controller
{
  public function __construct()
  {
    $menuProjects = [];
    $states = State::publish()->orderBy('order')->get();
    foreach($states as $state)
    {
      if ($state->show_in_menu)
      {
        // get all categories with featuredProjects that have this state
        $projectsByCategories = Category::with(['featuredProjects' => function ($query) use ($state) {
          $query->whereHas('states', fn($q) => $q->where('states.id', $state->id))->orderBy('year', 'DESC');
        }])->publish()->orderBy('order')->get();

        // filter out categories that don't have any featured projects
        $projectsByCategories = $projectsByCategories->filter(function ($category) {
          return $category->featuredProjects->count() > 0;
        });

        // if there are any categories left, add them to the menuProjects array
        if ($projectsByCategories->count() > 0)
        {
          $menuProjects[$state->order] = [
            'id' => $state->id,
            'state' => $state,
            'hasCategories' => true,
            'categories' => $projectsByCategories,
          ];
        }
      }
      else
      {
        // get all projects for this state, order the projects by year
        $projectsByState = Project::featured()
          ->whereHas('states', fn($q) => $q->where('states.id', $state->id))
          ->publish()
          ->orderBy('year', 'DESC')
          ->get();

        $menuProjects[$state->order] = [
          'id' => $state->id,
          'state' => $state,
          'hasCategories' => false,
          'featuredProjects' => $projectsByState,
        ];
      }
    }
    view()->share('menuProjects', $menuProjects);
  }
}
