<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Teaser;
use App\Http\Resources\DataCollection;
use Illuminate\Http\Request;

class TeaserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get($type = NULL, $projectId = NULL)
  {
    $query = Teaser::with('image', 'project', 'article.publishedImage')->where('type', $type);

    if ($projectId)
    {
      $query->where('project_id', $projectId);
    }
    
    $items = $query->orderBy('position')->get();
    
    return response()->json([
      'items' => $items->groupBy('column')->values()
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
    $teaser = Teaser::create([
      'type' => $request->get('type'),
      'column' => $request->get('column'),
      'position' => 999,
      'project_id' => $request->get('project_id'),
      'article_id' => $request->get('article_id'),
      'image_id' => $request->get('image_id'),
    ]);
    return response()->json(['teaser' => $teaser->with('image', 'project', 'article')->find($teaser->id)]);
  }

  /**
   * Toggle the status a given teaser
   *
   * @param  Teaser $teaser
   * @return \Illuminate\Http\Response
   */
  public function toggle(Teaser $teaser)
  {
    $teaser->publish = !$teaser->publish;
    $teaser->save();
    return response()->json($teaser->publish);
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
       $t = Teaser::find($item['id']);
       $t->position = $item['position'];
       $t->save(); 
     }
     return response()->json('successfully updated');
   }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Teaser $teaser
   * @return \Illuminate\Http\Response
   */
  public function destroy(Teaser $teaser)
  {
    $teaser->delete();
    return response()->json('successfully deleted');
  }

}