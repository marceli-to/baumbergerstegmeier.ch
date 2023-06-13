<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Category::create([
      'description' => 'Wohnen',
    ]);
    $record->flag('isPublished', 1);

    $record = Category::create([
      'description' => 'Wohnen im Alter',
    ]);
    $record->flag('isPublished', 1);

    $record = Category::create([
      'description' => 'Schulen',
    ]);
    $record->flag('isPublished', 1);

    $record = Category::create([
      'description' => 'Gewerbe',
    ]);
    $record->flag('isPublished', 1);

    $record = Category::create([
      'description' => 'Gesundheit',
    ]);
    $record->flag('isPublished', 1);

    $record = Category::create([
      'description' => 'Kultur & Freizeit',
    ]);
    $record->flag('isPublished', 1);

    $record = Category::create([
      'description' => 'Strategien & Entwicklung',
    ]);
    $record->flag('isPublished', 1);

  }
}
