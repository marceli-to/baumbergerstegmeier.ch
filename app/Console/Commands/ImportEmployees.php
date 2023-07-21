<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Employee;

class ImportEmployees extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:employees';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import employee data from a comma separated text file';

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
    $file = fopen(storage_path('app/public/imports/employees.txt'), "r");

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

      // $data[5] = team_id
      $team_id = $data[5] == 'BSA' ? 1 : 2;

      // $data[6] = employee_category_id
      // remove \r\n from the end of the line
      $data[6] = str_replace(["\r\n", "\n"], [""], $data[6]);
      dd($data[6]);
      $employee_category_id = $data[6] == 'Partner' ? 1 : 2;

      $employee = Employee::create([
        'firstname' => $data[0],
        'name' => $data[1],
        'title' => $data[2],
        'email' => $data[3],
        'team_id' => $team_id,
        'employee_category_id' => $employee_category_id,
      ]);
    }

    // Close the file
    fclose($file);
  }
}
