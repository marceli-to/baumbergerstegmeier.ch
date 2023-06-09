<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PublicationStoreRequest extends FormRequest
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
      'year' => 'required|integer|min:1900|max:2100',
      'title' => 'required',
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
      'year.required' => [
        'field' => 'year',
        'error' => 'Text wird benötigt'
      ],
      'year.integer' => [
        'field' => 'year',
        'error' => 'Jahr muss eine Zahl sein'
      ],
      'title.required' => [
        'field' => 'title',
        'error' => 'Text wird benötigt'
      ],
    ];
  }
}