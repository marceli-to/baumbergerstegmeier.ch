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
    $award = Award::create([
      'year' => '2021',
      'title' => 'best architects 22, Auszeichnung',
      'subtitle' => 'Wohnhaus in Zürich',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2021',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnsiedlung am Katzenbach IV/V',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2021',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnhaus Ottikerstrasse',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2020',
      'title' => 'best architects 22, Auszeichnung',
      'subtitle' => 'Wohnhaus in Zürich',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2020',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnsiedlung am Katzenbach I',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2019',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnhaus Ottikerstrasse',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2018',
      'title' => 'best architects 19, Auszeichnung',
      'subtitle' => 'Wohnhaus in Zürich',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2018',
      'title' => 'best architects 19, Auszeichnung',
      'subtitle' => 'Wohnsiedlung am Katzenbach IV',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2017',
      'title' => 'best architects 21, Auszeichnung',
      'subtitle' => 'Wohnhaus Ottikerstrasse',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2019',
      'title' => 'best architects 18, Auszeichnung',
      'subtitle' => 'Wohnhaus Pfungen',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2016',
      'title' => 'best architects 15, Auszeichnung',
      'subtitle' => 'Primarschulhaus Neftenbach',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2015',
      'title' => 'Arc-Award 13 Sieger Sonderpreis Ingenieurleistung',
      'subtitle' => 'Wohnsiedlung am Katzenbach IV/V',
    ]);
    $award->flag('isPublished', 1);

    $award = Award::create([
      'year' => '2015',
      'title' => 'Die Besten 2012/Hochparterre, Nomination',
      'subtitle' => 'Mehrfamilienhaus Segantinistrasse',
    ]);
    $award->flag('isPublished', 1);

  }
}
