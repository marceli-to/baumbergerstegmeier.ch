<?php
namespace Database\Seeders;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Team::create([
      'name' => 'Baumberger Stegmeier Architektur',
    ]);
    $record->flag('isPublish', 1);

    $record = Team::create([
      'name' => 'BS+EMI Architektenpartner',
    ]);
    $record->flag('isPublish', 1);

  }
}