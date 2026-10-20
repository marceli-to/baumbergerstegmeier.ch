<?php
namespace App\Support;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Interfaces\ImageInterface;
use League\Glide\Manipulators\BaseManipulator;

/**
 * CMYK uploads to sRGB, as image-cache's GD did. Imagick would otherwise
 * write CMYK JPEGs (wrong colours in some browsers) and WebP/AVIF can't
 * hold CMYK at all.
 */
class SrgbManipulator extends BaseManipulator
{
  public function getApiParams(): array
  {
    return [];
  }

  public function run(ImageInterface $image): ImageInterface
  {
    return $image->colorspace() instanceof Rgb ? $image : $image->setColorspace(Rgb::class);
  }
}
