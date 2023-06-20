<?php
namespace App\Services;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\Team;

class Teams
{ 
  public function get()
  {
    // BSA
    $bsa_team = Team::with('publishedImages')->where('slug', 'baumberger-stegmeier-architekten')->first();

    // BSA Leadership
    $bsa_leadership_category = EmployeeCategory::where('slug', 'partner')->first();
    $bsa_leadership = Employee::with('team', 'employeeCategory')->where('team_id', $bsa_team->id)->where('employee_category_id', $bsa_leadership_category->id)->orderBy('order')->get();

    // BSA Employees
    $bsa_employees_category = EmployeeCategory::where('slug', 'mitarbeitende')->first();
    $bsa_employees = Employee::with('team', 'employeeCategory')->where('team_id', $bsa_team->id)->where('employee_category_id', $bsa_employees_category->id)->orderBy('name', 'ASC')->get();
    
    // chunk employees into 2 columns
    $bsa_employees = $bsa_employees->chunk(ceil($bsa_employees->count() / 2));

    // BSEMI
    $bsemi_team = Team::with('publishedImages')->where('slug', 'bs-emi-architektenpartner')->first();

    // BSEMI Leadership
    $bsemi_leadership_category = EmployeeCategory::where('slug', 'partner')->first();
    $bsemi_leadership = Employee::with('team', 'employeeCategory')->where('team_id', $bsemi_team->id)->where('employee_category_id', $bsemi_leadership_category->id)->orderBy('order')->get();

    // BSEMI Employees
    $bsemi_employees_category = EmployeeCategory::where('slug', 'mitarbeitende')->first();
    $bsemi_employees = Employee::with('team', 'employeeCategory')->where('team_id', $bsemi_team->id)->where('employee_category_id', $bsemi_employees_category->id)->orderBy('name', 'ASC')->get();

    // chunk employees into 2 columns
    $bsemi_employees = $bsemi_employees->chunk(ceil($bsemi_employees->count() / 2));

    // Former employees
    $former_employees_category = EmployeeCategory::where('slug', 'ehemalige')->first();
    $former_employees = Employee::with('team', 'employeeCategory')->where('employee_category_id', $former_employees_category->id)->orderBy('name', 'ASC')->get();

    // chunk employees into 2 columns
    $former_employees = $former_employees->chunk(ceil($former_employees->count() / 2));

    $data = [
      'bsa_team' => $bsa_team,
      'bsa_leadership' => [
        'category' => $bsa_leadership_category,
        'employees' => $bsa_leadership
      ],
      'bsa_employees' => [
        'category' => $bsa_employees_category,
        'employees' => $bsa_employees
      ],
      'bsemi_team' => $bsemi_team,
      'bsemi_leadership' => [
        'category' => $bsemi_leadership_category,
        'employees' => $bsemi_leadership
      ],
      'bsemi_employees' => [
        'category' => $bsemi_employees_category,
        'employees' => $bsemi_employees
      ],
      'former_employees' => [
        'category' => $former_employees_category,
        'employees' => $former_employees
      ]
    ];

   // dd($data);


    return collect($data);

  }
}
