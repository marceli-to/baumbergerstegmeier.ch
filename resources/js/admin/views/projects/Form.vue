<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="tabErrors" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.title }]">
          <label>Titel</label>
          <input type="text" v-model="record.title" @focus="clearError('title')">
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Titel Menü</label>
          <input type="text" v-model="record.title_menu">
          <div class="is-info">Optional</div>
        </div>
        <div class="form-row">
          <label>Titel Werkliste</label>
          <input type="text" v-model="record.title_worklist">
          <div class="is-info">Optional</div>
        </div>
        <div class="form-row">
          <label>Ort</label>
          <input type="text" v-model="record.location">
        </div>
        <div :class="['form-row', { 'has-error': errors.year }]">
          <label>Jahr</label>
          <input type="text" v-model="record.year" maxlength="4" @focus="clearError('year')">
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Text</label>
          <Editor v-model="record.text" />
        </div>
        <div class="form-row">
          <label>Info</label>
          <Editor v-model="record.info" />
        </div>
      </div>
      <div v-show="tab === 'worklist'">
        <div :class="['form-row', { 'has-error': errors.type }]">
          <label>Typ</label>
          <input type="text" v-model="record.type" @focus="clearError('type')">
          <LabelRequired />
        </div>
      </div>
      <div v-show="tab === 'images'">
        <ImageManager
          v-model:images="record.images"
          :flags="[{ key: 'worklist', label: 'Werkliste' }, { key: 'cover', label: 'Cover' }, { key: 'lightbox', label: 'Lightbox' }]"
        />
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <label>Beschreibung (SEO)</label>
          <textarea v-model="record.description"></textarea>
        </div>
        <div :class="['form-row', { 'has-error': errors.category_ids }]">
          <label>Kategorie</label>
          <div class="checkbox-list">
            <label v-for="category in categories" :key="category.id" class="checkbox">
              <input type="checkbox" :value="category.id" v-model="record.category_ids" @change="clearError('category_ids')">
              <span>{{ category.description }}</span>
            </label>
          </div>
          <LabelRequired />
        </div>
        <!-- A project can have several states -->
        <div :class="['form-row', { 'has-error': errors.state_ids }]">
          <label>Status</label>
          <div class="checkbox-list">
            <label v-for="state in states" :key="state.id" class="checkbox">
              <input type="checkbox" :value="state.id" v-model="record.state_ids" @change="clearError('state_ids')">
              <span>{{ state.description }}</span>
            </label>
          </div>
          <LabelRequired />
        </div>
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
        <div class="form-row">
          <Toggle label="Detailseite?" name="feature" v-model="record.feature" />
        </div>
        <div class="form-row">
          <Toggle label="Startseite?" name="landing" v-model="record.landing" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'projects' }" submit />
    </form>
  </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Tabs from '@/components/ui/Tabs.vue';
import LabelRequired from '@/components/ui/LabelRequired.vue';
import Toggle from '@/components/ui/Toggle.vue';
import Editor from '@/components/ui/editor/Editor.vue';
import ImageManager from '@/components/images/ImageManager.vue';
import { useResourceForm } from '@/composables/useResourceForm';
import http from '@/lib/http';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Projekt' },
  { key: 'worklist', label: 'Werkliste' },
  { key: 'images', label: 'Bilder' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

// Which tab holds which validated field
const fieldTabs = { title: 'data', year: 'data', type: 'worklist', category_ids: 'settings', state_ids: 'settings' };
const tabErrors = computed(() => Object.keys(errors.value).map(field => fieldTabs[field] ?? 'data'));

const categories = ref([]);
const states = ref([]);

// The edit response brings categories and states along
const load = props.type === 'create'
  ? [
    () => http.get('/api/project/categories').then(({ data }) => categories.value = data.data),
    () => http.get('/api/project/states').then(({ data }) => states.value = data.data),
  ]
  : [];

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'project',
  key: 'project',
  model: () => ({
    title: null,
    title_menu: null,
    title_worklist: null,
    description: null,
    text: null,
    info: null,
    year: null,
    location: null,
    type: null,
    category_ids: [],
    state_ids: [],
    publish: 1,
    feature: 0,
    landing: 0,
    images: [],
  }),
  redirect: { name: 'projects' },
  titles: { create: 'Projekt hinzufügen', edit: 'Projekt bearbeiten' },
  load,
  loaded: data => {
    categories.value = data.categories;
    states.value = data.states;
  },
});
</script>
