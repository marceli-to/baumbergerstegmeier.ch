<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\TeamStoreRequest;
use Illuminate\Http\Request;

class TeamController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Team::with('images')->orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Team $team
   * @return \Illuminate\Http\Response
   */
  public function find(Team $team)
  {
    return response()->json(['team' => Team::with('images')->find($team->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\TeamStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(TeamStoreRequest $request)
  {
    $team = Team::create([
      'name' => $request->input('name'),
    ]);
    $this->handleFlag($team, 'isPublish', $request->input('publish'));
    $this->handleImages($team, $request->input('images'));
    return response()->json(['teamId' => $team->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\TeamStoreRequest  $request
   * @param  Team $team
   * @return \Illuminate\Http\Response
   */
  public function update(TeamStoreRequest $request, Team $team)
  {
    $team = Team::findOrFail($team->id);
    $team->name = $request->input('name');
    $team->save();
    $this->handleFlag($team, 'isPublish', $request->input('publish'));
    $this->handleImages($team, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given team
   *
   * @param  Team $team
   * @return \Illuminate\Http\Response
   */
  public function toggle(Team $team)
  {
    if ($team->hasFlag('isPublish'))
    {
      $team->unflag('isPublish');
    }
    else
    {
      $team->flag('isPublish');
    } 
    return response()->json($team->hasFlag('isPublish'));
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Team $team
   * @return \Illuminate\Http\Response
   */
  public function destroy(Team $team)
  {
    $team->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Update the order the teams
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function order(Request $request)
  {
    $teams = $request->get('teams');
    foreach($teams as $team)
    {
      $p = Team::find($team['id']);
      $p->order = $team['order'];
      $p->save(); 
    }
    return response()->json('successfully updated');
  }

  /**
   * Handle flags of a team
   *
   * @param Team $team
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Team $team, $flag, $value)
  {
    if ($value == 1)
    {
      $team->flag($flag);
    }
    else
    {
      $team->unflag($flag);
    }
    return $team->hasFlag($flag);
  }
  /**
   * Handle associated images
   *
   * @param Team $team
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Team $team, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $team->id;
      $i->imageable_type = Team::class;
      $i->save();
    }
  }
}