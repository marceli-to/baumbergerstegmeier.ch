<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
  protected $fillable = [
    'title',
    'text',
    'info',
    'periode',
    'year',
    'location',
    'order',
    'type_id',
    'publish',
    'feature',
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'category_ids', 'state_ids',
  ];

  /**
   * The type that belong to this project.
   */
  
  public function type()
  {
    return $this->belongsTo(Type::class);
  }

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
   * The images that belong to this model.
   */

  public function images()
  {
    return $this->morphMany(Image::class, 'imageable')->orderBy('order');
  }
 
  public function publishedImages()
  {
    return $this->morphMany(Image::class, 'imageable')->where('publish', 1)->orderBy('order');
  }

  /**
   * Get array of ids from the m:n category relationship
   *
   */

  public function getCategoryIdsAttribute()
  {
    return $this->categories->pluck('id');
  }

  /**
   * Get array of ids from the m:n state relationship
   *
   */

  public function getStateIdsAttribute()
  {
    return $this->states->pluck('id');
  }
 
}
