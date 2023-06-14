<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectState extends Pivot
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'project_id',
    'state_id'
  ];
}
