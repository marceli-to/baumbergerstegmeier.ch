<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Cv extends Model
{
  use HasFlags;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'periode',
    'description',
    'employee_id',
    'cv_category_id'
  ];

  /**
   * The employee that belong to this model.
   */

  public function employee()
  {
    return $this->belongsTo(employee::class);
  }
 
  /**
   * The category that belong to this model.
   */

  public function category()
  {
    return $this->belongsTo(CvCategory::class, 'cv_category_id');
  }

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
    return $this->hasFlag('isPublished') ? 1 : 0;    
  }
}
