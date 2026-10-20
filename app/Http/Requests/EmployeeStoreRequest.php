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
      'firstname.required' => 'Vorname wird benötigt',
      'name.required' => 'Name wird benötigt',
      'team_id.required' => 'Team wird benötigt',
      'team_id.exists' => 'Team existiert nicht',
      'employee_category_id.required' => 'Mitarbeiterkategorie wird benötigt',
      'employee_category_id.exists' => 'Mitarbeiterkategorie existiert nicht',
    ];
  }
}