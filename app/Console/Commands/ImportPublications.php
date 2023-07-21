<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Publication;

class ImportPublications extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:publications';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import publication data from a comma separated text file';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
 {
    // Open the file in read mode
    $file = fopen(storage_path('app/public/imports/publications.txt'), "r");

    // Create an array to store the data
    $data = [];

    // Loop through the file line by line
    while (!feof($file)) {

      // Get the current line
      $line = fgets($file);

      // Split the line by semicolon
      $fields = explode(";", $line);

      // Add the fields to the array
      $data = $fields;

      $publication = Publication::create([
        'year' => intval($data[0]),
        'title' => $data[1],
        'subtitle' => $data[2],
        'description' => $data[3],
        //'link' => $d[4],
      ]);
    }

    // Close the file
    fclose($file);
  }
}
