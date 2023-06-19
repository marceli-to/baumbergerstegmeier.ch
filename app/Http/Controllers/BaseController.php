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
    $states = State::publish()->with('featuredProjects.categories')->orderBy('order')->get();
    $menuProjects = [];
    foreach ($states as $state)
    {
      if ($state->featuredProjects->count() > 0)
      {
        $menuProjects[$state->slug] = [
          'id' => $state->id,
          'slug' => $state->slug,
          'description' => $state->description,
          'order' => $state->order,
          'projects' => $state->featuredProjects,
          'categories' => [],
        ];

        // get all categories with published projects for this state, order the projects by order
        $menuProjects[$state->slug]['categories'] = Category::whereHas('featuredProjects', function ($query) use ($state) {
          $query->whereHas('states', function ($query) use ($state) {
            $query->where('state_id', '=', $state->id);
          });
        })->with(['featuredProjects' => function ($query) use ($state) {
          $query->whereHas('states', function ($query) use ($state) {
            $query->where('state_id', '=', $state->id);
          })->orderBy('order');
        }])->publish()->orderBy('order')->get();
      }
    }
    
    view()->share('menuProjects', $menuProjects);
  }
}
