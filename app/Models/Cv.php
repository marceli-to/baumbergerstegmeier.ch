<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'periode',
    'description',
    'employee_id',
    'cv_category_id',
    'publish',
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

}
