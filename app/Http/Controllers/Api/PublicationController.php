<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Image;
use App\Http\Resources\DataCollection;
use App\Http\Requests\PublicationStoreRequest;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(Publication::with('images')->orderBy('year', 'DESC')->orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  Publication $publication
   * @return \Illuminate\Http\Response
   */
  public function find(Publication $publication)
  {
    return response()->json(['publication' => Publication::with('images')->find($publication->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\PublicationStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PublicationStoreRequest $request)
  {
    $publication = Publication::create([
      'year' => $request->input('year'),
      'title' => $request->input('title'),
      'subtitle' => $request->input('subtitle'),
      'description' => $request->input('description'),
      'link' => $request->input('link'),
      'publish' => $request->input('publish')
    ]);
    $this->handleImages($publication, $request->input('images'));
    return response()->json(['publicationId' => $publication->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\PublicationStoreRequest  $request
   * @param  Publication $publication
   * @return \Illuminate\Http\Response
   */
  public function update(PublicationStoreRequest $request, Publication $publication)
  {
    $publication = Publication::findOrFail($publication->id);
    $publication->year = $request->input('year');
    $publication->title = $request->input('title');
    $publication->subtitle = $request->input('subtitle');
    $publication->link = $request->input('link');
    $publication->description = $request->input('description');
    $publication->publish = $request->input('publish');
    $publication->save();
    $this->handleImages($publication, $request->input('images'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given job
   *
   * @param  Publication $publication
   * @return \Illuminate\Http\Response
   */
  public function toggle(Publication $publication)
  {
    $publication->publish = !$publication->publish;
    $publication->save();
    return response()->json($publication->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Publication $publication
   * @return \Illuminate\Http\Response
   */
  public function destroy(Publication $publication)
  {
    $publication->delete();
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
     $publications = $request->get('publications');
     foreach($publications as $publication)
     {
       $p = Publication::find($publication['id']);
       $p->order = $publication['order'];
       $p->save(); 
     }
     return response()->json('successfully updated');
   }

/**
 * Handle associated images
 *
 * @param Publication $publication
 * @param Array $images
 * @return void
 */  

  protected function handleImages(Publication $publication, $images = NULL)
  {
    foreach($images as $image)
    {
      $i = Image::findOrFail($image['id']);
      $i->imageable_id = $publication->id;
      $i->imageable_type = Publication::class;
      $i->save();
    }
  }
}