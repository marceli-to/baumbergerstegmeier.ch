<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Category extends Base
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'description',
    'slug',
    'order',
    'publish',
  ];

  /**
   * The projects that belong to this category
   */
  
  public function projects()
  {
    return $this->belongsToMany(Project::class);
  }

  /**
   * The published projects that belong to this category
   */
  
  public function publishedProjects()
  {
    return $this->belongsToMany(Project::class)->where('publish', '=', '1');
  }

  public function featuredProjects()
  {
    return $this->belongsToMany(Project::class)->where('publish', '=', '1')->where('feature', '=', '1');
  }

}
