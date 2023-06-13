<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\Team;
use App\Http\Resources\DataCollection;
use App\Http\Requests\EmployeeStoreRequest;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function get()
  {
    // BSA, Leadership
    $data = [
      'bsa_leadership'    => Employee::with('team', 'employeeCategory')->where('team_id', 1)->where('employee_category_id', 1)->orderBy('order')->get(),
      'bsa_employees'     => Employee::with('team', 'employeeCategory')->where('team_id', 1)->where('employee_category_id', 2)->orderBy('name', 'ASC')->get(),
      'bsemi_leadership'  => Employee::with('team', 'employeeCategory')->where('team_id', 2)->where('employee_category_id', 1)->orderBy('order')->get(),
      'bsemi_employees'   => Employee::with('team', 'employeeCategory')->where('team_id', 2)->where('employee_category_id', 2)->orderBy('name', 'ASC')->get(),
      'former_employees'  => Employee::with('team', 'employeeCategory')->where('employee_category_id', 3)->orderBy('name', 'ASC')->get()
    ];
    return response()->json($data);
  }

  /**
   * Display the specified resource.
   *
   * @param  Employee $employee
   * @return \Illuminate\Http\Response
   */
  public function find(Employee $employee)
  {
    return response()->json(
      [
        'employee' => Employee::with('team', 'employeeCategory')->find($employee->id),
        'employeeCategories' => EmployeeCategory::get(),
        'teams' => Team::get(),
      ]
  );
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\EmployeeStoreRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(EmployeeStoreRequest $request)
  {
    $employee = Employee::create([
      'firstname' => $request->input('firstname'),
      'name' => $request->input('name'),
      'title' => $request->input('title'),
      'email' => $request->input('email'),
      'team_id' => $request->input('team_id'),
      'employee_category_id' => $request->input('employee_category_id'),
    ]);
    $this->handleFlag($employee, 'isPublished', $request->input('publish'));
    return response()->json(['employeeId' => $employee->id]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\EmployeeStoreRequest  $request
   * @param  Employee $employee
   * @return \Illuminate\Http\Response
   */
  public function update(EmployeeStoreRequest $request, Employee $employee)
  {
    $employee = Employee::findOrFail($employee->id);
    $employee->firstname = $request->input('firstname');
    $employee->name = $request->input('name');
    $employee->title = $request->input('title');
    $employee->email = $request->input('email');
    $employee->team_id = $request->input('team_id');
    $employee->employee_category_id = $request->input('employee_category_id');
    $employee->save();
    $this->handleFlag($employee, 'isPublished', $request->input('publish'));
    return response()->json('successfully updated');
  }

  /**
   * Toggle the status a given employee
   *
   * @param  Employee $employee
   * @return \Illuminate\Http\Response
   */
  public function toggle(Employee $employee)
  {
    if ($employee->hasFlag('isPublished'))
    {
      $employee->unflag('isPublished');
    }
    else
    {
      $employee->flag('isPublished');
    } 
    return response()->json($employee->hasFlag('isPublished'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Employee $employee
   * @return \Illuminate\Http\Response
   */
  public function destroy(Employee $employee)
  {
    $employee->delete();
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
     $employees = $request->get('employees');
     foreach($employees as $employee)
     {
       $p = Employee::find($employee['id']);
       $p->order = $employee['order'];
       $p->save(); 
     }
     return response()->json('successfully updated');
   }

  /**
   * Handle flags of a employee
   *
   * @param Employee $employee
   * @param String $flag
   * @param Integer $value
   * @return Boolean
   */  
  protected function handleFlag(Employee $employee, $flag, $value)
  {
    if ($value == 1)
    {
      $employee->flag($flag);
    }
    else
    {
      $employee->unflag($flag);
    }
    return $employee->hasFlag($flag);
  }
}