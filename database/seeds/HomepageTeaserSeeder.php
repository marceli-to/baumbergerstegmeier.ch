<?php
namespace Database\Seeders;
use App\Models\Teaser;
use App\Models\Project;
use Illuminate\Database\Seeder;

class HomepageTeaserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $type = 'home';
    $projects = Project::with('images')->get();

    // column 1
    for($i = 0; $i <= 8; $i++)
    {
      // get a random project with has images
      $project = $projects->random();
      while($project->images && $project->images->count() == 0)
      {
        $project = $projects->random();
      }

      // get a random image from the project
      $image = $project->images->random();

      // create a teaser
      $teaser = new Teaser();
      $teaser->type = $type;
      $teaser->project_id = $project->id;
      $teaser->image_id = $image->id;
      $teaser->column = 0;
      $teaser->position = $i;
      $teaser->save();
    }

    // column 2
    for($i = 0; $i <= 8; $i++)
    {
      // get a random project with has images
      $project = $projects->random();
      while($project->images && $project->images->count() == 0)
      {
        $project = $projects->random();
      }

      // get a random image from the project
      $image = $project->images->random();

      // create a teaser
      $teaser = new Teaser();
      $teaser->type = $type;
      $teaser->project_id = $project->id;
      $teaser->image_id = $image->id;
      $teaser->column = 1;
      $teaser->position = $i;
      $teaser->save();
    }

    // column 3
    for($i = 0; $i <= 8; $i++)
    {
      // get a random project with has images
      $project = $projects->random();
      while($project->images && $project->images->count() == 0)
      {
        $project = $projects->random();
      }

      // get a random image from the project
      $image = $project->images->random();

      // create a teaser
      $teaser = new Teaser();
      $teaser->type = $type;
      $teaser->project_id = $project->id;
      $teaser->image_id = $image->id;
      $teaser->column = 2;
      $teaser->position = $i;
      $teaser->save();
    }
  }
}
