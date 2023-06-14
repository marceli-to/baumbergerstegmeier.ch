<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
      'category' => 'required',
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
      'category.required' => [
        'field' => 'category',
        'error' => 'Text wird benötigt'
      ],
      'title.required' => [
        'field' => 'title',
        'error' => 'Text wird benötigt'
      ],
    ];
  }
}