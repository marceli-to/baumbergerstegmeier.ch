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
      'description' => '<p>Presse: <a href="mailto:test@test.ch" class="icon-arrow-right-up">Katharina Sommer</a></p><p><a href="/jobs" class="icon-arrow-right-up">Bewerbungen</a></span></p>',
      'maps_uri' => 'https://goo.gl/maps/D9K5vEgjLQuWA4ncA',
    ]);
  }
}
