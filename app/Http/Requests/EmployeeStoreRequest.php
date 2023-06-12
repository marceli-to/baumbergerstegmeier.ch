<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */

  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */

  public function rules()
  {
    return [
      'firstname' => 'required',
      'name' => 'required',
      'team_id' => 'required|exists:teams,id',
      'employee_category_id' => 'required|exists:employee_categories,id',
    ];
  }

  /**
   * Custom message for validation
   *
   * @return array
   */
  
  public function messages()
  {
    return [
      'firstname.required' => [
        'field' => 'firstname',
        'error' => 'Vorname wird benötigt'
      ],
      'name.required' => [
        'field' => 'name',
        'error' => 'Name wird benötigt'
      ],
      'team_id.required' => [
        'field' => 'team_id',
        'error' => 'Team wird benötigt'
      ],
      'team_id.exists' => [
        'field' => 'team_id',
        'error' => 'Team existiert nicht'
      ],
      'employee_category_id.required' => [
        'field' => 'employee_category_id',
        'error' => 'Mitarbeiterkategorie wird benötigt'
      ],
      'employee_category_id.exists' => [
        'field' => 'employee_category_id',
        'error' => 'Mitarbeiterkategorie existiert nicht'
      ],
    ];
  }
}