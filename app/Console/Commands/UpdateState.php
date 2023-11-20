<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Project;

class UpdateState extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'update:state';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Updates the project state';

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
    $projects = Project::with('states')->get();
    foreach($projects as $project)
    {
      $project->state_id = $project->states[0]->id;
      $project->save();
      $project->states()->detach();
    }
  }
}
