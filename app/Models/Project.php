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
    'text',
    'info',
    'periode',
    'year',
    'location',
    'order',
    'type_id',
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
   * Get the publish attribute
   * 
   */

  public function getPublishAttribute()
  {
    return $this->hasFlag('isPublished') ? 1 : 0;    
  }
}
