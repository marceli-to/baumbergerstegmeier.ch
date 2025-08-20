<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request)
  {
    $data = parent::toArray($request);
    
    return $this->sanitizeUtf8($data);
  }

  private function sanitizeUtf8($data)
  {
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $data[$key] = $this->sanitizeUtf8($value);
      }
    } elseif (is_string($data)) {
      $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
    }
    
    return $data;
  }
}
