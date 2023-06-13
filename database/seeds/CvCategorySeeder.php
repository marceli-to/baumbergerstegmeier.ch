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
    $record->flag('isPublished', 1);

    $record = CvCategory::create([
      'description' => 'Mitgliedschaften',
    ]);
    $record->flag('isPublished', 1);

  }
}
