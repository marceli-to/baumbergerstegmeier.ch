<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    setLocale(LC_ALL, 'de');
    \Carbon\Carbon::setLocale('de');

    // Add 'whereLike' to the query builder
    Builder::macro('whereLike', function(string $attribute, string $searchTerm) {
      return $this->where($attribute, 'LIKE', "%{$searchTerm}%");
    });
    
    // Add 'orWhereLike' to the query builder
    Builder::macro('orWhereLike', function(string $attribute, string $searchTerm) {
      return $this->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
    });
  }
}
