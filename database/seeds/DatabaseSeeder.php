<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {

    if (App::environment('local'))
    {
      $this->call([
        \Database\Seeders\UserSeeder::class,
        \Database\Seeders\JobSeeder::class,
        \Database\Seeders\JobArticleSeeder::class,
        \Database\Seeders\ContactSeeder::class,
        \Database\Seeders\AwardSeeder::class,
        \Database\Seeders\PublicationSeeder::class,
        \Database\Seeders\ProfileSeeder::class,
        \Database\Seeders\TeamSeeder::class,
        \Database\Seeders\CvCategorySeeder::class,
        \Database\Seeders\EmployeeCategorySeeder::class,
        \Database\Seeders\EmployeeSeeder::class,
        \Database\Seeders\StateSeeder::class,
        \Database\Seeders\CategorySeeder::class,
        \Database\Seeders\ProjectSeeder::class,
        \Database\Seeders\ArticleSeeder::class,
        \Database\Seeders\HomepageTeaserSeeder::class,
      ]);
    }


    if (App::environment('staging'))
    {
      $this->call([
        \Database\Seeders\UserSeeder::class,
        \Database\Seeders\JobSeeder::class,
        \Database\Seeders\JobArticleSeeder::class,
        \Database\Seeders\ContactSeeder::class,
        \Database\Seeders\ProfileSeeder::class,
        \Database\Seeders\TeamSeeder::class,
        \Database\Seeders\CvCategorySeeder::class,
        \Database\Seeders\EmployeeCategorySeeder::class,
        \Database\Seeders\StateSeeder::class,
        \Database\Seeders\CategorySeeder::class,
        \Database\Seeders\ArticleSeeder::class,
      ]);
    }

    if (App::environment('preproduction') || App::environment('production'))
    {
      $this->call([
        \Database\Seeders\UserSeeder::class,
        \Database\Seeders\CvCategorySeeder::class,
        \Database\Seeders\EmployeeCategorySeeder::class,
        \Database\Seeders\StateSeeder::class,
        \Database\Seeders\CategorySeeder::class,
        \Database\Seeders\TeamSeeder::class,
      ]);
    }

  }
}
