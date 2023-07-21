<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Award extends Base
{

  protected $casts = [
    'year' => 'integer',
    'publish' => 'integer',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'year',
    'text',
    'publish',
  ];

  protected $appends = [
    'preview_text'
  ];

  /**
   * The images that belong to this model.
   */

  public function images()
  {
    return $this->morphMany(Image::class, 'imageable')->orderBy('order');
  }

  public function publishedImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('publish', 1)->orderBy('order');
  }

  public function getPreviewTextAttribute()
  {
    // remove all html-tags, replace <br /> and <br> with space
    $text = str_replace('</h3>', ' – ', $this->text);
    $text = strip_tags(str_replace(['<br />', '<br>'], ['&nbsp;'], $text));

    // replace entities like &uuml; &auml; &ouml; &szlig; &quot; &amp; with their corresponding characters
    $text = html_entity_decode($text);

    // create a substring (100 characters)
    $text = substr($text, 0, 75);

    $text = $text . '...';

    return $text;
  }

}
