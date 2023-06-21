<?php
namespace Database\Seeders;
use App\Models\Article;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Article::create([
      'category' => 'Jobs',
      'title' => 'Architekt:in',
      'text' => '<p>Gesucht ab Juli 2023 zur Mitarbeit an anspruchsvollen Projekten in der Ausführung und an Wettbewerben</p>'
    ]);

    $record = Article::create([
      'category' => 'Auszeichnung',
      'title' => 'Architekturpreis Kanton Zürich',
      'text' => '<p>Musterhaus wird gleich zweifach ausgezeichnet!</p>'
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
      'imageable_id' => $record->id,
      'imageable_type' => Article::class,
      'caption' => '',
      'order' => 1,
      'publish' => 1
    ]);

    $record = Article::create([
      'category' => 'Diskussionspodium',
      'title' => 'Gute Bauten trotz oder dank Klimakrise?',
      'text' => '<p>3. Juni 2023, 19.00 Uhr<br>Architekturforum Zürich</p>'
    ]);

    $record = Article::create([
      'category' => 'Vortrag',
      'title' => 'Nachhaltige Architektur: Design für eine grüne Zukunft',
      'text' => '<p>10. Juli 2023, 18:30 Uhr<br>Stadtplanungsamt Frankfurt</p>'
    ]);

    $record = Article::create([
      'category' => 'Ausstellung',
      'title' => 'Die Kunst des Bauens: Meisterwerke der Architekturgeschichte',
      'text' => '<p>25. August 2023, 11:00 Uhr<br>Museum für moderne Architektur Berlin</p>'
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
      'imageable_id' => $record->id,
      'imageable_type' => Article::class,
      'caption' => '',
      'order' => 1,
      'publish' => 1
    ]);

    $record = Article::create([
      'category' => 'Diskussionsrunde',
      'title' => 'Architektur im Wandel: Traditionelle vs. Moderne Ansätze',
      'text' => '<p>5. September 2023, 17:00 Uhr<br>Universität für Architektur Wien</p>'
    ]);

    $record = Article::create([
      'category' => 'Workshop',
      'title' => 'Städtebau der Zukunft: Nachhaltige Lösungen für urbane Räume',
      'text' => '<p>15. Oktober 2023, 14:30 Uhr<br>Architekturzentrum München</p>'
    ]);

    $record = Article::create([
      'category' => 'Symposium',
      'title' => 'Architektur und Technologie: Chancen und Herausforderungen',
      'text' => '<p>20. November 2023, 16:00 Uhr<br>Technisches Museum Hamburg</p>'
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
      'imageable_id' => $record->id,
      'imageable_type' => Article::class,
      'caption' => '',
      'order' => 1,
      'publish' => 1
    ]);
    
  }
}
