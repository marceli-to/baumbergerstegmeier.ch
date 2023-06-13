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
    return new DataCollection(Project::with('images')->orderBy('order')->orderBy('year', 'DESC')->get());
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
        'types' => Type::orderBy('order')->get(),
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
      'title' => $request->input('title'),
      'text' => $request->input('text'),
      'info' => $request->input('info'),
      'periode' => $request->input('periode'),
      'year' => $request->input('year'),
      'location' => $request->input('location'),
      'type_id' => $request->input('type_id'),
    ]);
    $project->categories()->attach($request->input('category_ids'));
    $project->states()->attach($request->input('state_ids'));
    $this->handleFlag($project, 'isPublished', $request->input('publish'));
    $this->handleFlag($project, 'isFeatured', $request->input('feature'));
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
    $project->title = $request->input('title');
    $project->text = $request->input('text');
    $project->info = $request->input('info');
    $project->periode = $request->input('periode');
    $project->year = $request->input('year');
    $project->location = $request->input('location');
    $project->type_id = $request->input('type_id');
    $project->save();
    $project->categories()->sync($request->input('category_ids'));
    $project->states()->sync($request->input('state_ids'));
    $this->handleFlag($project, 'isPublished', $request->input('publish'));
    $this->handleFlag($project, 'isFeatured', $request->input('feature'));
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
    if ($project->hasFlag('isPublished'))
    {
      $project->unflag('isPublished');
    }
    else
    {
      $project->flag('isPublished');
    } 
    return response()->json($project->hasFlag('isPublished'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Project $project
   * @return \Illuminate\Http\Response
   */
  public function destroy(Project $project)
  {
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
   * Handle flags of a project
   *
   * @param Project $project
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Project $project, $flag, $value)
  {
    if ($value == 1)
    {
      $project->flag($flag);
    }
    else
    {
      $project->unflag($flag);
    }
    return $project->hasFlag($flag);
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