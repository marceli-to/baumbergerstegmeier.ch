<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\ProfileStoreRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Profile::with('images')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Profile $profile
   * @return \Illuminate\Http\Response
   */
  public function find(Profile $profile)
  {
    return response()->json(['profile' => Profile::with('images')->find($profile->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\ProfileStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProfileStoreRequest $request)
  {
    $profile = Profile::create([
      'title_bsa' => $request->input('title_bsa'),
      'text_bsa' => $request->input('text_bsa'),
      'title_bsemi' => $request->input('title_bsemi'),
      'text_bsemi' => $request->input('text_bsemi'),
      'publish' => $request->input('publish')
    ]);
    $this->handleImages($profile, $request->input('images'));
    return response()->json(['profileId' => $profile->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\ProfileStoreRequest  $request
   * @param  Profile $profile
   * @return \Illuminate\Http\Response
   */
  public function update(ProfileStoreRequest $request, Profile $profile)
  {
    $profile = Profile::findOrFail($profile->id);
    $profile->title_bsa = $request->input('title_bsa');
    $profile->text_bsa = $request->input('text_bsa');
    $profile->title_bsemi = $request->input('title_bsemi');
    $profile->text_bsemi = $request->input('text_bsemi');
    $profile->publish = $request->input('publish');
    $profile->save();
    $this->handleImages($profile, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given profile
   *
   * @param  Profile $profile
   * @return \Illuminate\Http\Response
   */
  public function toggle(Profile $profile)
  {
    $profile->publish = !$profile->publish;
    $profile->save();
    return response()->json($profile->publish);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Profile $profile
   * @return \Illuminate\Http\Response
   */
  public function destroy(Profile $profile)
  {
    $profile->delete();
    return response()->json('successfully deleted');
  }
  
  /**
   * Handle associated images
   *
   * @param Profile $profile
   * @param Array $images
   * @return void
   */  

  protected function handleImages(Profile $profile, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $profile->id;
      $i->imageable_type = Profile::class;
      $i->save();
    }
  }
}