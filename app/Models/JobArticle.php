<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class JobArticle extends Model
{
  use HasFlags;
 
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'title',
    'description',
    'teaser_title',
    'teaser_description',
    'order'
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
   * Get the publish attribute
   * 
   */

  public function getPublishAttribute()
  {
    return $this->hasFlag('isPublish') ? 1 : 0;    
  }
}
