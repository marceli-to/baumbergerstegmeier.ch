<?php
namespace Database\Seeders;
use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Type::create([
      'description' => 'Neubau',
    ]);
    $record->flag('isPublished', 1);

    $record = Type::create([
      'description' => 'Sanierung',
    ]);
    $record->flag('isPublished', 1);

    $record = Type::create([
      'description' => 'Umbau',
    ]);
    $record->flag('isPublished', 1);

  }
}
