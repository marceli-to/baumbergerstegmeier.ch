<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Publication extends Base
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
    'description',
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

  public function publishedImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('publish', 1)->orderBy('order');
  }

  /**
   * The images that belong to this model.
   */

  public function files()
  {
    return $this->morphMany(File::class, 'fileable')->orderBy('order');
  }

  public function publishedFiles()
  {
    return $this->morphMany(File::class, 'fileable')->where('publish', 1)->orderBy('order');
  }

  public function publishedFile()
  {
    return $this->morphOne(File::class, 'fileable')->where('publish', 1)->orderBy('order');
  }

}
