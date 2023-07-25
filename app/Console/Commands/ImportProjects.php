<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Project;

class ImportProjects extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'import:projects';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Import project data from a comma separated text file';

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
    $file = fopen(storage_path('app/public/imports/projects.txt'), "r");

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

      // remove \r\n from $data[6]
      $state_id = str_replace("\r\n", "", $data[6]);

      $project = Project::create([
        'slug' => 'null', // TODO: generate slug from 'title
        'title' => $data[0],
        'text' => $data[1],
        'info' => $data[2],
        'year' => $data[3],
        'location' => $data[4],
        'type' => $data[5],
      ]);

      $project->slug = \AppHelper::slug($project->title) . '-' . $project->id;

      $project->states()->sync($state_id);
    }

    // Close the file
    fclose($file);

  }
}
