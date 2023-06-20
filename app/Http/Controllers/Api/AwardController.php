<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\AwardStoreRequest;
use Illuminate\Http\Request;

class AwardController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Award::with('images')->orderBy('year', 'DESC')->orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Award $award
   * @return \Illuminate\Http\Response
   */
  public function find(Award $award)
  {
    return response()->json(['award' => Award::with('images')->find($award->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\AwardStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(AwardStoreRequest $request)
  {
    $award = Award::create([
      'year' => $request->input('year'),
      'text' => $request->input('text'),
      // 'title' => $request->input('title'),
      // 'subtitle' => $request->input('subtitle'),
      // 'link' => $request->input('link'),
      'publish' => $request->input('publish')
    ]);
    $this->handleImages($award, $request->input('images'));
    return response()->json(['awardId' => $award->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\AwardStoreRequest  $request
   * @param  Award $award
   * @return \Illuminate\Http\Response
   */
  public function update(AwardStoreRequest $request, Award $award)
  {
    $award = Award::findOrFail($award->id);
    $award->year = $request->input('year');
    $award->text = $request->input('text');
    // $award->title = $request->input('title');
    // $award->subtitle = $request->input('subtitle');
    // $award->link = $request->input('link');
    $award->publish = $request->input('publish');
    $award->save();
    $this->handleImages($award, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given job
   *
   * @param  Award $award
   * @return \Illuminate\Http\Response
   */
  public function toggle(Award $award)
  {
    $award->publish = !$award->publish;
    $award->save();
    return response()->json($award->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Award $award
   * @return \Illuminate\Http\Response
   */
  public function destroy(Award $award)
  {
    $award->delete();
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
     $awards = $request->get('awards');
     foreach($awards as $award)
     {
       $p = Award::find($award['id']);
       $p->order = $award['order'];
       $p->save(); 
     }
     return response()->json('successfully updated');
   }

  /**
   * Handle associated images
   *
   * @param Award $award
   * @param Array $images
   * @return void
   */  

   protected function handleImages(Award $award, $images = NULL)
   {
     foreach($images as $image)
     {
       $i = Image::findOrFail($image['id']);
       $i->imageable_id = $award->id;
       $i->imageable_type = Award::class;
       $i->save();
     }
   }

}