<?php
namespace Database\Seeders;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Contact::create([
      'address' => '<p>Baumberger Stegmeier Architektur<br>Weststrasse 70, CH-8003 Zürich<br>info@baumbergerstegmeier.ch, +41 43 500 54 00',
      'description' => '<p>Presse: <span class="icon-arrow-right-up"><a href="mailto:test@test.ch">Katharina Sommer</a></span></p><p><span class="icon-arrow-right-up"><a href="/jobs">Bewerbungen</a></span></p>'
    ]);
    $record->flag('isPublished', 1);
  }
}
