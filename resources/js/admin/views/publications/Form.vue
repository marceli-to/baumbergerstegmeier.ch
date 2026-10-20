<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.year }]">
          <label>Jahr</label>
          <input type="text" v-model="record.year" maxlength="4" @focus="clearError('year')">
          <LabelRequired />
        </div>
        <div :class="['form-row', { 'has-error': errors.title }]">
          <label>Titel</label>
          <textarea v-model="record.title" @focus="clearError('title')"></textarea>
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Subtitel</label>
          <input type="text" v-model="record.subtitle">
        </div>
        <div class="form-row">
          <label>Beschreibung</label>
          <textarea v-model="record.description"></textarea>
        </div>
        <div class="form-row">
          <div class="flex justify-between">
            <label>Link</label>
            <!-- Opens the link to check it, once it looks like a URL -->
            <a :href="record.link" target="_blank" class="feather-icon" title="Link öffnen" v-if="isUrl(record.link)">
              <PhLink :size="18" weight="light" />
            </a>
          </div>
          <input type="text" v-model="record.link">
        </div>
      </div>
      <div v-show="tab === 'images'">
        <ImageManager v-model:images="record.images" />
      </div>
      <div v-show="tab === 'files'">
        <FileManager v-model:files="record.files" />
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'publications' }" submit />
    </form>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { PhLink } from '@phosphor-icons/vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Tabs from '@/components/ui/Tabs.vue';
import LabelRequired from '@/components/ui/LabelRequired.vue';
import Toggle from '@/components/ui/Toggle.vue';
import ImageManager from '@/components/images/ImageManager.vue';
import FileManager from '@/components/files/FileManager.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Inhalt' },
  { key: 'images', label: 'Bilder' },
  { key: 'files', label: 'Dateien' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

function isUrl(value) {
  return /^https?:\/\/[^\s.]+\.[^\s]+$/i.test(value ?? '');
}

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
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
  titles: { create: 'Publikation hinzufügen', edit: 'Publikation bearbeiten' },
});
</script>
