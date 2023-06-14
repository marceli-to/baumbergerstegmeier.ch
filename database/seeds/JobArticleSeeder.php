<?php
namespace Database\Seeders;
use App\Models\JobArticle;
use Illuminate\Database\Seeder;

class JobArticleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = JobArticle::create([
      'title' => 'Architekt:in mit Ausführungserfahrung ETH/FH (80-100%)',
      'description' => '<p>Wir sind ein führendes Architekturunternehmen, das sich der Schaffung innovativer und nachhaltiger Designs verschrieben hat, die die gebaute Umwelt prägen. Mit einem starken Fokus auf Kreativität, Funktionalität und Kundenzufriedenheit sind wir seit über zwei Jahrzehnten Vorreiter für architektonische Exzellenz. Wir glauben an die Kraft der Zusammenarbeit, und unser Team talentierter Fachleute arbeitet gemeinsam daran, außergewöhnliche Projekte in verschiedenen Bereichen wie Wohn-, Gewerbe- und Institutionellenbau zu realisieren.</p>',
    ]);

    $record = JobArticle::create([
      'title' => 'Praktikant:in',
      'description' => '<p>Gesucht wird zur Mitarbeit an anspruchsvollen Projekten in der Ausführung und an Wettbewerben.</p><p>Wir bieten ein angenehmes Arbeitsklima in einem jungen Team und eine abwechslungsreiche Tätigkeit. Wir erwarten gute CAD-Kenntnisse (vorzugsweise Vectorworks) und gute Deutschkenntnisse in Wort und Schrift.</p>'
    ]);
  }
}
