<?php
namespace Database\Seeders;
use App\Models\CvCategory;
use Illuminate\Database\Seeder;

class CvCategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = CvCategory::create([
      'description' => 'Firma',
    ]);
    $record->flag('isPublish', 1);

    $record = CvCategory::create([
      'description' => 'Mitgliedschaften',
    ]);
    $record->flag('isPublish', 1);

  }
}
