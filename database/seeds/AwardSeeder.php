<?php
namespace Database\Seeders;
use App\Models\Award;
use App\Models\Image;
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
      'text' => '<h3>best architects 22, Auszeichnung</h3><p>Wohnhaus in Zürich</p>',
      'publish' => 1,
    ]);

    $rand = rand(1,8);
    $image = Image::create([
      'uuid' => \Str::uuid(),
      'name' => 'bas-' . $rand . '.jpg',
      'original_name' => 'bas-' . $rand . '.jpg',
      'orientation' => 'landscape',
      'extension' => 'jpg',
      'size' => 111562,
      'ratio' => '580x386',
      'imageable_id' => $award->id,
      'imageable_type' => Award::class,
      'caption' => '',
      'order' => 1,
      'publish' => 1
    ]);

    $award = Award::create([
      'year' => '2021',
      'text' => '<h3>best architects 21, Auszeichnung</h3><p>Wohnsiedlung am Katzenbach IV/V</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2021',
      'text' => '<h3>best architects 21, Auszeichnung</h3><p>Wohnhaus Ottikerstrasse</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2020',
      'text' => '<h3>best architects 22, Auszeichnung</h3><p>Wohnhaus in Zürich</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2020',
      'text' => '<h3>best architects 21, Auszeichnung</h3><p>Wohnsiedlung am Katzenbach I</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2019',
      'text' => '<h3>best architects 21, Auszeichnung</h3><p>Wohnhaus Ottikerstrasse</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2018',
      'text' => '<h3>best architects 19, Auszeichnung</h3><p>Wohnhaus in Zürich</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2018',
      'text' => '<h3>best architects 19, Auszeichnung</h3><p>Wohnsiedlung am Katzenbach IV</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2017',
      'text' => '<h3>best architects 21, Auszeichnung</h3><p>Wohnhaus Ottikerstrasse</p>',
      'publish' => 1,
    ]);
    $rand = rand(1,8);
    $image = Image::create([
      'uuid' => \Str::uuid(),
      'name' => 'bas-' . $rand . '.jpg',
      'original_name' => 'bas-' . $rand . '.jpg',
      'orientation' => 'landscape',
      'extension' => 'jpg',
      'size' => 111562,
      'ratio' => '580x386',
      'imageable_id' => $award->id,
      'imageable_type' => Award::class,
      'caption' => '',
      'order' => 1,
      'publish' => 1
    ]);

    $award = Award::create([
      'year' => '2019',
      'text' => '<h3>best architects 18, Auszeichnung</h3><p>Wohnhaus Pfungen</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2016',
      'text' => '<h3>best architects 15, Auszeichnung</h3><p>Primarschulhaus Neftenbach</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2015',
      'text' => '<h3>Arc-Award 13 Sieger Sonderpreis Ingenieurleistung</h3><p>Wohnsiedlung am Katzenbach IV/V</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2015',
      'text' => '<h3>Die Besten 2012/Hochparterre, Nomination</h3><p>Mehrfamilienhaus Segantinistrasse</p>',
      'publish' => 1,
    ]);
    $rand = rand(1,8);
    $image = Image::create([
      'uuid' => \Str::uuid(),
      'name' => 'bas-' . $rand . '.jpg',
      'original_name' => 'bas-' . $rand . '.jpg',
      'orientation' => 'landscape',
      'extension' => 'jpg',
      'size' => 111562,
      'ratio' => '580x386',
      'imageable_id' => $award->id,
      'imageable_type' => Award::class,
      'caption' => '',
      'order' => 1,
      'publish' => 1
    ]);
  }
}
