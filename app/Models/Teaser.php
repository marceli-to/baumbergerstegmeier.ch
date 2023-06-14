<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Teaser extends Model
{
  protected $fillable = [
    'type',
    'image_id',
    'project_id',
    'article_id',
    'position',
    'column',
    'publish'
  ];

  /**
   * The image that belongs to this teaser item.
   */

  public function image()
  {
    return $this->belongsTo(Image::class);
  }
 
  /**
  * The project that belongs to this teaser item.
  */

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
  * The article that belongs to this teaser item.
  */

  public function article()
  {
    return $this->belongsTo(Article::class);
  }

}
