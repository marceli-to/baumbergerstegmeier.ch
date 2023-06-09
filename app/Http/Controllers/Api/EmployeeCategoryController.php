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
      'slug' => Str::slug($request->input('name')),
      'name' => $request->input('name'),
      'publish' => $request->input('publish')
    ]);
    return response()->json(['employeeCategory' => $employeeCategory]);
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
    $employeeCategory->publish = $request->input('publish');
    $employeeCategory->save();
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
    $employeeCategory->publish = !$employeeCategory->publish;
    $employeeCategory->save();
    return response()->json($employeeCategory->publish);
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

}