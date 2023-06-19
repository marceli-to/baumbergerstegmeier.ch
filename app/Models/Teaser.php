<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class Teaser extends Base
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

  protected $appends = [
    'isProject',
    'isArticle',
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

  public function getIsArticleAttribute()
  {
    return $this->article_id !== null;
  }

  public function getIsProjectAttribute()
  {
    return $this->project_id !== null && $this->image_id !== null;
  }

}
