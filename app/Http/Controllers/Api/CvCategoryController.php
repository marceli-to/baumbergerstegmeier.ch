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
    return new DataCollection(CvCategory::get());
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
    ]);
    $this->handleFlag($cvCategory, 'isPublish', $request->input('publish'));
    return response()->json(['cvCategoryId' => $cvCategory->id]);
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
    $cvCategory->save();
    $this->handleFlag($cvCategory, 'isPublish', $request->input('publish'));
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
    if ($cvCategory->hasFlag('isPublish'))
    {
      $cvCategory->unflag('isPublish');
    }
    else
    {
      $cvCategory->flag('isPublish');
    } 
    return response()->json($cvCategory->hasFlag('isPublish'));
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
   * Handle flags of a cvCategory
   *
   * @param CvCategory $cvCategory
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(CvCategory $cvCategory, $flag, $value)
  {
    if ($value == 1)
    {
      $cvCategory->flag($flag);
    }
    else
    {
      $cvCategory->unflag($flag);
    }
    return $cvCategory->hasFlag($flag);
  }

}