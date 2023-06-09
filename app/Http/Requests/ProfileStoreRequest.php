<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ProfileStoreRequest extends FormRequest
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
      'title_bsa' => 'required',
      'title_bsemi' => 'required',
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
      'title_bsa.required' => [
        'field' => 'title_bsa',
        'error' => 'Titel für BSA wird benötigt'
      ],
      'title_bsemi.required' => [
        'field' => 'title_bsemi',
        'error' => 'Titel für BS/EMI wird benötigt'
      ],
    ];
  }
}