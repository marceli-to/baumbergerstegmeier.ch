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
      'slug' => \Str::slug($request->input('description')),
      'publish' => $request->input('publish')
    ]);
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
    $state->slug = \Str::slug($request->input('description'));
    $state->publish = $request->input('publish');
    $state->save();
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
    $state->publish = !$state->publish;
    $state->save();
    return response()->json($state->publish);
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

}