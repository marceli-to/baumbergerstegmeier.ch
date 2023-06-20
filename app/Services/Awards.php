<?php
namespace App\Services;
use App\Models\Award;

class Awards
{ 
  public function get()
  {
    $awards = Award::publish()->with('publishedImage')->orderBy('year', 'DESC')->get();
    $awards = $awards->groupBy('year');
    return $awards->chunk(ceil($awards->count() / 3));
  }
}
