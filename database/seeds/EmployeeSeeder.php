<?php
namespace Database\Seeders;
use App\Models\Employee;
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

    // BSA, Leadership
    for($i = 0; $i < 6; $i++)
    {
      $employee = Employee::create([
        'firstname' => $firstnames[random_int(0, count($firstnames) - 1)],
        'name' => $names[random_int(0, count($names) - 1)],
        'title' => $titles[random_int(0, count($titles) - 1)] . ' / ' . $leadership_titles[random_int(0, count($leadership_titles) - 1)],
        'team_id' => 1,
        'employee_category_id' => 1,
        'order' => $i
      ]);
      $employee->flag('isPublish', 1);
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
      $employee->flag('isPublish', 1);
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
      $employee->flag('isPublish', 1);
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
      $employee->flag('isPublish', 1);
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
      $employee->flag('isPublish', 1);
    }

  }
}
