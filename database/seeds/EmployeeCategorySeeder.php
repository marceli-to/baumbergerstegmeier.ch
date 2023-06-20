<?php
namespace Database\Seeders;
use App\Models\EmployeeCategory;
use Illuminate\Database\Seeder;

class EmployeeCategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = EmployeeCategory::create([
      'slug' => 'partner',
      'name' => 'Partner:in/Geschäftsleitung',
    ]);

    $record = EmployeeCategory::create([
      'slug' => 'mitarbeitende',
      'name' => 'Mitarbeitende',
    ]);

    $record = EmployeeCategory::create([
      'slug' => 'ehemalige',
      'name' => 'Ehemalige',
    ]);

  }
}
