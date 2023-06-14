<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{

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

}
