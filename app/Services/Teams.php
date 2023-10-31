<?php
namespace App\Services;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\Team;

class Teams
{ 
  public function get()
  {
    // Team
    $team = Team::with('publishedImages')->where('slug', 'baumberger-stegmeier-architekten')->first();

    // Leadership
    $leadership_category = EmployeeCategory::where('slug', 'partner')->first();
    $leadership = Employee::with('team', 'employeeCategory', 'cv.category')->where('employee_category_id', $leadership_category->id)->orderBy('order')->get();
 
    // Employees
    $employees_category = EmployeeCategory::where('slug', 'mitarbeitende')->first();
    $employees = Employee::with('team', 'employeeCategory')->where('employee_category_id', $employees_category->id)->orderBy('name', 'ASC')->get();
    
    // Chunk employees into 2 columns
    $employees = $employees->chunk(ceil($employees->count() / 2));

    // Former employees
    $former_employees_category = EmployeeCategory::where('slug', 'ehemalige')->first();
    $former_employees = Employee::with('team', 'employeeCategory')->where('employee_category_id', $former_employees_category->id)->orderBy('name', 'ASC')->get();

    // Chunk former employees into 2 columns
    $former_employees = $former_employees->chunk(ceil($former_employees->count() / 2));

    $data = [
      'team' => $team,
      'leadership' => [
        'category' => $leadership_category,
        'employees' => $leadership
      ],
      'employees' => [
        'category' => $employees_category,
        'employees' => $employees
      ],
      'former_employees' => [
        'category' => $former_employees_category,
        'employees' => $former_employees
      ]
    ];
    return collect($data);

  }
}
