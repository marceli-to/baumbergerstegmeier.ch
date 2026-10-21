<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'publications' })">
    <Tabs v-model="tab" :tabs="tabs" :errors="Object.keys(errors).length ? ['data'] : []">
      <Tab name="data">
        <div class="flex flex-col gap-24">
          <div class="w-160">
            <FormField v-model="record.year" name="year" label="Jahr" maxlength="4" :errors="errors" required />
          </div>
          <FormField v-model="record.title" name="title" label="Titel" type="textarea" :errors="errors" required />
          <FormField v-model="record.subtitle" name="subtitle" label="Subtitel" :errors="errors" />
          <FormField v-model="record.description" name="description" label="Beschreibung" type="textarea" :errors="errors" />
          <div class="relative">
            <FormField v-model="record.link" name="link" label="Link" :errors="errors" />
            <!-- Opens the link to check it, once it looks like a URL -->
            <a
              v-if="isUrl(record.link)"
              :href="record.link"
              target="_blank"
              class="absolute top-0 right-0 rounded text-gray-400 transition-colors hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100"
              title="Link öffnen"
            >
              <PhArrowSquareOut :size="16" weight="light" />
            </a>
          </div>
        </div>
      </Tab>
      <Tab name="images">
        <ImageField v-model:images="record.images" :label="null" />
      </Tab>
      <Tab name="files">
        <FileField v-model:files="record.files" :label="null" />
      </Tab>
    </Tabs>

    <template #sidebar>
      <FormField v-model="record.publish" name="publish" label="Publiziert" type="checkbox" />
    </template>
  </ResourceForm>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { PhArrowSquareOut } from '@phosphor-icons/vue';
import ResourceForm from '@/components/resource/ResourceForm.vue';
import FormField from '@/components/ui/form/FormField.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import Tab from '@/components/ui/tabs/Tab.vue';
import ImageField from '@/components/media/ImageField.vue';
import FileField from '@/components/media/FileField.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const router = useRouter();

const tabs = [
  { key: 'data', label: 'Inhalt' },
  { key: 'images', label: 'Bilder' },
  { key: 'files', label: 'Dateien' },
];
const tab = ref('data');

function isUrl(value) {
  return /^https?:\/\/[^\s.]+\.[^\s]+$/i.test(value ?? '');
}

const { record, errors, isEdit, isLoading, isFetched, title, submit } = useResourceForm({
  type: props.type,
  endpoint: 'publication',
  key: 'publication',
  model: () => ({
    year: null,
    title: null,
    subtitle: null,
    description: null,
    link: null,
    publish: 1,
    images: [],
    files: [],
  }),
  redirect: { name: 'publications' },
  titles: { create: 'Neue Publikation', edit: 'Publikation bearbeiten' },
});
</script>
