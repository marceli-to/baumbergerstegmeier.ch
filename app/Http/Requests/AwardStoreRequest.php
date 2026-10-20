<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AwardStoreRequest extends FormRequest
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
      'text' => 'required',
      //'title' => 'required',
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
      'year.required' => 'Text wird benötigt',
      'year.integer' => 'Jahr muss eine Zahl sein',
      'text.required' => 'Text wird benötigt',
      // 'title.required' => [
      //   'field' => 'title',
      //   'error' => 'Titel wird benötigt'
      // ],
    ];
  }
}