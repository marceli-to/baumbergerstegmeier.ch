<?php
namespace App\Http\Controllers;
use App\Actions\Image\RenderAction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serves /img/... through Glide, keeping the URL shapes and rules of the
 * former marceli-to/image-cache package:
 *
 *   /img/original/{file}
 *   /img/thumbnail/{file}                            admin, 300 × 300
 *   /img/crop/{file}/{maxSize?}/{coords?}/{ratio?}   coords = w,h,x,y
 *
 * Crops take ?fm=webp|avif (<x-image> emits them). A server that can't
 * write the format answers with the upload's own.
 */
class ImageController extends Controller
{
  /**
   * Longest side when the URL has no size (image-cache's Crop default)
   */
  public const DEFAULT_SIZE = 2400;

  /**
   * Largest size a URL may ask for (image-cache's max_size default)
   */
  public const MAX_SIZE = 2400;

  public const CACHE_SECONDS = 3600;

  public function original(string $filename): BinaryFileResponse
  {
    return response()->file($this->source($filename), $this->cacheHeaders());
  }

  /**
   * 300 × 300, cropped to fill
   */
  public function thumbnail(string $filename): Response
  {
    $this->source($filename);

    return $this->respond($filename, ['w' => 300, 'h' => 300, 'fit' => 'crop']);
  }

  /**
   * Crop to the coords (or centre-crop to the ratio when there are none),
   * then scale the longer side down to maxSize. Never upscales.
   */
  public function crop(Request $request, string $filename, ?string $maxSize = null, ?string $coords = null, ?string $ratio = null): Response
  {
    $source = $this->source($filename);
    $maxSize = $this->size($maxSize);
    [$width, $height] = $this->orientedSize($source);

    $crop = $this->coords($coords, $width, $height) ?? $this->ratio($ratio, $width, $height);
    $params = [];

    if ($crop)
    {
      $params['crop'] = implode(',', $crop);
      [$width, $height] = $crop;
    }

    $params += $this->bound($width, $height, $maxSize) + ['fit' => 'max'];

    if ($request->has('fm'))
    {
      abort_unless(in_array($request->query('fm'), ['webp', 'avif'], true), 404);
      $params['fm'] = $request->query('fm');
    }

    return $this->respond($filename, $params);
  }

  /**
   * Bound the longer side only. The unbounded other side keeps Glide from
   * flooring the derived dimension, so sizes match image-cache's.
   */
  protected function bound(int $width, int $height, int $maxSize): array
  {
    return $height > $width
      ? ['w' => 99999, 'h' => $maxSize]
      : ['w' => $maxSize, 'h' => 99999];
  }

  protected function respond(string $filename, array $params): Response
  {
    [$image, $format, $fellBack] = (new RenderAction)->execute($filename, $params);

    abort_if($image === null, 500, 'Image could not be rendered');

    // A fallback is cached briefly, so the requested format is tried again soon
    return response($image, 200, [
      'Content-Type' => 'image/' . ($format === 'jpg' ? 'jpeg' : $format),
    ] + $this->cacheHeaders($fellBack ? 300 : self::CACHE_SECONDS));
  }

  /**
   * Absolute path of an uploaded image; 404 for anything else
   */
  protected function source(string $filename): string
  {
    $path = storage_path('app/public/uploads/' . $filename);
    abort_unless(
      $filename === basename($filename)
        && preg_match('/\.(jpe?g|png)$/i', $filename)
        && is_file($path),
      404
    );

    return $path;
  }

  protected function size(?string $maxSize): int
  {
    if ($maxSize === null)
    {
      return self::DEFAULT_SIZE;
    }

    abort_unless(ctype_digit($maxSize) && (int) $maxSize > 0 && (int) $maxSize <= self::MAX_SIZE, 404);

    return (int) $maxSize;
  }

  /**
   * w,h,x,y as ints, clamped to the image as image-cache did; null when
   * there is no crop ('0,0,0,0'). The admin sends the stored decimals.
   */
  protected function coords(?string $coords, int $width, int $height): ?array
  {
    if ($coords === null)
    {
      return null;
    }

    abort_unless(preg_match('/^\d+(\.\d+)?(,\d+(\.\d+)?){3}$/', $coords), 404);

    [$w, $h, $x, $y] = array_map(fn ($value) => (int) floor((float) $value), explode(',', $coords));

    if ($w === 0 && $h === 0 && $x === 0 && $y === 0)
    {
      return null;
    }

    $w = max(1, min($w, $width - $x));
    $h = max(1, min($h, $height - $y));

    return [$w, $h, $x, $y];
  }

  /**
   * Centred crop to w:h, w/h or wxh
   */
  protected function ratio(?string $ratio, int $width, int $height): ?array
  {
    if ($ratio === null)
    {
      return null;
    }

    abort_unless(preg_match('/^([1-9]\d*)[:\/x]([1-9]\d*)$/', $ratio, $matches), 404);

    $target = $matches[1] / $matches[2];
    $current = $width / $height;

    if ($current > $target)
    {
      $w = (int) round($height * $target);
      return [$w, $height, (int) round(($width - $w) / 2), 0];
    }

    if ($current < $target)
    {
      $h = (int) round($width / $target);
      return [$width, $h, 0, (int) round(($height - $h) / 2)];
    }

    return null;
  }

  /**
   * Width and height as displayed, i.e. after EXIF rotation
   */
  protected function orientedSize(string $path): array
  {
    [$width, $height] = getimagesize($path);
    $orientation = function_exists('exif_read_data') ? (@exif_read_data($path)['Orientation'] ?? 1) : 1;

    return $orientation >= 5 ? [$height, $width] : [$width, $height];
  }

  protected function cacheHeaders(int $maxAge = self::CACHE_SECONDS): array
  {
    return ['Cache-Control' => 'max-age=' . $maxAge . ', public'];
  }
}
