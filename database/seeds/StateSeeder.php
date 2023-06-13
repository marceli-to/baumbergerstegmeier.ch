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
    $record->flag('isPublish', 1);

    $record = State::create([
      'description' => 'In Planung',
    ]);
    $record->flag('isPublish', 1);

    $record = State::create([
      'description' => 'Wettbewerbe',
    ]);
    $record->flag('isPublish', 1);
  }
}
