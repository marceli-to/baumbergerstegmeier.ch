<?php
namespace App\Services;
use App\Models\Publication;

class Publications
{ 
  public function get($columns = 3)
  {
    // initialize the array of data
    $data = [];

    for($i = 1; $i <= $columns; $i++)
    {
      $data['col_' . $i] = [];
    }

    // get all publications, ordered by year, ascending
    $publications = Publication::publish()
      ->with('publishedImage', 'publishedFile')
      ->orderBy('year', 'ASC')
      ->orderBy('order', 'ASC')
      ->get();

    // build an array of years (year as key, number of items as value)
    $years = $publications->groupBy('year')->map(function ($item, $key){
      return $item->count();
    });

    // define a threshold for the number of items per column
    $threshold = ceil($publications->count() / $columns);

    // loop over the years array and put the years into one of the colums. 
    // start with col_3. if the threshold is reached, switch to col_2, then col_1
    $col_index = $columns;
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

    // loop over data and add the publications to the years
    foreach ($data as $col => $years)
    {
      foreach ($years as $year)
      {
        $data[$col][$year] = $publications->where('year', $year);
      }
      krsort($data[$col]);
    }

    // remove keys col_1, col_2, col_3
    $data = array_values($data);
    return $data;
  }
}