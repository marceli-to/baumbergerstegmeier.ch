<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Award extends Model
{
  use HasFlags;
 
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'year',
    'title',
    'subtitle',
    'link',
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
    return $this->hasFlag('isPublish') ? 1 : 0;    
  }
}
