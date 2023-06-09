<?php
namespace Database\Seeders;
use App\Models\Publication;
use Illuminate\Database\Seeder;

class PublicationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Publication::create([
      'year' => '2021',
      'title' => 'Wohlfühlhaus (Haus mit zwei Eingängen)',
      'description' => 'Cornelia Faist Das Ideale Heim 2/2023',
    ]);

    Publication::create([
      'year' => '2021',
      'title' => 'Das farbigste Schulhaus der Schweiz',
      'subtitle' => 'Sekundarschulhaus Chliriet',
      'description' => 'Andres Herzog Tagesanzeiger,17.12.2022',
    ]);

  }
}
