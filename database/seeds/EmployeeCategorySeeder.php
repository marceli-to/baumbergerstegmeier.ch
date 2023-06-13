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
      'name' => 'Partner:in/Geschäftsleitung',
    ]);
    $record->flag('isPublished', 1);

    $record = EmployeeCategory::create([
      'name' => 'Mitarbeitende',
    ]);
    $record->flag('isPublished', 1);

    $record = EmployeeCategory::create([
      'name' => 'Ehemalige',
    ]);
    $record->flag('isPublished', 1);


  }
}
