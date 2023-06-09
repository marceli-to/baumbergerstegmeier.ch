<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Http\Resources\DataCollection;
use App\Http\Requests\TypeStoreRequest;
use Illuminate\Http\Request;

class TypeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Type::orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Type $type
   * @return \Illuminate\Http\Response
   */
  public function find(Type $type)
  {
    return response()->json(['type' => Type::find($type->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\TypeStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(TypeStoreRequest $request)
  {
    $type = Type::create([
      'description' => $request->input('description'),
      'publish' => $request->input('publish')
    ]);
    return response()->json(['typeId' => $type->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\TypeStoreRequest  $request
   * @param  Type $type
   * @return \Illuminate\Http\Response
   */
  public function update(TypeStoreRequest $request, Type $type)
  {
    $type = Type::findOrFail($type->id);
    $type->description = $request->input('description');
    $type->publish = $request->input('publish');
    $type->save();
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given type
   *
   * @param  Type $type
   * @return \Illuminate\Http\Response
   */
  public function toggle(Type $type)
  {
    $type->publish = !$type->publish;
    $type->save();
    return response()->json($type->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Type $type
   * @return \Illuminate\Http\Response
   */
  public function destroy(Type $type)
  {
    $type->delete();
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
    $types = $request->get('types');
    foreach($types as $type)
    {
      $c = Type::find($type['id']);
      $c->order = $type['order'];
      $c->save(); 
    }
    return response()->json('successfully updated');
  }
}