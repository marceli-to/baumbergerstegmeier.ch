<?php
namespace App\View\Components;
use Illuminate\View\Component;

class Image extends Component
{
  /**
   * Image
   *
   * @var Object
   */
  public $image;

  /**
   * Maximum sizes for responsive images
   *
   * @var Array
   */
  public $maxSizes;

  /**
   * Width of the image
   *
   * @var String
   */
  public $width; 

  /**
   * Height of the image
   *
   * @var String
   */
  public $height; 

  /**
   * Visible state
   *
   * @var Boolean
   */
  public $visible; 

  /**
   * Overlay state
   *
   * @var Boolean
   */
  public $overlay; 

  /**
   * Classes
   *
   * @var String
   */
  public $classes; 

  /**
   * Ratio
   *
   * @var String
   */
  public $ratio; 

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($image = NULL, $maxSizes = [], $width = NULL, $height = NULL, $visible = TRUE, $overlay = FALSE, $classes = '', $ratio = null)
  {
    $this->image = $image;
    $this->maxSizes = $maxSizes;
    $this->width = $width;
    $this->height = $height;
    $this->visible = $visible;
    $this->overlay = $overlay;
    $this->classes = $classes;
    $this->ratio = $ratio;
  }

  /**
   * /img/crop URL of the image at $size, in $format (webp, avif) if given
   */
  public function url(int $size, ?string $format = null): string
  {
    $url = '/img/crop/' . $this->image->name . '/' . $size . '/' . $this->image->coords . ($this->ratio ? '/' . $this->ratio : '');
    return $format ? $url . '?fm=' . $format : $url;
  }

  /**
   * Breakpoints, widest first: the browser takes the first <source> that
   * matches, so (min-width: 1000px) has to come before the rest
   */
  public function breakpoints(): array
  {
    $sizes = $this->maxSizes;
    krsort($sizes);
    return $sizes;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|string
   */
  public function render()
  {
    return view('components.image');
  }
}
