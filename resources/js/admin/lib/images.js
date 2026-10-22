/**
 * Url of an uploaded image for the admin (ImageController):
 *
 * 'thumbnail'  300 × 300, cropped to fill
 * 'original'   the upload (the cropper's source)
 * 'crop'       the stored crop, longer side maxSize (1500). The coords are
 *              part of the URL, so a re-crop gets a new one.
 */
export function imageUrl(image, template = 'crop', maxSize = 1500) {
  if (template !== 'crop') {
    return `/img/${template}/${image.name}`;
  }
  const coords = [image.coords_w, image.coords_h, image.coords_x, image.coords_y].map(value => Math.floor(value || 0));
  return `/img/crop/${image.name}/${maxSize}/${coords.join(',')}`;
}

/**
 * Resolves once the browser has loaded the image at url.
 */
export function preloadImage(url) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => resolve(url);
    img.onerror = reject;
    img.src = url;
  });
}
