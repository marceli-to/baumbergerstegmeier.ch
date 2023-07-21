<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Employee;

class ImportFormeremployees extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:formeremployees';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import former employee data from a comma separated text file';

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
    $file = fopen(storage_path('app/public/imports/formeremployees.txt'), "r");

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

      // $data[4] = team_id
      // remove \r\n
      $data[4] = str_replace("\r\n", "", $data[4]);
      $team_id = $data[4] == 'BSA' ? 1 : 2;

      $employee = Employee::create([
        'firstname' => $data[0],
        'name' => $data[1],
        'title' => $data[2],
        'email' => $data[3],
        'team_id' => $team_id,
        'employee_category_id' => 3,
      ]);
    }

    // Close the file
    fclose($file);
  }
}
