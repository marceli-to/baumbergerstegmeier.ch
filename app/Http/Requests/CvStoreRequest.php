<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CvStoreRequest extends FormRequest
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
      'periode' => 'required',
      'description' => 'required',
      'employee_id' => 'required|exists:employees,id',
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
      'periode.required' => [
        'field' => 'periode',
        'error' => 'Beschreibung wird benötigt'
      ],
      'description.required' => [
        'field' => 'description',
        'error' => 'Beschreibung wird benötigt'
      ],
      'employee_id.required' => [
        'field' => 'employee_id',
        'error' => 'Mitarbeiter wird benötigt'
      ],
      'employee_id.exists' => [
        'field' => 'employee_id',
        'error' => 'Mitarbeiter existiert nicht'
      ],
    ];
  }
}