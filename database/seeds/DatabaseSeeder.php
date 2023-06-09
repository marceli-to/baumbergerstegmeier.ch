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
    $this->call([
      \Database\Seeders\UserSeeder::class,
      \Database\Seeders\JobSeeder::class,
      \Database\Seeders\JobArticleSeeder::class,
      \Database\Seeders\ContactSeeder::class,
      \Database\Seeders\AwardSeeder::class,
    ]);
  }
}
