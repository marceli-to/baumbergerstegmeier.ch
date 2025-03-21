<?php
namespace App\Console;
use App\Tasks\Newsletter;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   */
  protected function schedule(Schedule $schedule): void
  {
  }

  /**
   * Register the commands for the application.
   */
  protected function commands(): void
  {
    $this->load(__DIR__.'/Commands');
    require base_path('routes/console.php');
  }
}