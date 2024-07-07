<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectLanding extends Model
{
  protected $table = 'project_landing';

  protected $fillable = [
    'image_id',
    'project_id',
    'position',
    'column',
    'publish',
    'state_id',
    'category_id',
  ];

  // append $type
  protected $appends = [
    'type',
  ];

  public function image()
  {
    return $this->belongsTo(Image::class);
  }

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function state()
  {
    return $this->belongsTo(State::class);
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function getTypeAttribute()
  {
    return 'landing';
  }
}
