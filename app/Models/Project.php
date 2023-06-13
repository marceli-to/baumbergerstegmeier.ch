<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Project extends Model
{
  use HasFlags;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
  protected $fillable = [
    'title',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'publish',
  ];

  /**
   * The states that belong to this project.
   */
  
   public function states()
   {
     return $this->belongsToMany(State::class);
   }
  /**
   * The categories that belong to this project.
   */
  
   public function categories()
   {
     return $this->belongsToMany(Category::class);
   }

  /**
   * Get the publish attribute
   * 
   */

   public function getPublishAttribute()
   {
     return $this->hasFlag('isPublish') ? 1 : 0;    
   }
}
