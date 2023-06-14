<?php
namespace Database\Seeders;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = State::create([
      'description' => 'Gebaut',
    ]);

    $record = State::create([
      'description' => 'In Planung',
    ]);

    $record = State::create([
      'description' => 'Wettbewerbe',
    ]);

  }
}
