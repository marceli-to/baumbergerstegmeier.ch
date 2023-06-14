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

    $record = Type::create([
      'description' => 'Sanierung',
    ]);

    $record = Type::create([
      'description' => 'Umbau',
    ]);

  }
}
