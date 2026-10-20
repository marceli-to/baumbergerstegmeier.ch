import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete } from '@/lib/utils';

/**
 * Image actions of a form whose record has an images array (images: its
 * ref). An upload gets its image record right away; the form attaches it to
 * the record on save. Delete, publish, texts, flags and crop go to the API
 * right away.
 */
export function useImages({ images, isLoading }) {
  async function request(call, success) {
    isLoading.value = true;
    try {
      const result = await call();
      if (success) {
        notify({ type: 'success', text: success });
      }
      return result;
    }
    catch {
      // Notified by the http error handler
    }
    finally {
      isLoading.value = false;
    }
  }

  // upload: the upload endpoint's response (name, original_name, …)
  async function store(upload) {
    const image = {
      ...upload,
      caption: null,
      description: null,
      credits: null,
      coords_w: 0,
      coords_h: 0,
      coords_x: 0,
      coords_y: 0,
      preview: 0,
      publish: 1,
      cover: 0,
      worklist: 0,
      // The column defaults to 1: images open in the lightbox unless switched off
      lightbox: 1,
    };
    const response = await request(() => http.post('/api/image', image), 'Bild gespeichert!');
    if (response) {
      images.value.push({ ...image, id: response.data.imageId });
    }
  }

  async function destroy(image) {
    if (!await confirmDelete('Bild löschen?')) {
      return;
    }
    if (await request(() => http.delete(`/api/image/${image.name}`))) {
      images.value.splice(images.value.indexOf(image), 1);
    }
  }

  async function toggle(image) {
    const response = await request(() => http.get(`/api/image/state/${image.id}`));
    if (response) {
      image.publish = response.data;
    }
  }

  // Texts and the lightbox flag
  function update(image) {
    const { caption, description, credits, lightbox } = image;
    return request(() => http.put(`/api/image/${image.id}`, { caption, description, credits, lightbox }), 'Änderungen gespeichert!');
  }

  // Cover and worklist: one image per record; setting it on one clears it
  // on the others (the API does the same)
  async function toggleFlag(image, key) {
    const response = await request(() => http.get(`/api/image/${key}/state/${image.id}`));
    if (response) {
      image[key] = response.data;
      if (response.data == 1) {
        images.value.filter(other => other !== image).forEach(other => other[key] = 0);
      }
    }
  }

  // The coords are already set on the image
  function saveCoords(image) {
    const { coords_w, coords_h, coords_x, coords_y } = image;
    return request(() => http.put(`/api/image/coords/${image.id}`, { coords_w, coords_h, coords_x, coords_y }), 'Änderungen gespeichert!');
  }

  return { store, destroy, toggle, update, toggleFlag, saveCoords };
}

/**
 * Uploader props for images (as before the port)
 */
export const imageUpload = {
  url: '/api/image/upload',
  restrictions: 'jpg, png | max. 16 MB',
  acceptedFiles: '.png,.jpg,.jpeg',
  maxFiles: 99,
  maxFilesize: 32,
};
