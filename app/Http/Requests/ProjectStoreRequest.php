<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
      'title' => 'required',
      'year' => 'required|integer|min:1900|max:2100',
      'type_id' => 'required|exists:types,id',
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
      'title.required' => [
        'field' => 'title',
        'error' => 'Name wird benötigt'
      ],
      'year.required' => [
        'field' => 'year',
        'error' => 'Jahr wird benötigt'
      ],
      'year.integer' => [
        'field' => 'year',
        'error' => 'Jahr muss eine Zahl sein'
      ],
      'type_id.required' => [
        'field' => 'type',
        'error' => 'Typ wird benötigt'
      ],
      'type_id.exists' => [
        'field' => 'type',
        'error' => 'Typ existiert nicht'
      ],
    ];
  }
}