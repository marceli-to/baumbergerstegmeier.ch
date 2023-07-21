<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\CvCategory;
use App\Http\Resources\DataCollection;
use App\Http\Requests\CvCategoryStoreRequest;
use Illuminate\Http\Request;

class CvCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(CvCategory::orderBy('order')->get());
  }

  /**
   * Display the specified resource.
   *
   * @param  CvCategory $cvCategory
   * @return \Illuminate\Http\Response
   */
  public function find(CvCategory $cvCategory)
  {
    return response()->json(['cvCategory' => CvCategory::find($cvCategory->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\CvCategoryStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CvCategoryStoreRequest $request)
  {
    $cvCategory = CvCategory::create([
      'description' => $request->input('description'),
      'publish' => $request->input('publish')
    ]);
    return response()->json(['cvCategory' => $cvCategory]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\CvCategoryStoreRequest  $request
   * @param  CvCategory $cvCategory
   * @return \Illuminate\Http\Response
   */
  public function update(CvCategoryStoreRequest $request, CvCategory $cvCategory)
  {
    $cvCategory = CvCategory::findOrFail($cvCategory->id);
    $cvCategory->description = $request->input('description');
    $cvCategory->publish = $request->input('publish');
    $cvCategory->save();
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given cvCategory
   *
   * @param  CvCategory $cvCategory
   * @return \Illuminate\Http\Response
   */
  public function toggle(CvCategory $cvCategory)
  {
    $cvCategory->publish = !$cvCategory->publish;
    $cvCategory->save();
    return response()->json($cvCategory->publish);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  CvCategory $cvCategory
   * @return \Illuminate\Http\Response
   */
  public function destroy(CvCategory $cvCategory)
  {
    $cvCategory->delete();
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
    $cvCategories = $request->get('cvCategories');
    foreach($cvCategories as $cvCategory)
    {
      $c = CvCategory::find($cvCategory['id']);
      $c->order = $cvCategory['order'];
      $c->save(); 
    }
    return response()->json('successfully updated');
  }
}