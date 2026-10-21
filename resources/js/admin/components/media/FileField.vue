<template>
  <div>
    <FormLabel v-if="label">{{ label }}</FormLabel>
    <div class="mt-8 flex flex-col gap-16">
      <MediaUploader v-bind="fileUpload" label="Dateien hinzufügen" :compact="files.length > 0" @uploaded="store" />
      <draggable
        v-if="files.length"
        v-model="files"
        item-key="id"
        class="grid grid-cols-3 gap-20 lg:grid-cols-4"
        ghost-class="opacity-30"
        :animation="150"
        @end="order(files)"
      >
        <template #item="{ element: file }">
          <div class="cursor-grab active:cursor-grabbing">
            <MediaCard
              :href="`/storage/uploads/${file.name}`"
              :label="file.original_name || displayName(file.name)"
              :meta="file.caption ?? ''"
              :hidden="Number(file.publish) === 0"
              file
              @toggle="toggle(file)"
              @edit="openEdit(file)"
              @delete="destroy(file)"
            />
          </div>
        </template>
      </draggable>
    </div>

    <MediaEdit :item="editing" file @close="editing = null" @save="save">
      <template #default="{ item }">
        <FormGroup>
          <FormLabel for="file_caption">Titel</FormLabel>
          <FormInput id="file_caption" v-model="item.caption" />
        </FormGroup>
        <FormGroup>
          <FormLabel for="file_description">Beschreibung</FormLabel>
          <FormTextarea id="file_description" v-model="item.description" />
        </FormGroup>
      </template>
    </MediaEdit>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import draggable from 'vuedraggable';
import FormGroup from '@/components/ui/form/FormGroup.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormInput from '@/components/ui/form/FormInput.vue';
import FormTextarea from '@/components/ui/form/FormTextarea.vue';
import MediaUploader from '@/components/media/MediaUploader.vue';
import MediaCard from '@/components/media/MediaCard.vue';
import MediaEdit from '@/components/media/MediaEdit.vue';
import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete, displayName } from '@/lib/utils';
import { useOrder } from '@/composables/useOrder';

// Files of a form (publications): upload, publish, title, order, delete.
// Every change is saved right away; new files are attached when the form
// is saved.
defineProps({
  label: { type: String, default: 'Dateien' },
});

const files = defineModel('files', { type: Array, required: true });

const fileUpload = {
  url: '/api/file/upload',
  restrictions: 'PDF, ZIP, TXT, DOC | max. 32 MB',
  acceptedFiles: '.pdf,.zip,.txt,.doc',
  maxFiles: 99,
  maxFilesize: 32,
};

const isLoading = ref(false);
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
  if (!await confirmDelete('Datei löschen', { text: `«${file.original_name || displayName(file.name)}» wirklich löschen?` })) {
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

// The drawer edits a copy; Speichern writes it back and saves it
const editing = ref(null);
let original = null;

function openEdit(file) {
  original = file;
  editing.value = { ...file };
}

function save(copy) {
  editing.value = null;
  Object.assign(original, { caption: copy.caption, description: copy.description });
  const { caption, description } = original;
  return request(() => http.put(`/api/file/${original.id}`, { caption, description }), 'Änderungen gespeichert!');
}
</script>
