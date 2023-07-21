<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CvCategory extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'description',
    'order',
    'publish',
  ];

  /**
   * The cvs (many) that belong to this model.
   */

  public function cvs()
  {
    return $this->belongsToMany(Cv::class);
  }

}
