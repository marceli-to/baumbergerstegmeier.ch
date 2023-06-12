<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Employee extends Model
{
  use HasFlags;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'firstname',
    'name',
    'title',
    'email',
    'order',
    'team_id',
    'employee_category_id'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'publish',
  ];


  /*
  |--------------------------------------------------------------------------
  | Relationships
  |--------------------------------------------------------------------------
  |
  |
  */

  /**
   * The team that belong to this model.
   */

  public function team()
  {
    return $this->belongsTo(Team::class);
  }

  /**
   * The employeeCategory that belong to this model.
   */

  public function employeeCategory()
  {
    return $this->belongsTo(EmployeeCategory::class);
  }

  /**
   * The cvs that belong to this model.
   */

  public function cv()
  {
    return $this->hasMany(Cv::class);
  }

  /**
   * Get the publish attribute
   * 
   */

  public function getPublishAttribute()
  {
    return $this->hasFlag('isPublish') ? 1 : 0;    
  }
}
