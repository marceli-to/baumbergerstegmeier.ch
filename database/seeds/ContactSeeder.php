<?php
namespace Database\Seeders;
use App\Models\Contact;
use App\Models\Image;
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
      'description' => '<p>Presse: <a href="mailto:test@test.ch" class="icon-arrow-right-up">Katharina Sommer</a><br><a href="/jobs" class="icon-arrow-right-up">Bewerbungen</a><br><a href="/team" class="icon-arrow-right-up">Team</a></p>',
      'maps_uri' => 'https://goo.gl/maps/D9K5vEgjLQuWA4ncA',
      'imprint' => '<p>© 2021 Baumberger Stegmeier Architektur<br>Alle Rechte vorbehalten</p>',
      'privacy' => '<p><strong>Lorem ipsum</strong></p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla euismod, nisl quis tincidunt ultricies, nunc nisl aliquam nunc, quis aliquam nisl nunc si</p>',
    ]);

    for($i = 1; $i <= 2; $i++)
    {
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
        'imageable_type' => Contact::class,
        'caption' => '',
        'order' => $i,
        'publish' => 1
      ]);
    }
  }
}
