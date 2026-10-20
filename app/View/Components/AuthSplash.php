<?php
namespace App\View\Components;
use App\Models\Image;
use App\Models\Project;
use App\Support\ImageSupport;
use Illuminate\View\Component;

/**
 * A random published project's cover as the background of the login and
 * password screens (not the error pages, which use the same layout)
 */
class AuthSplash extends Component
{
  public ?Image $image;

  public function __construct()
  {
    $this->image = Image::where('publish', 1)
      ->where('cover', 1)
      ->where('imageable_type', Project::class)
      ->whereIn('imageable_id', Project::published()->select('id'))
      ->inRandomOrder()
      ->first();
  }

  /**
   * CSS background-image with AVIF/WebP via image-set(), plain url() first
   * for browsers without image-set() type()
   */
  public function background(int $size): string
  {
    $url = fn (?string $format = null) => '/img/crop/' . $this->image->name . '/' . $size . '/' . $this->image->coords . ($format ? '?fm=' . $format : '');
    $set = collect(ImageSupport::modernFormats())
      ->map(fn ($format) => "url('{$url($format)}') type('image/{$format}')")
      ->push("url('{$url()}') type('image/jpeg')")
      ->implode(', ');

    return "background-image: url('{$url()}'); background-image: image-set({$set});";
  }

  public function render()
  {
    return view('components.auth-splash');
  }
}
