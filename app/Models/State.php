<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class State extends Base
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'slug',
    'description',
    'order',
    'publish',
    'show_in_menu',
    'has_landing'
  ];

  /**
   * The projects that belong to this state
   */
  
  public function projects()
  {
    return $this->hasMany(Project::class);
  }

  /* Scope for 'has_landing' */
  public function scopeHasLanding($query)
  {
    return $query->where('has_landing', 1);
  }

  /**
   * The published projects that belong to this state
   */
  
  public function publishedProjects()
  {
    return $this->hasMany(Project::class)->where('publish', '=', '1');
  }

  public function featuredProjects()
  {
    return $this->hasMany(Project::class)->where('publish', '=', '1')->where('feature', '=', '1');
  }

  public function hasCategories()
  {
    return $this->show_in_menu;
  }
}
