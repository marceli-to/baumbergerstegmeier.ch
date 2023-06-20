<?php
namespace App\Services;
use App\Models\Publication;

class Publications
{ 
  public function get()
  {
    $publications = Publication::publish()->with('publishedImage', 'publishedFile')->orderBy('year', 'DESC')->get();
    $publications = $publications->groupBy('year');
    return $publications->chunk(ceil($publications->count() / 3));
  }
}
