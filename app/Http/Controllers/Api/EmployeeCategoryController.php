<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\EmployeeCategory;
use App\Http\Resources\DataCollection;
use App\Http\Requests\EmployeeCategoryStoreRequest;
use Illuminate\Http\Request;

class EmployeeCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    return new DataCollection(EmployeeCategory::get());
  }

  /**
   * Display the specified resource.
   *
   * @param  EmployeeCategory $employeeCategory
   * @return \Illuminate\Http\Response
   */
  public function find(EmployeeCategory $employeeCategory)
  {
    return response()->json(['employeeCategory' => EmployeeCategory::find($employeeCategory->id)]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\EmployeeCategoryStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EmployeeCategoryStoreRequest $request)
  {
    $employeeCategory = EmployeeCategory::create([
      'name' => $request->input('name'),
    ]);
    $this->handleFlag($employeeCategory, 'isPublish', $request->input('publish'));
    return response()->json(['employeeCategoryId' => $employeeCategory->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\EmployeeCategoryStoreRequest  $request
   * @param  EmployeeCategory $employeeCategory
   * @return \Illuminate\Http\Response
   */
  public function update(EmployeeCategoryStoreRequest $request, EmployeeCategory $employeeCategory)
  {
    $employeeCategory = EmployeeCategory::findOrFail($employeeCategory->id);
    $employeeCategory->name = $request->input('name');
    $employeeCategory->save();
    $this->handleFlag($employeeCategory, 'isPublish', $request->input('publish'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given employeeCategory
   *
   * @param  EmployeeCategory $employeeCategory
   * @return \Illuminate\Http\Response
   */
  public function toggle(EmployeeCategory $employeeCategory)
  {
    if ($employeeCategory->hasFlag('isPublish'))
    {
      $employeeCategory->unflag('isPublish');
    }
    else
    {
      $employeeCategory->flag('isPublish');
    } 
    return response()->json($employeeCategory->hasFlag('isPublish'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  EmployeeCategory $employeeCategory
   * @return \Illuminate\Http\Response
   */
  public function destroy(EmployeeCategory $employeeCategory)
  {
    $employeeCategory->delete();
    return response()->json('successfully deleted');
  }

  /**
   * Handle flags of a employeeCategory
   *
   * @param EmployeeCategory $employeeCategory
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(EmployeeCategory $employeeCategory, $flag, $value)
  {
    if ($value == 1)
    {
      $employeeCategory->flag($flag);
    }
    else
    {
      $employeeCategory->unflag($flag);
    }
    return $employeeCategory->hasFlag($flag);
  }
}