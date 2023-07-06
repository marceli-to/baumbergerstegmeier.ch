<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class Image extends Model
{
  use HasFactory, SoftDeletes, HasFlags;

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
    'orientation',
    'ratio',
		'coords_w',
    'coords_h',
    'coords_x',
    'coords_y',
    'order',
    'preview',
    'publish',
    'cover',
    'worklist',
    'locked',
    'imageable_id',
    'imageable_type'
  ];

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */

  protected $appends = [
    'coords',
    'has_coords',
  ];

  /**
   * Relationships
   * 
   */

  public function imageable()
  {
    return $this->morphTo();
  }

	/**
   * Scope for preview images
   */

	public function scopePreview($query)
	{
		return $query->where('preview', 1);
	}

	/**
   * Scope for published images
   */

	public function scopePublish($query)
	{
		return $query->where('publish', 1);
	}

	/**
   * Scope for locked images
   */

	public function scopeLocked($query)
	{
		return $query->where('locked', 1);
	}

	/**
	 * Get the cropping coordinates
	 *
	 * @return string
	 */

	public function getCoordsAttribute()
	{
    $coords = '0,0,0,0';
    if ($this->coords_w && $this->coords_h)
    {
      $coords = floor($this->coords_w) . ',' .  floor($this->coords_h) . ',' .  floor($this->coords_x) . ',' .  floor($this->coords_y);
    }
    return $coords;
  }

  /**
   * Check for cropping coordinates
   * @return boolean
   */

  public function getHasCoordsAttribute()
  {
    return ($this->coords_w && $this->coords_h && $this->coords_w > 0 && $this->coords_h > 0);
  }

}
