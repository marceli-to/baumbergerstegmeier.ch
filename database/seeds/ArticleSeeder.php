<?php
namespace Database\Seeders;
use App\Models\Article;
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
    Article::create([
      'category' => 'Jobs',
      'title' => 'Architekt:in',
      'text' => '<p>Gesucht ab Juli 2023 zur Mitarbeit an anspruchsvollen Projekten in der Ausführung und an Wettbewerben</p>'
    ]);

    Article::create([
      'category' => 'Auszeichnung',
      'title' => 'Architekturpreis Kanton Zürich',
      'text' => '<p>Musterhaus wird gleich zweifach ausgezeichnet!</p>'
    ]);

    Article::create([
      'category' => 'Diskussionspodium',
      'title' => 'Gute Bauten trotz oder dank Klimakrise?',
      'text' => '<p>3. Juni 2023, 19.00 Uhr<br>Architekturforum Zürich</p>'
    ]);
  }
}
