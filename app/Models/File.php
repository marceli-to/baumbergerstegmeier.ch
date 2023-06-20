<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
  use HasFactory, SoftDeletes;

  protected $casts = [
    'created_at' => "datetime:d.m.Y",
  ];

	protected $fillable = [
    'uuid',
    'name',
    'original_name',
    'extension',
    'size',
    'caption',
    'description',
    'order',
    'publish',
    'locked',
    'fileable_id',
    'fileable_type'
  ];

  public function fileable()
  {
    return $this->morphTo();
  }

	/**
   * Scope for published files
   */

	public function scopePublish($query)
	{
		return $query->where('publish', 1);
	}

	/**
   * Scope for locked files
   */

	public function scopeLocked($query)
	{
		return $query->where('locked', 1);
	}
}
