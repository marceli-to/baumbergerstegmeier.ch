<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.title_bsa }]">
          <label>Titel BSA</label>
          <input type="text" v-model="record.title_bsa" @focus="clearError('title_bsa')">
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Beschreibung BSA</label>
          <Editor v-model="record.text_bsa" />
        </div>
        <div :class="['form-row', { 'has-error': errors.title_bsemi }]">
          <label>Titel BS+EMI</label>
          <input type="text" v-model="record.title_bsemi" @focus="clearError('title_bsemi')">
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Beschreibung BS+EMI</label>
          <Editor v-model="record.text_bsemi" />
        </div>
      </div>
      <div v-show="tab === 'images'">
        <ImageManager v-model:images="record.images" />
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'profile' }" submit />
    </form>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Tabs from '@/components/ui/Tabs.vue';
import LabelRequired from '@/components/ui/LabelRequired.vue';
import Toggle from '@/components/ui/Toggle.vue';
import Editor from '@/components/ui/editor/Editor.vue';
import ImageManager from '@/components/images/ImageManager.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Text' },
  { key: 'images', label: 'Bilder' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'profile',
  key: 'profile',
  model: () => ({
    title_bsa: null,
    text_bsa: null,
    title_bsemi: null,
    text_bsemi: null,
    publish: 1,
    images: [],
  }),
  redirect: { name: 'profile' },
  titles: { create: 'Profil hinzufügen', edit: 'Profil bearbeiten' },
});
</script>
