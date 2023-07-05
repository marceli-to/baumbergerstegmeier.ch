<?php
namespace Database\Seeders;
use App\Models\Employee;
use App\Models\Cv;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $firstnames = ["Hans", "Anna", "Michael", "Sarah", "Markus", "Julia", "Christoph", "Lisa", "Thomas", "Laura", "Stefan", "Jennifer", "Martin", "Nicole", "Andreas", "Maria", "Sebastian", "Sabine", "Patrick", "Katrin","Jan", "Sophie", "Matthias", "Melanie", "Christian", "Monika", "Alexander", "Sandra", "Peter", "Veronika", "Daniel", "Carina", "Philipp", "Christine", "Benjamin", "Katharina", "Florian", "Stephanie", "Oliver", "Susanne", "David", "Jana", "Marc", "Nadine", "Fabian", "Vanessa", "Robert", "Petra", "Simon", "Nina", "Max", "Jessica", "Erik", "Sabrina", "Jonas", "Michelle", "Tobias", "Carolin", "Dominik", "Marina", "Lukas", "Simone", "Marcel", "Isabella", "Julian", "Hannah", "Kevin", "Lisa", "Paul", "Anja", "Eric", "Steffi", "Felix", "Lea", "Tim", "Eva", "Christoph", "Carmen", "Patrick", "Lena", "Johannes", "Yvonne", "Bastian", "Tanja", "Sven", "Annika", "Nico", "Laura", "Raphael", "Birgit"];
    $names = ["Müller", "Schmidt", "Wagner", "Becker", "Zimmermann", "Klein", "Schuster", "Weber", "Fischer", "Richter", "Bergmann", "Lehmann", "Huber", "Keller", "Schmitt", "Neumann", "Braun", "Hoffmann", "Schäfer", "Krüger", "Hahn", "Schneider", "Schulz", "Fuchs", "Kraft", "Bauer", "Kühn", "Scholz", "Lang", "Vogel", "Krause", "Roth", "Frank", "Werner", "Wolf", "Schwarz", "Meyer", "Winkler", "Ritter", "Hermann", "Schreiber", "Haas", "Schubert", "Kraus", "Kaiser", "Friedrich", "Reichert", "Thiel", "Seidel", "Mayr", "Hartmann", "Voigt", "Kuhn", "Lechner", "Schiller", "Böhm", "Lenz", "Mayer", "Heinrich", "Baumann", "Weiß", "Engel", "Schlegel", "Kunz", "Horn", "Walter", "Graf", "Paulus", "Eckert", "Lorenz", "Riedl", "Günther", "Böcker", "Jung", "Schön", "Ebert", "Hofmann", "König", "Ackermann", "Arnold", "Brandt", "Kramer", "Schulte", "Wittmann", "Ziegler", "Bender", "Vetter", "Kühnert", "Lange", "Pfeiffer"];
   
    $titles = [
      'Dipl. Architekt HTL BSA SIA',
      'Architekt BA FHZ',
      'Architekt Msc AAM',
      'Dipl.-Ing. Architekt TU',
    ];

    $leadership_titles = [
      'Partner',
      'Mitglied der Geschäftsleitung',
    ];

    $cv = [
      'seit 2003' => 'Gemeinsames Büro mit Karin Stegmeier in Zürich',
      '2001–03' => 'Assistenz bei Gastprofessor F. Riegler, ETH Zürich',
      '1996–05' => 'Mitarbeit bei Miller & Maranta, Basel',
      '1995–96' => 'Mitarbeit bei Graber Pulver Architekten, Zürich',
      '1994'  => 'Mitarbeit bei Gigon / Guyer Architekten, Zürich',
      '1991–95' => 'Architekturstudium am Technikum Winterthur',
      '1991' => 'Mitarbeit im Architekturbüro Prof. R. Leu, Wetzikon',
      '1986–90' => 'Hochbauzeichnerlehre',
      '1969' => 'geboren in Zürich',
    ];


    // BSA, Leadership
    for($i = 0; $i < 6; $i++)
    {
      $firstname = $firstnames[random_int(0, count($firstnames) - 1)];
      $name = $names[random_int(0, count($names) - 1)];
      $email = strtolower($firstname) . '.' . strtolower($name) . '@bsa.ch';

      $employee = Employee::create([
        'firstname' => $firstname,
        'name' => $name,
        'title' => $titles[random_int(0, count($titles) - 1)] . ' / ' . $leadership_titles[random_int(0, count($leadership_titles) - 1)],
        'email' => str_replace(['ä', 'ö', 'ü'], ['ae', 'oe', 'ue'], $email),
        'team_id' => 1,
        'employee_category_id' => 1,
        'order' => $i
      ]);

      foreach($cv as $periode => $description)
      {
        Cv::create([
          'periode' => $periode,
          'description' => $description,
          'employee_id' => $employee->id,
        ]);
      }

      // Add cv items with category
      Cv::create([
        'periode' => '2003',
        'description' => 'Gründung Einzelgesellschaft Baumberger & Stegmeier Architekten',
        'employee_id' => $employee->id,
        'cv_category_id' => 1
      ]);
      Cv::create([
        'periode' => '2008',
        'description' => 'Aktiengesellschaft Baumberger & Stegmeier Architekten AG',
        'employee_id' => $employee->id,
        'cv_category_id' => 1
      ]);
      Cv::create([
        'periode' => '2011',
        'description' => 'Gründung BS+EMI Architektenpartner AG',
        'employee_id' => $employee->id,
        'cv_category_id' => 1
      ]);
      Cv::create([
        'periode' => '2006',
        'description' => 'SIA (Schweizerischer Ingenieur- und Architektenverein) Firmenmitglied',
        'employee_id' => $employee->id,
        'cv_category_id' => 2
      ]);
      Cv::create([
        'periode' => '2004',
        'description' => 'SIA FEB (Fachgruppe für die Erhaltung von Bauwerken) Vorstandsmitglied',
        'employee_id' => $employee->id,
        'cv_category_id' => 2
      ]);
      Cv::create([
        'periode' => '2002',
        'description' => 'BSA (Bund Schweizer Architekten)',
        'employee_id' => $employee->id,
        'cv_category_id' => 2
      ]);
    }

    // BSA, Employees
    for($i = 0; $i < 11; $i++)
    {
      $employee = Employee::create([
        'firstname' => $firstnames[random_int(0, count($firstnames) - 1)],
        'name' => $names[random_int(0, count($names) - 1)],
        'title' => $titles[random_int(0, count($titles) - 1)],
        'team_id' => 1,
        'employee_category_id' => 2,
        'order' => $i
      ]);
    }

    // BS+EMI, Leadership
    for($i = 0; $i < 5; $i++)
    {
      $employee = Employee::create([
        'firstname' => $firstnames[random_int(0, count($firstnames) - 1)],
        'name' => $names[random_int(0, count($names) - 1)],
        'title' => $titles[random_int(0, count($titles) - 1)] . ' / ' . $leadership_titles[random_int(0, count($leadership_titles) - 1)],
        'team_id' => 2,
        'employee_category_id' => 1,
        'order' => $i
      ]);
    }

    // BSA, Employees
    for($i = 0; $i < 14; $i++)
    {
      $employee = Employee::create([
        'firstname' => $firstnames[random_int(0, count($firstnames) - 1)],
        'name' => $names[random_int(0, count($names) - 1)],
        'title' => $titles[random_int(0, count($titles) - 1)],
        'team_id' => 2,
        'employee_category_id' => 2,
        'order' => $i
      ]);
    }

    // BSA, Employees
    for($i = 0; $i < 50; $i++)
    {
      $employee = Employee::create([
        'firstname' => $firstnames[random_int(0, count($firstnames) - 1)],
        'name' => $names[random_int(0, count($names) - 1)],
        'title' => $titles[random_int(0, count($titles) - 1)],
        'team_id' => random_int(1, 2),
        'employee_category_id' => 3,
        'order' => $i
      ]);
    }

  }
}
