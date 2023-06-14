<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
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
    'publish',
    'team_id',
    'employee_category_id'
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

}
