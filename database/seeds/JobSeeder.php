<?php
namespace Database\Seeders;
use App\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Job::create([
      'text' => 'Zur Zeit sind alle unsere Stellen besetzt.',
      'description' => 'Jobs @ Baumberger Stegmeier Architektur',
    ]);
  }
}
