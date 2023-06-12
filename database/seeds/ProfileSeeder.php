<?php
namespace Database\Seeders;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Profile::create([
      'title_bsa' => 'Baumberger Stegmeier Architektur',
      'text_bsa' => '<p>Das Architekturbüro Baumberger Stegmeier wurde 2004 von Karin Stegmeier und Peter Baumberger in Zürich gegründet und beschäftigt heute rund 30 Mitarbeitende. Seit 20xx erweitern zudem Daniel Kaschub und Mirko Schlemminger sowie seit 20xx Arno Bruderer die Geschäftsleitung.</p><p>Unser Tätigkeitsfeld reicht von städtebaulichen Planungen bis hin zum Entwurf und der Realisierung von Neubauten mit eigener Bauleitung. Schwerpunkte bilden dabei neben dem Wohnungsbau Schul- und Gesundheitsbauten. Darüber hinaus gilt unser Interesse dem Umbau und der Sanierung von teilweise denkmalpflegerisch geschützten Gebäuden im historischen Kontext.</p><p>Die Architektur von Baumberger Stegmeier ist eine gesamtheitliche, die den baulichen Kontext, die Bedürfnisse der Menschen sowie ein ökologisch nachhaltiges Denken gepaart mit konstruktiven und wirtschaftlichen Aspekten gleichermassen berücksichtigt. Wir denken aus dem Alltäglichen heraus, suchen nach einem hohen Gebrauchswert und legen Wert auf nachhaltige Grundrisstypologien, die eine räumliche Vielfalt aufweisen.</p>',
      'title_bsemi' => 'BS+EMI Architektenpartner',
      'text_bsemi' => '<p>Die kontinuierliche Zusammenarbeit mit Edelaar Mosayebi Inderbitzin Architekten hat 2011 zur Gründung der BS+EMI Architektenpartner AG geführt. In dieser Zusammensetzung beschäftigen sich die sechs Partnerinnen und Partner, Peter Baumberger, Karin Stegmeier, Ron Edelaar, Elli Mosayebi, Christian Inderbitzin und seit 2017 Phillip Türich, mit grossmassstäblichen Projekten, Projektwettbewerben und städtebaulichen Studien. Die beiden Partnerbüros behalten weiterhin ihre Eigenständigkeit.</p>',
    ]);
    $record->flag('isPublish', 1);
  }
}
