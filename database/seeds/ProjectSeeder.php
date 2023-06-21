<?php
namespace Database\Seeders;
use App\Models\Project;
use App\Models\Type;
use App\Models\Category;
use App\Models\State;
use App\Models\Image;
use App\Models\Teaser;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $locations = [
      "Zürich",
      "Genf",
      "Basel",
      "Lausanne",
      "Bern",
      "Winterthur",
      "Luzern",
      "St. Gallen",
      "Lugano",
      "Biel/Bienne",
      "Thun",
      "Köniz",
      "La Chaux-de-Fonds",
      "Fribourg",
      "Schaffhausen"
    ];

    $titles = [
      "Villa am Seeufer",
      "Neubau Einfamilienhaus",
      "Renovierung Altbauwohnung",
      "Gartenanlage mit Pool",
      "Bürokomplex im Stadtzentrum",
      "Ferienhaus in den Bergen",
      "Kulturzentrum am Flussufer",
      "Wohnanlage im Grünen",
      "Sanierung historisches Gebäude",
      "Hotel mit Panoramablick",
      "Schlossrestauration",
      "Industriepark am Stadtrand",
      "Wohnhochhaus mit Skygarden",
      "Restaurantumbau in Altstadt",
      "Mehrgenerationenwohnen",
      "Fitnessstudio Design",
      "Hochschulerweiterung",
      "Innenarchitektur Boutique-Hotel",
      "Stadtteilzentrum Neugestaltung",
      "Öffentliche Bibliothek",
      "Studentenwohnheim am Campus",
      "Krankenhaus Erweiterung",
      "Museum für moderne Kunst",
      "Einkaufszentrum Neubau",
      "Sportarena Umbau",
      "Seniorenresidenz im Park",
      "Öffentlicher Platz Umgestaltung",
      "Wohnprojekt für nachhaltiges Wohnen",
      "Forschungslaboratorium",
      "Kino und Eventzentrum",
      "Kulturschule mit Theater",
      "Kongresszentrum am Flughafen",
      "Barrierefreie Wohnanlage",
      "Freizeitpark Neugestaltung",
      "Schwimmbadkomplex mit Sauna",
      "Stadtvillenensemble",
      "Ökohotel im Wald",
      "Verwaltungsgebäude mit Gründach",
      "Sportzentrum mit Sporthalle",
      "Bürolofts im Industriegebiet",
      "Kirchenumbau zu Gemeindezentrum",
      "Wohnprojekt am Fluss",
      "Konzerthalle mit Akustik-Design",
      "Futuristische Brücke",
      "Umnutzung Fabrikgebäude",
      "Hochschulbibliothek",
      "Wohnanlage für junge Familien",
      "Kulturzentrum in historischem Gebäude",
      "Stadtentwicklungskonzept",
      "Bürogebäude mit grüner Fassade",
      "Wohnprojekt am Seeufer",
    ];

    $subtitles = [
      "Modernes Stadthaus mit Panoramablick",
      "Nachhaltiges Wohnprojekt mit grünen Dächern",
      "Futuristische Wolkenkratzer im Herzen der Stadt",
      "Renovierte Altbauvilla mit großem Garten",
      "Innovative Bürogebäude mit smarten Technologien",
      "Urbane Loftwohnungen in historischer Fabrik",
      "Ökologisches Passivhaus mit Energieeffizienz",
      "Luxuriöse Residenzen am Seeufer",
      "Revitalisiertes Industrieareal als Kulturzentrum",
      "Exklusive Penthouse-Suiten mit Blick über die Stadt",
      "Barrierefreies Wohnprojekt für alle Generationen",
      "Designhotel mit einzigartiger Architektur",
      "Kompakte Tiny Houses als nachhaltige Lösung",
      "Innenstadtlage mit urbanem Lebensgefühl",
      "Beeindruckendes Museum für zeitgenössische Kunst",
      "Historisches Schloss mit restaurierten Räumlichkeiten",
      "Moderner Hochschulcampus für Studierende",
      "Architektonisches Meisterwerk als Konzerthalle",
      "Innovatives Wohnprojekt mit ökologischem Fokus",
      "Familienfreundliche Reihenhäuser in grüner Umgebung",
      "Bürokomplex mit flexiblen Arbeitsflächen",
      "Kulturelles Zentrum mit Veranstaltungsräumen",
      "Urbanes Wohnviertel mit vielfältiger Infrastruktur",
      "Neubau mit grünen Innenhöfen und Spielplätzen",
      "Zeitgemäße Seniorenresidenz mit Betreuungsangeboten"
    ];

    $texts = [
      '<p>Im Zentrum der Stadt Winterthur entsteht noch bis 2025 der neue Stadtteil Lokstadt. Den Auftakt bildete das zentrale Hofhaus Krokodil, dessen Struktur und Erscheinung als grosser Hallenbau mit Bezug zu den industriellen Vorfahren des Areals entworfen wurde. Dabei waren die Aussenmantellinien und die Grösse des Innenhofs und somit die hohe Dichte des Gebäudes sowie ein öffentlicher Gewerbesockel bereits im Gestaltungsplan vorgegeben.</p><p>Die soziale Heterogenität des Projekts leistet einen wichtigen Beitrag zur Vitalität des neuen Stadtteils. So wurden die Wohnformen von vier unterschiedlichen Bauherrschaften realisiert: Zum neuen Dialogplatz orientierte Eigentumswohnungen, genossenschaftliche Familien- und Gemeinschaftswohnungen im Kopf der Nordwestseite, Alterswohnungen an der Gasse im Südwesten und kostengünstige Mietwohnungen in Südostlage. Die strukturelle Ordnung des hallenartigen Skelettbaus fasst die diversen Wohnungstypen zu einem Haus zusammen. Wohnungen mit zweiseitiger Ausrichtung und ein Holztragwerk, das als raumbildendes Element die tiefen Grundrisse rhythmisiert und gliedert, prägen das Raumgefüge. Vier grosse Atrien entspannen die hohe Dichte in den Gebäudeecken und führen mit ihren Glasdächern und grosszügig befensterten Innenfassaden die gassenartige, industriell geprägte Erschliessung im Gebäudeinneren fort.</p><p>Eine Verkleidung mit fein reliefierten Glasfaserzementplatten sowie Titanzinkschindeln, in Aluminiumleibungen gefasste vertikale Fensterbänder, die horizontbildende Gliederung und die feine mehrfache Übergiebelung des Gebäudes stammen aus dem Gestaltungskanon der einstigen Industriehallen und thematisieren den Holzbau als verkleidete Konstruktion. Der Innenhof als grosser Binnenraum erinnert mit seiner monumentalen Balkontragstruktur, der Holzfassade und der Ruderalbepflanzung an überkommene Werkhallenräume.</p>',
      '<p>Das Projekt "Alpenrefugium" in Zermatt ist eine luxuriöse Wohnanlage mit Blick auf die majestätischen Schweizer Alpen. Die Architektur des Projekts wurde sorgfältig gestaltet, um sich nahtlos in die natürliche Umgebung einzufügen und gleichzeitig höchsten Wohnkomfort zu bieten. Jede Wohnung verfügt über großzügige Fenster, die einen atemberaubenden Panoramablick auf die umliegende Berglandschaft ermöglichen. Darüber hinaus umfasst das Alpenrefugium Annehmlichkeiten wie ein Wellnesscenter, einen beheizten Pool und eine private Skikabine, um den Bewohnern ein erstklassiges alpines Erlebnis zu bieten.</p><p>Die Lage des Alpenrefugiums ist ideal für Outdoor-Enthusiasten, da es direkt an Wander- und Skiwegen liegt. Nach einem Tag in den Bergen können die Bewohner in die luxuriöse Atmosphäre ihrer Residenzen zurückkehren und sich in der gemütlichen Umgebung entspannen. Das Projekt "Alpenrefugium" verkörpert den Geist der Schweizer Alpen und bietet ein einzigartiges Wohnerlebnis inmitten einer der schönsten Naturlandschaften der Welt.</p>',
      '<p>Das Projekt "Seestadt am Ufer" in Luzern ist ein innovatives Stadtentwicklungsprojekt, das den Lebensstil am Wasser mit modernem Stadtleben vereint. Das Areal wurde sorgfältig geplant, um Wohnungen, Büros, Geschäfte und öffentliche Räume in einer harmonischen Umgebung zu integrieren. Die architektonische Gestaltung des Projekts greift die charakteristischen Merkmale der Stadt auf und kombiniert sie mit zeitgenössischen Elementen.</p><p>Die Bewohner der "Seestadt am Ufer" können von der Nähe zum See und dem atemberaubenden Blick auf das Wasser profitieren. Es wurden Grünflächen geschaffen, um eine angenehme Umgebung für Freizeitaktivitäten im Freien zu bieten. Darüber hinaus gibt es eine Vielzahl von Geschäften, Restaurants und kulturellen Einrichtungen, die das tägliche Leben bereichern. Das Projekt "Seestadt am Ufer" ist ein lebendiger und dynamischer Ort, der sowohl den Bedürfnissen der Bewohner als auch den Anforderungen einer modernen Stadt gerecht wird.</p>',
      '<p>Das "Bergpanorama Residenzen" Projekt in St. Moritz bietet exklusive Wohnungen mit einem atemberaubenden Blick auf die umliegende Berglandschaft. Jede Wohnung wurde mit höchster Qualität und Liebe zum Detail entworfen und bietet luxuriösen Komfort für die anspruchsvollsten Bewohner.</p><p>Die Lage des Projekts ermöglicht es den Bewohnern, die Schönheit der Alpen in vollen Zügen zu genießen. Im Winter bietet das nahegelegene Skigebiet erstklassige Pisten und Abfahrten, während im Sommer Wanderwege und Outdoor-Aktivitäten zur Verfügung stehen. Zur Ausstattung des Bergpanorama Residenzen gehören unter anderem ein Wellnessbereich, ein Fitnessstudio und eine private Tiefgarage.</p><p>Die Architektur des Projekts fügt sich harmonisch in die Umgebung ein und greift lokale Baustile und Materialien auf. Die großen Fenster ermöglichen einen freien Blick auf die umliegende Natur und lassen viel natürliches Licht in die Räume. Das Bergpanorama Residenzen Projekt verkörpert den Geist von St. Moritz und bietet einen exklusiven Rückzugsort für anspruchsvolle Wohnenden.</p>',
      '<p>Das "Wohnensemble am Seeufer" in Genf ist ein einzigartiges architektonisches Projekt, das direkt am Ufer des Genfer Sees liegt. Die modernen Wohnungen bieten nicht nur luxuriösen Komfort, sondern auch einen spektakulären Blick auf den See und die umliegende Landschaft.</p><p>Das Wohnensemble ist so gestaltet, dass es sich nahtlos in die natürliche Umgebung einfügt und gleichzeitig ein modernes und elegantes Design aufweist. Die großzügigen Fenster ermöglichen eine Fülle von natürlichem Licht und schaffen eine Verbindung zur umgebenden Natur. Jede Wohnung verfügt über hochwertige Ausstattung und Annehmlichkeiten, die den Bewohnern ein luxuriöses und komfortables Leben ermöglichen.</p><p>Darüber hinaus bietet das Wohnensemble am Seeufer Zugang zu einer Vielzahl von Freizeitaktivitäten. Bewohner können entlang der Uferpromenade spazieren gehen, Wassersport betreiben oder die nahegelegenen Restaurants und Geschäfte erkunden. Das Projekt "Wohnensemble am Seeufer" in Genf ist ein Ort, an dem Luxus, Natur und Lifestyle aufeinandertreffen.</p>',
    ];

    $captions = [
      "Eine Symphonie aus Glas und Stahl.",
      "Wo Moderne auf Natur trifft.",
      "Städtische Eleganz in ihrer schönsten Form.",
      "Die Schönheit des architektonischen Designs enthüllen.",
      "Die Essenz architektonischer Harmonie einfangen.",
      "Ein Zeugnis menschlicher Genialität.",
      "Wenn Kunstfertigkeit und Funktionalität sich vereinen.",
      "Träume Stein für Stein Wirklichkeit werden lassen.",
      "Ein architektonisches Meisterwerk in der Entstehung.",
      "Die Konturen architektonischer Vorstellungskraft erkunden.",
      "Gelassenheit in Beton und Stein verkörpert.",
      "Intrikate Details, die das Auge fesseln.",
      "Der Tanz von Licht und Schatten.",
      "Eine neue Perspektive auf das städtische Leben.",
      "Die Skyline mit architektonischer Grazie erhöhen.",
      "Wo Tradition auf Innovation trifft.",
      "Eine verwirklichte Vision, ein errichtetes Erbe.",
      "Fenster zur Seele eines Gebäudes.",
      "Die Kraft architektonischer Formen betrachten.",
      "Die verborgenen Geschichten städtischer Landschaften aufdecken."
    ];

    $info = '<h2>'.$subtitles[random_int(0, count($subtitles) - 1)].'</h2><p><strong>Wettbewerb:</strong> 2016, 1. Preis<br><strong>Ausführung:</strong> 2018-2021<br><strong>Bausumme:</strong> ca. 90 Mio. CHF</p><p><strong>Bauherrschaft:</strong> Implenia Schweiz, Dietlikon; Anlagestiftung Adimora, Zürich; Genossenschaft Gesewo, Winterthur; Genossenschaft Gaiwo, Winterthur</p><p><strong>Mitarbeit Wettbewerb:</strong> Peter Baumberger, Karin Stegmeier, Stephan Popp, Monika Kilga, Daniel Kaschub, Arno Bruderer, Marius Rinderknecht, Laura Kübler, Manuela Schneeberger<br><strong>Mitarbeit Ausführung:</strong> Peter Baumberger, Karin Stegmeier, Stephan Popp, Monika Kilga, Daniel Kaschub, Markus Nyfeler, Remo Reichmuth, Nadine Jaberg, Giancarlo Ceriani, Arno Bruderer, Sarah Fahrni, Lars Schriever, Viola Müller, Roman Schober, Urs Bösch, Andreina Schnellmann, Josua Frei</p><p><strong>Totalunternehmer:</strong> Implenia Schweiz AG Modernisation & Development, Winterthur<br><strong>Holzbauingenieur:</strong> Timbatec Holzbauingenieure Schweiz AG, Zürich<br><strong>Bauingenieur:</strong> Dr. Grob & Partner AG, Winterthur<br><strong>HLSK-Planung:</strong> Implenia Schweiz AG. Gisikon<br><strong>Elektroplanung:</strong> Hefti. Hess. Martignoni, Zürich<br><strong>Fassadenplanung:</strong> EBP Schweiz AG, Zürich<br><strong>Bauphysik:</strong> Pirmin Jung Schweiz AG, Rain<br><strong>Brandschutz:</strong> Timbatec Holzbauingenieure Schweiz AG, Zürich<br><strong>Landschaftsarchitektur</strong>: Hager Partner AG, Zürich<br><strong>Fotograf</strong>: Jürg Zimmermann, Zürich</p>';

    $types = Type::get();

    for($i = 0; $i <= 50; $i++)
    {
      $landingAndFeature = random_int(0, 1);

      $project = Project::create([
        'slug'  => 'null',
        'title' => $titles[$i],
        'text'  => $texts[random_int(0, 4)],
        'info'  => $info,
        'location' => $locations[random_int(0, count($locations) - 1)],
        'year'  => rand(2010, 2023),
        'periode' => rand(2010, 2023) . ' – ' . rand(2010, 2023),
        'type_id' => $types->random()->id,
        'order' => $i,
        'publish' => 1,
        'feature' => $landingAndFeature,
        'landing' => $landingAndFeature
      ]);

      $project->slug = \AppHelper::slug($titles[$i]) . '-' . $project->id;
      $project->save();

      // create many-to-many relationships for categories
      $categories = Category::get();
      $project->categories()->attach($categories[random_int(0, count($categories) - 1)]->id);

      // create many-to-many relationships for states
      $states = State::get();
      $project->states()->attach($states[random_int(0, count($states) - 1)]->id);

      // Create images
      for($ii = 1; $ii <= 13; $ii++)
      {
        $image = Image::create([
          'uuid' => \Str::uuid(),
          'name' => 'bas-' . $ii . '.jpg',
          'original_name' => 'bas-' . $ii . '.jpg',
          'orientation' => 'landscape',
          'extension' => 'jpg',
          'size' => 111562,
          'ratio' => '580x386',
          'imageable_id' => $project->id,
          'imageable_type' => Project::class,
          'caption' => $captions[random_int(0, count($captions) - 1)],
          'order' => $ii,
          'cover' => 0,
          'worklist' => 0,
          'publish' => 1
        ]);
      }

      // if landingAndFeature is true, select a random image and set it as cover
      if ($landingAndFeature)
      {
        $image = $project->images->random();
        $image->cover = 1;
        $image->worklist = 1;
        $image->save();
      }
    }

    $projects = Project::featured()->get();

    foreach($projects as $project)
    {
      // col 1
      foreach($project->publishedImages->shuffle() as $key => $image)
      {
        if ($key > 5) break;
        // create a teaser
        $teaser = new Teaser();
        $teaser->type = 'project';
        $teaser->project_id = $project->id;
        $teaser->image_id = $image->id;
        $teaser->column = 0;
        $teaser->position = $key;
        $teaser->save();
      }
      // col 2
      foreach($project->publishedImages->shuffle() as $key => $image)
      {
        if ($key > 6) break;
        // create a teaser
        $teaser = new Teaser();
        $teaser->type = 'project';
        $teaser->project_id = $project->id;
        $teaser->image_id = $image->id;
        $teaser->column = 1;
        $teaser->position = $key;
        $teaser->save();
      }
      // col 3
      foreach($project->publishedImages->shuffle() as $key => $image)
      {
        if ($key > 5) break;
        // create a teaser
        $teaser = new Teaser();
        $teaser->type = 'project';
        $teaser->project_id = $project->id;
        $teaser->image_id = $image->id;
        $teaser->column = 2;
        $teaser->position = $key;
        $teaser->save();
      }
    }
  }
}
