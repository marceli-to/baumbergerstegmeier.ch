<?php
namespace Database\Seeders;
use App\Models\Job;
use App\Models\Image;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Job::create([
      'text' => 'Zur Zeit sind alle unsere Stellen besetzt.',
    ]);

    for($i = 1; $i <= 2; $i++)
    {
      $rand = rand(1,8);
      $image = Image::create([
        'uuid' => \Str::uuid(),
        'name' => 'bas-' . $rand . '.jpg',
        'original_name' => 'bas-' . $rand . '.jpg',
        'orientation' => 'landscape',
        'extension' => 'jpg',
        'size' => 111562,
        'ratio' => '580x386',
        'imageable_id' => $record->id,
        'imageable_type' => Job::class,
        'caption' => '',
        'order' => $i,
        'publish' => 1
      ]);
    }
  }
}
