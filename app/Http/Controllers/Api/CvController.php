<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Cv;
use App\Models\CvCategory;
use App\Models\Employee;
use App\Http\Resources\DataCollection;
use App\Http\Requests\CvStoreRequest;
use Illuminate\Http\Request;

class CvController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Employee $employee
   * @return \Illuminate\Http\Response
   */
  public function get(Employee $employee)
  {
    // Get entries without categories
    $cv = Cv::where('employee_id', $employee->id)->where('cv_category_id', null)->orderBy('order')->get();

    // Get entries with categories and group them by category
    $cvCategorized = Cv::where('employee_id', $employee->id)->with('category')->where('cv_category_id', '!=', null)->orderBy('order')->get();

    // Group the entries by category
    $cvCategorized = $cvCategorized->sortBy('order')->groupBy('cv_category_id');

    // Return the itemsWithoutCategories
    return response()->json(
      [
        'cv' => $cv,
        'cvCategorized' => $cvCategorized,
      ]
    );
  }

  /**
   * Display the specified resource.
   *
   * @param  Cv $cv
   * @return \Illuminate\Http\Response
   */
  public function find(Cv $cv)
  {
    return response()->json(
      [
        'cv' => Cv::with('category')->find($cv->id),
        'categories' => CvCategory::get(),
      ]
    );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\CvStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CvStoreRequest $request)
  {
    $cv = Cv::create([
      'periode' => $request->input('periode'),
      'description' => $request->input('description'),
      'cv_category_id' => $request->input('cv_category_id'),
      'employee_id' => $request->input('employee_id'),
      'publish' => $request->input('publish')
    ]);
    return response()->json(['cvId' => $cv->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\CvStoreRequest  $request
   * @param  Cv $cv
   * @return \Illuminate\Http\Response
   */
  public function update(CvStoreRequest $request, Cv $cv)
  {
    $cv = Cv::findOrFail($cv->id);
    $cv->periode = $request->input('periode');
    $cv->description = $request->input('description');
    $cv->cv_category_id = $request->input('cv_category_id');
    $cv->publish = $request->input('publish');
    $cv->save();
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given cv
   *
   * @param  Cv $cv
   * @return \Illuminate\Http\Response
   */
  public function toggle(Cv $cv)
  {
    $cv->publish = !$cv->publish;
    $cv->save();
    return response()->json($cv->publish);
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  Cv $cv
   * @return \Illuminate\Http\Response
   */
  public function destroy(Cv $cv)
  {
    $cv->delete();
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
    $cvs = $request->get('cvs');
    foreach($cvs as $cv)
    {
      $p = Cv::find($cv['id']);
      $p->order = $cv['order'];
      $p->save(); 
    }
    return response()->json('successfully updated');
  }

}