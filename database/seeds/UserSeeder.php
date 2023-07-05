<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $users = [
      [
        'firstname' => 'Marcel',
        'name'  => 'Stadelmann',
        'email' => 'm@marceli.to',
        'email_verified_at' => \Carbon\Carbon::now(),
        'password' => \Hash::make('7aq31rr'),
        'role' => 'admin'
      ],
      [
        'firstname' => 'Judith',
        'name'  => 'Stutz',
        'email' => 'stutz@bivgrafik.ch',
        'email_verified_at' => \Carbon\Carbon::now(),
        'password' => \Hash::make('jUd1tH5tUtz'),
      ],
      [
        'firstname' => 'Katharina',
        'name'  => 'Sommer',
        'email' => 'sommer@baumbergerstegmeier.ch',
        'email_verified_at' => \Carbon\Carbon::now(),
        'password' => \Hash::make('k4th4rin@SomM3R'),
      ],
      [
        'firstname' => 'Carla',
        'name'  => 'Petraschke',
        'email' => 'petraschke@bivgrafik.ch',
        'email_verified_at' => \Carbon\Carbon::now(),
        'password' => \Hash::make('c4RL@P3tr4sChK3'),
      ],
      [
        'firstname' => 'Karin',
        'name'  => 'Stegmeier',
        'email' => 'stegmeier@baumbergerstegmeier.ch',
        'email_verified_at' => \Carbon\Carbon::now(),
        'password' => \Hash::make('k4r+in3t3gm3Ler'),
      ],
      [
        'firstname' => 'Daniel',
        'name'  => 'Kaschub',
        'email' => 'kaschub@baumbergerstegmeier.ch',
        'email_verified_at' => \Carbon\Carbon::now(),
        'password' => \Hash::make('D4n13LK4$cHuB'),
      ],
      [
        'firstname' => 'Ján',
        'name'  => 'Vyšný',
        'email' => 'vysny@baumbergerstegmeier.ch',
        'email_verified_at' => \Carbon\Carbon::now(),
        'password' => \Hash::make('j@NVy$nY23'),
      ],
    ];

    foreach($users as $user)
    {
      User::create([
        'firstname' => $user['firstname'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'email_verified_at' => $user['email_verified_at'],
        'password' => $user['password'],
        'role' => 'admin'
      ]);
    }
  }
}
