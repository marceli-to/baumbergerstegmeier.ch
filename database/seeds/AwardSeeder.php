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
    // $award = Award::create([
    //   'year' => '2021',
    //   'title' => 'best architects 22, Auszeichnung',
    //   'subtitle' => 'Wohnhaus in Zürich',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2021',
    //   'title' => 'best architects 21, Auszeichnung',
    //   'subtitle' => 'Wohnsiedlung am Katzenbach IV/V',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2021',
    //   'title' => 'best architects 21, Auszeichnung',
    //   'subtitle' => 'Wohnhaus Ottikerstrasse',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2020',
    //   'title' => 'best architects 22, Auszeichnung',
    //   'subtitle' => 'Wohnhaus in Zürich',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2020',
    //   'title' => 'best architects 21, Auszeichnung',
    //   'subtitle' => 'Wohnsiedlung am Katzenbach I',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2019',
    //   'title' => 'best architects 21, Auszeichnung',
    //   'subtitle' => 'Wohnhaus Ottikerstrasse',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2018',
    //   'title' => 'best architects 19, Auszeichnung',
    //   'subtitle' => 'Wohnhaus in Zürich',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2018',
    //   'title' => 'best architects 19, Auszeichnung',
    //   'subtitle' => 'Wohnsiedlung am Katzenbach IV',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2017',
    //   'title' => 'best architects 21, Auszeichnung',
    //   'subtitle' => 'Wohnhaus Ottikerstrasse',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2019',
    //   'title' => 'best architects 18, Auszeichnung',
    //   'subtitle' => 'Wohnhaus Pfungen',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2016',
    //   'title' => 'best architects 15, Auszeichnung',
    //   'subtitle' => 'Primarschulhaus Neftenbach',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2015',
    //   'title' => 'Arc-Award 13 Sieger Sonderpreis Ingenieurleistung',
    //   'subtitle' => 'Wohnsiedlung am Katzenbach IV/V',
    //   'publish' => 1,
    // ]);

    // $award = Award::create([
    //   'year' => '2015',
    //   'title' => 'Die Besten 2012/Hochparterre, Nomination',
    //   'subtitle' => 'Mehrfamilienhaus Segantinistrasse',
    //   'publish' => 1,
    // ]);

    $award = Award::create([
      'year' => '2021',
      'text' => '<p>best architects 22, Auszeichnung<br>Wohnhaus in Zürich</p>',
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
      'text' => '<p>best architects 21, Auszeichnung<br>Wohnsiedlung am Katzenbach IV/V</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2021',
      'text' => '<p>best architects 21, Auszeichnung<br>Wohnhaus Ottikerstrasse</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2020',
      'text' => '<p>best architects 22, Auszeichnung<br>Wohnhaus in Zürich</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2020',
      'text' => '<p>best architects 21, Auszeichnung<br>Wohnsiedlung am Katzenbach I</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2019',
      'text' => '<p>best architects 21, Auszeichnung<br>Wohnhaus Ottikerstrasse</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2018',
      'text' => '<p>best architects 19, Auszeichnung<br>Wohnhaus in Zürich</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2018',
      'text' => '<p>best architects 19, Auszeichnung<br>Wohnsiedlung am Katzenbach IV</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2017',
      'text' => '<p>best architects 21, Auszeichnung<br>Wohnhaus Ottikerstrasse</p>',
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
      'text' => '<p>best architects 18, Auszeichnung<br>Wohnhaus Pfungen</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2016',
      'text' => '<p>best architects 15, Auszeichnung<br>Primarschulhaus Neftenbach</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2015',
      'text' => '<p>Arc-Award 13 Sieger Sonderpreis Ingenieurleistung<br>Wohnsiedlung am Katzenbach IV/V</p>',
      'publish' => 1,
    ]);

    $award = Award::create([
      'year' => '2015',
      'text' => '<p>Die Besten 2012/Hochparterre, Nomination<br>Mehrfamilienhaus Segantinistrasse</p>',
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
