<?php
namespace Database\Seeders;
use App\Models\Award;
use Illuminate\Database\Seeder;

class AwardSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Award::create([
      'year' => '2021',
      'title' => 'best architects 22, Auszeichnung',
      'subtitle' => 'Wohnhaus in Zürich',
    ]);

    Award::create([
      'year' => '2021',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnsiedlung am Katzenbach IV/V',
    ]);

    Award::create([
      'year' => '2021',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnhaus Ottikerstrasse',
    ]);

    Award::create([
      'year' => '2020',
      'title' => 'best architects 22, Auszeichnung',
      'subtitle' => 'Wohnhaus in Zürich',
    ]);

    Award::create([
      'year' => '2020',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnsiedlung am Katzenbach I',
    ]);

    Award::create([
      'year' => '2019',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnhaus Ottikerstrasse',
    ]);

    Award::create([
      'year' => '2018',
      'title' => 'best architects 19, Auszeichnung',
      'subtitle' => 'Wohnhaus in Zürich',
    ]);

    Award::create([
      'year' => '2018',
      'title' => 'best architects 19, Auszeichnung',
      'subtitle' => 'Wohnsiedlung am Katzenbach IV',
    ]);

    Award::create([
      'year' => '2017',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnhaus Ottikerstrasse',
    ]);

    Award::create([
      'year' => '2019',
      'title' => 'best architects 18, Auszeichnung',
      'subtitle' => 'Wohnhaus Pfungen',
    ]);

    Award::create([
      'year' => '2016',
      'title' => 'best architects 15, Auszeichnung',
      'subtitle' => 'Primarschulhaus Neftenbach',
    ]);

    Award::create([
      'year' => '2015',
      'title' => 'Arc-Award 13 Sieger Sonderpreis Ingenieurleistung',
      'subtitle' => 'Wohnsiedlung am Katzenbach IV/V',
    ]);

    Award::create([
      'year' => '2015',
      'title' => 'Die Besten 2012/Hochparterre, Nomination',
      'subtitle' => 'Mehrfamilienhaus Segantinistrasse',
    ]);
  }
}
