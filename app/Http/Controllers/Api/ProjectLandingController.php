<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\Category;
use App\Models\ProjectLanding;
use Illuminate\Http\Request;

class ProjectLandingController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return response()->json([
      'categories' => Category::whereHas('projects', function($query) {
        $query->whereHas('states', function($q) {
          $q->where('has_landing', false);
        });
      })->get(),
      'states' => State::hasLanding()->get(),
    ]);
  }

  public function findCategoryItems(Category $category)
  {
    $items = ProjectLanding::with('image', 'project')->where('category_id', $category->id)->orderBy('position')->get();
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
    return response()->json([
      'items' => $data
    ]);
  }

  public function findStateItems(State $state)
  {
    $items = ProjectLanding::with('image', 'project')->where('state_id', $state->id)->orderBy('position')->get();
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
    return response()->json([
      'items' => $data
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $column = $request->get('column');
    $category_id = $request->get('category_id');
    $state_id = $request->get('state_id');

    // Get the item with the highest position
    $query = ProjectLanding::where('column', $column)->orderBy('position', 'desc');
    if ($category_id)
    {
      $query->where('category_id', $category_id);
    }
    else if ($state_id)
    {
      $query->where('state_id', $state_id);
    }
    $item = $query->first();
    $item = ProjectLanding::create([
      'column' => $request->get('column'),
      'position' => $item ? $item->position + 1 : 0,
      'publish' => 1,
      'project_id' => $request->get('project_id'),
      'image_id' => $request->get('image_id'),
      'category_id' => $request->get('category_id') ?? null,
      'state_id' => $request->get('state_id') ?? null,
    ]);
    return response()->json(['item' => $item->with('image', 'project')->find($item->id)]);
  }

  /**
   * Toggle the status a given teaser
   *
   * @param  ProjectLanding $projectLanding
   * @return \Illuminate\Http\Response
   */
  public function toggle(ProjectLanding $projectLanding)
  {
    $projectLanding->publish = !$projectLanding->publish;
    $projectLanding->save();
    return response()->json($projectLanding->publish);
  }

  /**
   * Update the order the teasers
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

   public function order(Request $request)
   {
     $items = $request->get('items');
     foreach($items as $item)
     {
       $t = ProjectLanding::find($item['id']);
       $t->position = $item['position'];
       $t->save(); 
     }
     return response()->json('successfully updated');
   }

  /**
   * Remove the specified resource from storage.
   *
   * @param  ProjectLanding $projectLanding
   * @return \Illuminate\Http\Response
   */
  public function destroy(ProjectLanding $projectLanding)
  {
    $tempProjectLanding = $projectLanding;
    $projectLanding->delete();
    $this->reorder($tempProjectLanding);
    return response()->json('successfully deleted');
  }

  public function reorder(ProjectLanding $projectLanding)
  {
    $category = $projectLanding->category_id ? Category::find($projectLanding->category_id) : null;
    $state = $projectLanding->state_id ? State::find($projectLanding->state_id) : null;
    $column = $projectLanding->column;

    // Get all project landings for this category and state and column
    $query = ProjectLanding::where('column', $column);

    if ($category)
    {
      $query->where('category_id', $category->id);
    }
    if ($state)
    {
      $query->where('state_id', $state->id);
    }

    $items = $query->orderBy('position')->get();
    $key = 0;

    foreach($items as $item)
    {
      $item->position = $key;
      $item->save();
      $key++;
    }
  }

}