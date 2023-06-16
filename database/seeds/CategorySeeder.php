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
      'slug' => 'wohnen',
    ]);

    $record = Category::create([
      'description' => 'Wohnen im Alter',
      'slug' => 'wohnen-im-alter',
    ]);

    $record = Category::create([
      'description' => 'Schulen',
      'slug' => 'schulen',
    ]);

    $record = Category::create([
      'description' => 'Gewerbe',
      'slug' => 'gewerbe',
    ]);

    $record = Category::create([
      'description' => 'Gesundheit',
      'slug' => 'gesundheit',
    ]);

    $record = Category::create([
      'description' => 'Kultur & Freizeit',
      'slug' => 'kultur-freizeit',
    ]);

    $record = Category::create([
      'description' => 'Strategien & Entwicklung',
      'slug' => 'strategien-entwicklung',
    ]);
  }
}
