<?php
namespace App\Services;
use App\Models\Award;

class Awards
{ 
  public function get()
  {
    // $awards = Award::publish()->with('publishedImage')->orderBy('year', 'DESC')->get();
    // $awards = $awards->groupBy('year');
    // return $awards->chunk(ceil($awards->count() / 3));
    // initialize the array of data
    $data = [
      'col_1' => [],
      'col_2' => [],
      'col_3' => [],
    ];

    // get all awards, ordered by year, ascending
    $awards = Award::publish()->with('publishedImage')->orderBy('year', 'ASC')->get();

    // build an array of years (year as key, number of items as value)
    $years = $awards->groupBy('year')->map(function ($item, $key){
      return $item->count();
    });

    // define a threshold for the number of items per column
    $threshold = ceil($awards->count() / 3);

    // loop over the years array and put the years into one of the colums. 
    // start with col_3. if the threshold is reached, switch to col_2, then col_1
    $col_index = '3';
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

    // remove keys col_1, col_2, col_3
    $data = array_values($data);
    return $data;
  }
}
