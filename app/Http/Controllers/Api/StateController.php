<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Http\Resources\DataCollection;
use App\Http\Requests\StateStoreRequest;
use Illuminate\Http\Request;

class StateController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(State::orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  State $state
   * @return \Illuminate\Http\Response
   */
  public function find(State $state)
  {
    return response()->json(['state' => State::find($state->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\StateStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StateStoreRequest $request)
  {
    $state = State::create([
      'description' => $request->input('description'),
    ]);
    $this->handleFlag($state, 'isPublished', $request->input('publish'));
    return response()->json(['stateId' => $state->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\StateStoreRequest  $request
   * @param  State $state
   * @return \Illuminate\Http\Response
   */
  public function update(StateStoreRequest $request, State $state)
  {
    $state = State::findOrFail($state->id);
    $state->description = $request->input('description');
    $state->save();
    $this->handleFlag($state, 'isPublished', $request->input('publish'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given state
   *
   * @param  State $state
   * @return \Illuminate\Http\Response
   */
  public function toggle(State $state)
  {
    if ($state->hasFlag('isPublished'))
    {
      $state->unflag('isPublished');
    }
    else
    {
      $state->flag('isPublished');
    } 
    return response()->json($state->hasFlag('isPublished'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  State $state
   * @return \Illuminate\Http\Response
   */
  public function destroy(State $state)
  {
    $state->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Update the order the categories
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

   public function order(Request $request)
   {
     $states = $request->get('states');
     foreach($states as $state)
     {
       $s = State::find($state['id']);
       $s->order = $state['order'];
       $s->save(); 
     }
     return response()->json('successfully updated');
   }

  /**
   * Handle flags of a state
   *
   * @param State $state
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(State $state, $flag, $value)
  {
    if ($value == 1)
    {
      $state->flag($flag);
    }
    else
    {
      $state->unflag($flag);
    }
    return $state->hasFlag($flag);
  }

}