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
    'title_menu',
    'title_worklist',
    'text',
    'info',
    'year',
    'location',
    'type',
    'order',
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
    'category_ids', 
    'state_ids', 
    'worklist_title_mobile',
    'worklist_title_desktop',
  ];


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

  public function workListImage()
  {
    return $this->morphOne(Image::class, 'imageable')->where('publish', 1)->where('worklist', 1);
  }

  /**
   * Scope for published projects
   */

  public function scopePublished($query)
  {
    return $query->where('publish', '=', '1');
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

  /**
   * Get the worklist title for mobile, which consists of:
   * - title_worklist (if available, title if not)
   * - location (separated by a comma)
   * 
   */

  public function getWorklistTitleMobileAttribute()
  {
    $title = $this->title_worklist ? $this->title_worklist : $this->title;
    $location = $this->location ? $this->location : '';
    return $title . ($location ? ', ' . $location : '');
  }

  /**
   * Get the worklist title for desktop, which consists of:
   * - title_worklist (if available, title if not)
   * 
   */

  public function getWorklistTitleDesktopAttribute()
  {
    return $this->title_worklist ? $this->title_worklist : $this->title;
  }

 
}
