<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.description }]">
          <label>Bezeichnung</label>
          <input type="text" v-model="record.description" @focus="clearError('description')">
          <LabelRequired />
        </div>
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
        <div class="form-row">
          <Toggle label="Im Menu anzeigen?" name="show_in_menu" v-model="record.show_in_menu" />
        </div>
        <div class="form-row">
          <Toggle label="Übersichtsseite?" name="has_landing" v-model="record.has_landing" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'states' }" submit />
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
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Daten' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'project/state',
  key: 'state',
  model: () => ({
    description: null,
    publish: 1,
    show_in_menu: 1,
    has_landing: 1,
  }),
  redirect: { name: 'states' },
  titles: { create: 'Projekt-Status hinzufügen', edit: 'Projekt-Status bearbeiten' },
});
</script>
