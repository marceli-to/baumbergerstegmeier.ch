<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <div class="form-row">
      <Uploader v-bind="fileUpload" @uploaded="store" />
    </div>
    <div class="form-row upload-listing is-files" v-if="files.length">
      <SortableList v-model="files" @end="order">
        <div v-for="file in files" :key="file.id" :class="['upload-item-file is-draggable', { 'is-disabled': file.publish == 0 }]">
          <div>
            {{ file.name }}
            <template v-if="file.caption"> · {{ truncate(file.caption, 40) }}</template>
          </div>
          <div class="upload__actions">
            <div>
              <div>
                <a :href="`/storage/uploads/${file.name}`" target="_blank" class="feather-icon" title="Herunterladen">
                  <PhDownloadSimple :size="18" weight="light" />
                </a>
              </div>
              <div>
                <a href="javascript:;" class="feather-icon" :title="file.publish == 1 ? 'Verbergen' : 'Publizieren'" @click.prevent="toggle(file)">
                  <PhEye v-if="file.publish == 1" :size="18" weight="light" />
                  <PhEyeSlash v-else :size="18" weight="light" />
                </a>
              </div>
              <div>
                <a href="javascript:;" class="feather-icon" title="Bearbeiten" @click.prevent="editItem = file">
                  <PhPencilSimple :size="18" weight="light" />
                </a>
              </div>
              <div>
                <a href="javascript:;" class="feather-icon" title="Löschen" @click.prevent="destroy(file)">
                  <PhTrash :size="18" weight="light" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </SortableList>
    </div>

    <Lightbox :open="!!editItem" title="Datei bearbeiten" @close="editItem = null">
      <div v-if="editItem">
        <div class="form-row">
          <label>Titel</label>
          <input type="text" v-model="editItem.caption">
        </div>
        <div class="form-row">
          <label>Beschreibung</label>
          <textarea v-model="editItem.description"></textarea>
        </div>
      </div>
      <template #footer>
        <a href="javascript:;" class="btn-primary" @click.prevent="update(editItem); editItem = null">Speichern</a>
        <a href="javascript:;" @click.prevent="editItem = null">Abbrechen</a>
      </template>
    </Lightbox>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { PhDownloadSimple, PhEye, PhEyeSlash, PhPencilSimple, PhTrash } from '@phosphor-icons/vue';
import Lightbox from '@/components/ui/Lightbox.vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import SortableList from '@/components/ui/SortableList.vue';
import Uploader from '@/components/ui/Uploader.vue';
import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete, truncate } from '@/lib/utils';
import { useOrder } from '@/composables/useOrder';

// Files of a form (publications): upload, publish, title, order, delete.
// Every change is saved right away; new files are attached when the form
// is saved.
const files = defineModel('files', { type: Array, required: true });

const fileUpload = {
  url: '/api/file/upload',
  restrictions: 'pdf, zip, txt, doc | max. 32 MB',
  acceptedFiles: '.pdf,.zip,.txt,.doc',
  maxFiles: 99,
  maxFilesize: 32,
};

const isLoading = ref(false);
const editItem = ref(null);
const order = useOrder({ url: '/api/files/order', key: 'files', delay: 1000 });

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
  const file = {
    name: upload.name,
    original_name: upload.original_name,
    size: upload.size,
    extension: upload.extension,
    caption: null,
    description: null,
    publish: 1,
  };
  const response = await request(() => http.post('/api/file', file), 'Datei gespeichert!');
  if (response) {
    files.value.push({ ...file, id: response.data.fileId });
  }
}

async function destroy(file) {
  if (!await confirmDelete('Datei löschen?')) {
    return;
  }
  if (await request(() => http.delete(`/api/file/${file.name}`))) {
    files.value.splice(files.value.indexOf(file), 1);
  }
}

async function toggle(file) {
  const response = await request(() => http.get(`/api/file/state/${file.id}`));
  if (response) {
    file.publish = response.data;
  }
}

function update(file) {
  const { caption, description } = file;
  return request(() => http.put(`/api/file/${file.id}`, { caption, description }), 'Änderungen gespeichert!');
}
</script>
