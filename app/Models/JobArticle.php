<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobArticle extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   
	protected $fillable = [
    'title',
    'description',
    'order',
    'publish',
  ];

}
