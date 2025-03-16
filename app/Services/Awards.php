<?php
namespace App\Services;
use App\Models\Award;

class Awards
{ 
  public function get($columns = 3)
  {
    // initialize the array of data
    $data = [];

    for($i = 0; $i < $columns; $i++)
    {
      $data['col_' . $i] = [];
    }

    // get all awards, ordered by year, ascending
    $awards = Award::publish()
      ->with('publishedImage')
      ->orderBy('year', 'ASC')
      ->orderBy('order', 'ASC')
      ->get();

    // build an array of years (year as key, number of items as value)
    $years = $awards->groupBy('year')->map(function ($item, $key){
      return $item->count();
    });

    // define a threshold for the number of items per column
    $threshold = ceil($awards->count() / $columns);

    // loop over the years array and put the years into one of the colums. 
    // start with the last column and fill backwards
    $col_index = $columns - 1;
    $count = 0;

    foreach ($years as $year => $items)
    {
      $count = $count + $items;
      if ($count >= $threshold)
      {
        $col_index--;
        $count = 0;
      }
      $data['col_' . $col_index][$year] = $year;
    }

    // loop over data and add the awards to the years
    foreach ($data as $col => $years)
    {
      foreach ($years as $year)
      {
        $data[$col][$year] = $awards->where('year', $year);
      }

      krsort($data[$col]);
    }

    // sort the columns in the correct order (col_0, col_1, col_2)
    ksort($data);
    
    // remove keys col_0, col_1, col_2
    $data = array_values($data);
    
    return $data;
  }
}
