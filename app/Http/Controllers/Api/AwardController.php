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
      'title' => $request->input('title'),
      'subtitle' => $request->input('subtitle'),
      'link' => $request->input('link')
    ]);
    $this->handleFlag($award, 'isPublished', $request->input('publish'));
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
    $award->title = $request->input('title');
    $award->subtitle = $request->input('subtitle');
    $award->link = $request->input('link');
    $award->save();
    $this->handleFlag($award, 'isPublished', $request->input('publish'));
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
    if ($award->hasFlag('isPublished'))
    {
      $award->unflag('isPublished');
    }
    else
    {
      $award->flag('isPublished');
    } 
    return response()->json($award->hasFlag('isPublished'));
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
   * Handle flags of a job
   *
   * @param Award $award
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Award $award, $flag, $value)
  {
    if ($value == 1)
    {
      $award->flag($flag);
    }
    else
    {
      $award->unflag($flag);
    }
    return $award->hasFlag($flag);
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