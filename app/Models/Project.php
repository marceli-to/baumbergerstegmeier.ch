<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Project extends Base
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
  protected $fillable = [
    'slug',
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
    'landing',
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

  public function coverImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('publish', 1)->where('cover', 1);
  }

  /**
   * Scope for featured projects
   */

  public function scopeFeatured($query)
  {
    return $query->where('feature', '=', '1')->where('publish', '=', '1');
  }

  /**
   * Scope for landing page projects
   */

  public function scopeLanding($query)
  {
    return $query->where('landing', '=', '1')->where('publish', '=', '1')->where('feature', '=', '1');
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
