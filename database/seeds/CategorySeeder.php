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

    $record = Category::create([
      'description' => 'Wohnen im Alter',
    ]);

    $record = Category::create([
      'description' => 'Schulen',
    ]);

    $record = Category::create([
      'description' => 'Gewerbe',
    ]);

    $record = Category::create([
      'description' => 'Gesundheit',
    ]);

    $record = Category::create([
      'description' => 'Kultur & Freizeit',
    ]);

    $record = Category::create([
      'description' => 'Strategien & Entwicklung',
    ]);


  }
}
