<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
   * The projects that belong to this state
   */
  
  public function projects()
  {
    return $this->belongsToMany(Project::class);
  }

}
