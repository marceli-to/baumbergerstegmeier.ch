<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Type;
use App\Models\State;
use App\Models\Category;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\ProjectStoreRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Project::with('publishedImages')->orderBy('year', 'DESC')->orderBy('order')->get());
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getByCategory(Category $category)
  {
    return new DataCollection(
      Project::featured()->with('publishedImages', 'states')
        ->whereHas('categories', fn($q) => $q->where('category_id', $category->id))
        ->whereHas('states', fn($q) => $q->where('has_landing', false))
        ->orderBy('title')
        ->get()
    );
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getByState(State $state)
  {
    return new DataCollection(
      Project::featured()
        ->with('publishedImages')
        ->whereHas('states', fn($q) => $q->where('states.id', $state->id))
        ->orderBy('title')
        ->get()
    );
  }

  /**
   * Display the specified resource.
   *
   * @param  Project $project
   * @return \Illuminate\Http\Response
   */
  public function find(Project $project)
  {
    return response()->json(
      [
        'project' => Project::with('images', 'categories', 'states')->find($project->id),
        'states' => State::orderBy('order')->get(),
        'categories' => Category::get(),
      ]
    );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\ProjectStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProjectStoreRequest $request)
  {
    $project = Project::create([
      'slug' => 'null',
      'title' => $request->input('title'),
      'title_menu' => $request->input('title_menu'),
      'title_worklist' => $request->input('title_worklist'),
      'description' => $request->input('description') ?? null,
      'text' => $request->input('text'),
      'info' => $request->input('info'),
      'year' => $request->input('year'),
      'type' => $request->input('type'),
      'location' => $request->input('location'),
      'publish' => $request->input('publish'),
      'feature' => $request->input('feature'),
      'landing' => $request->input('landing'),
    ]);
    $project->slug = \AppHelper::slug($request->input('title')) . '-' . $project->id;
    $project->save();
    $project->categories()->attach($request->input('category_ids'));
    $project->states()->attach($request->input('state_ids'));
    $this->handleImages($project, $request->input('images'));
    return response()->json(['projectId' => $project->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\ProjectStoreRequest  $request
   * @param  Project $project
   * @return \Illuminate\Http\Response
   */
  public function update(ProjectStoreRequest $request, Project $project)
  {
    $project = Project::findOrFail($project->id);
    $project->slug = \AppHelper::slug($request->input('title')) . '-' . $project->id;
    $project->title = $request->input('title');
    $project->title_menu = $request->input('title_menu');
    $project->title_worklist = $request->input('title_worklist');
    $project->description = $request->input('description') ?? null;
    $project->text = $request->input('text');
    $project->info = $request->input('info');
    $project->year = $request->input('year');
    $project->type = $request->input('type');
    $project->location = $request->input('location');
    $project->publish = $request->input('publish');
    $project->feature = $request->input('feature');
    $project->landing = $request->input('landing');
    $project->save();
    $project->categories()->sync($request->input('category_ids'));
    $project->states()->sync($request->input('state_ids'));
    $this->handleImages($project, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given project
   *
   * @param  Project $project
   * @return \Illuminate\Http\Response
   */
  public function toggle(Project $project)
  {
    $project->publish = !$project->publish;
    $project->save();
    return response()->json($project->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Project $project
   * @return \Illuminate\Http\Response
   */
  public function destroy(Project $project)
  {
    $project->categories()->detach();
    $project->states()->detach();
    $project->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Update the order the projects
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $projects = $request->get('projects');
    foreach($projects as $project)
    {
      $p = Project::find($project['id']);
      $p->order = $project['order'];
      $p->save(); 
    }
    return response()->json('successfully updated');
  }

  /**
   * Handle associated images
   *
   * @param Project $project
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Project $project, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $project->id;
      $i->imageable_type = Project::class;
      $i->save();
    }
  }
}
