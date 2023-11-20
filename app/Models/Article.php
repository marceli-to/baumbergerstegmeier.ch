<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'category',
    'title',
    'text',
    'link',
    'publish',
  ];

  protected $attributes = [
    'link_target' => '_blank',
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

  public function setLinkAttribute($value)
  {
    $this->attributes['link'] = preg_match("~^(?:f|ht)tps?://~i", $value) ? $value : "https://".$value;
  }

  public function getLinkTargetAttribute($value)
  {
    // if $this->links contains 'baumberger-stegmeier.ch', set target to '_self'
    if (strpos($this->link, 'baumberger-stegmeier.ch') !== false || strpos($this->link, 'baumbergerstegmeier.ch') !== false) {
      return '_self';
    }
    return '_blank';
  }
}
