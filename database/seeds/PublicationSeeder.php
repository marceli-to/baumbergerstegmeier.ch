<?php
namespace Database\Seeders;
use App\Models\Publication;
use App\Models\File;
use App\Models\Image;
use Illuminate\Database\Seeder;

class PublicationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $record = Publication::create([
      'year' => '2021',
      'title' => 'Wohlfühlhaus (Haus mit zwei Eingängen)',
      'description' => 'Cornelia Faist Das Ideale Heim 2/2023',
    ]);

    $file = File::create([
      'uuid' => \Str::uuid(),
      'name' => 'bas-test-1.pdf',
      'original_name' => 'bas-test-1.pdf',
      'extension' => 'pdf',
      'size' => 111562,
      'fileable_id' => $record->id,
      'fileable_type' => Publication::class,
      'caption' => 'Das Ideale Heim 2/2023',
      'order' => 1,
      'publish' => 1
    ]);

    $record = Publication::create([
      'year' => '2021',
      'title' => 'Das farbigste Schulhaus der Schweiz',
      'subtitle' => 'Sekundarschulhaus Chliriet',
      'description' => 'Andres Herzog Tagesanzeiger, 17.12.2022',
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
      'imageable_type' => Publication::class,
      'caption' => 'Tagesanzeiger, 17.12.2022',
      'order' => 1,
      'publish' => 1
    ]);

    $record = Publication::create([
      'year' => '2019',
      'title' => 'Die Bedeutung von Barrierefreiheit in der Architektur',
      'subtitle' => 'Inklusives Design für alle Menschen',
      'description' => 'Sarah Müller Barrierefreies Bauen, 25.03.2019',
    ]);

    $record = Publication::create([
      'year' => '2018',
      'title' => 'Die Ästhetik des Brutalismus',
      'description' => 'Lukas Mayer Architekturkritik, 12.07.2018',
    ]);

    $record = Publication::create([
      'year' => '2017',
      'title' => 'Die Rolle der Architektur im sozialen Wandel',
      'subtitle' => 'Gemeinschaftsorientierte Projekte und ihre Auswirkungen',
      'description' => 'Maria Schneider Soziologie und Architektur, 20.11.2017',
    ]);
    $file = File::create([
      'uuid' => \Str::uuid(),
      'name' => 'bas-test-1.pdf',
      'original_name' => 'bas-test-1.pdf',
      'extension' => 'pdf',
      'size' => 111562,
      'fileable_id' => $record->id,
      'fileable_type' => Publication::class,
      'caption' => 'Soziologie und Architektur 11/2017',
      'order' => 1,
      'publish' => 1
    ]);


    $record = Publication::create([
      'year' => '2016',
      'title' => 'Die Zukunft des Städtebaus',
      'description' => 'Felix Klein Stadtentwicklung Zukunft, 30.04.2016',
    ]);

    $record = Publication::create([
      'year' => '2023',
      'title' => 'Bauhaus: Eine Revolution in der Architektur',
      'subtitle' => 'Die Prägung einer neuen Designbewegung',
      'description' => 'Maximilian Wagner Architektur Geschichte, 15.02.2023',
    ]);
        
    $record = Publication::create([
      'year' => '2022',
      'title' => 'Die Architektur der Zukunft',
      'subtitle' => 'Innovative Designs für nachhaltiges Bauen',
      'description' => 'Julia Schmidt Baukunst Magazin, 03.08.2022',
    ]);
    
    $record = Publication::create([
      'year' => '2021',
      'title' => 'Die Renaissance der historischen Bauweisen',
      'subtitle' => 'Traditionelle Techniken im modernen Kontext',
      'description' => 'Sophia Wagner Architekturgeschichte Aktuell, 10.09.2021',
    ]);
     
    $record = Publication::create([
      'year' => '2020',
      'title' => 'Die Integration von Natur in die Architektur',
      'subtitle' => 'Nachhaltige Konzepte für grüne Gebäude',
      'description' => 'David Becker Nachhaltig Bauen, 18.02.2020',
    ]);
    
    $record = Publication::create([
      'year' => '2019',
      'title' => 'Bauwerke als Kunstwerke',
      'subtitle' => 'Die Verschmelzung von Architektur und Kunst',
      'description' => 'Laura Schmidt Architektur Kunst Review, 07.07.2019',
    ]);

    $record = Publication::create([
      'year' => '2016',
      'title' => 'Die Bedeutung von nachhaltiger Energie',
      'subtitle' => 'Ein Blick auf zukunftsweisende Technologien',
      'description' => 'Julia Becker Energie Magazin, 05.03.2016',
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
      'imageable_type' => Publication::class,
      'caption' => 'Hochpartere 9/2016',
      'order' => 1,
      'publish' => 1
    ]);
    $record = Publication::create([
      'year' => '2017',
      'title' => 'Kunst im öffentlichen Raum',
      'subtitle' => 'Die Verbindung von Ästhetik und Stadtplanung',
      'description' => 'Paul Müller Kultur Journal, 18.09.2017',
    ]);
    $file = File::create([
      'uuid' => \Str::uuid(),
      'name' => 'bas-test-1.pdf',
      'original_name' => 'bas-test-1.pdf',
      'extension' => 'pdf',
      'size' => 111562,
      'fileable_id' => $record->id,
      'fileable_type' => Publication::class,
      'caption' => 'Architekturmagazin 9/2017',
      'order' => 1,
      'publish' => 1
    ]);


    $record = Publication::create([
      'year' => '2018',
      'title' => 'Die Auswirkungen des Klimawandels auf die Architektur',
      'description' => 'Sarah Schneider Umwelt Magazin, 07.05.2018',
    ]);

    $record = Publication::create([
      'year' => '2019',
      'title' => 'Revitalisierung von Industriebrachen',
      'subtitle' => 'Umnutzung von alten Fabrikgeländen',
      'description' => 'Markus Wagner Stadtentwicklung Heute, 12.11.2019',
    ]);

    $record = Publication::create([
      'year' => '2020',
      'title' => 'Die Zukunft des Wohnens',
      'subtitle' => 'Innovative Konzepte für zeitgemäße Wohnräume',
      'description' => 'Anna Schmitt Wohnen & Design, 25.06.2020',
    ]);

    $record = Publication::create([
      'year' => '2021',
      'title' => 'Nachhaltiges Bauen mit Holz',
      'subtitle' => 'Umweltfreundliche Lösungen für die Baubranche',
      'description' => 'Maximilian Fischer Bautechnik Magazin, 13.02.2021',
    ]);

    $record = Publication::create([
      'year' => '2022',
      'title' => 'Moderne Architektur: Form und Funktion im Einklang',
      'description' => 'Alexander Weber Städteplanung Journal, 10.11.2022',
    ]);

    $record = Publication::create([
      'year' => '2023',
      'title' => 'Die Bedeutung von Freiflächen in der Stadtplanung',
      'subtitle' => 'Grüne Oasen für eine lebenswerte Umgebung',
      'description' => 'Julia Mayer Urbanistik Magazin, 28.06.2023',
    ]);
    $file = File::create([
      'uuid' => \Str::uuid(),
      'name' => 'bas-test-1.pdf',
      'original_name' => 'bas-test-1.pdf',
      'extension' => 'pdf',
      'size' => 111562,
      'fileable_id' => $record->id,
      'fileable_type' => Publication::class,
      'caption' => 'Urbanistik Magazin, 2023',
      'order' => 1,
      'publish' => 1
    ]);


    $record = Publication::create([
      'year' => '2016',
      'title' => 'Die Ästhetik der Moderne: Bauhaus-Bewegung',
      'subtitle' => 'Einflussreiches Design des 20. Jahrhunderts',
      'description' => 'Martin Schulz Kunst und Architektur, 19.08.2016',
    ]);

    $record = Publication::create([
      'year' => '2017',
      'title' => 'Zukunftstrends in der Innenarchitektur',
      'subtitle' => 'Innovative Ideen für ansprechende Wohnräume',
      'description' => 'Sophie Keller Raumgestaltung Magazin, 04.04.2017',
    ]);

    $record = Publication::create([
      'year' => '2018',
      'title' => 'Die Bedeutung von Licht in der Architektur',
      'description' => 'Daniel Hoffmann Architekturlicht Journal, 22.11.2018',
    ]);

  }
}
