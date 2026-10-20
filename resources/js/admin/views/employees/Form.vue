<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.firstname }]">
          <label>Vorname</label>
          <input type="text" v-model="record.firstname" @focus="clearError('firstname')">
          <LabelRequired />
        </div>
        <div :class="['form-row', { 'has-error': errors.name }]">
          <label>Name</label>
          <input type="text" v-model="record.name" @focus="clearError('name')">
          <LabelRequired />
        </div>
        <div class="form-row">
          <label>Titel</label>
          <input type="text" v-model="record.title">
        </div>
        <div class="form-row">
          <label>E-Mail</label>
          <input type="text" v-model="record.email">
        </div>
        <div class="form-row">
          <label>Telefon</label>
          <input type="text" v-model="record.phone">
        </div>
        <div :class="['form-row', { 'has-error': errors.team_id }]">
          <label>Team</label>
          <div class="select-wrapper">
            <select v-model="record.team_id" @focus="clearError('team_id')">
              <option :value="null">Bitte wählen...</option>
              <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
            </select>
          </div>
          <LabelRequired />
        </div>
        <div :class="['form-row', { 'has-error': errors.employee_category_id }]">
          <label>Position</label>
          <div class="select-wrapper">
            <select v-model="record.employee_category_id" @focus="clearError('employee_category_id')">
              <option :value="null">Bitte wählen...</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
            </select>
          </div>
          <LabelRequired />
        </div>
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'employees' }" submit />
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
import http from '@/lib/http';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const tabs = [
  { key: 'data', label: 'Daten' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const teams = ref([]);
const categories = ref([]);

// The edit response brings teams and categories along; a new employee
// loads them separately
const options = [
  async () => teams.value = (await http.get('/api/teams')).data.data,
  async () => categories.value = (await http.get('/api/employee/categories')).data.data,
];

const { record, errors, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'employee',
  key: 'employee',
  model: () => ({
    firstname: null,
    name: null,
    title: null,
    email: null,
    phone: null,
    team_id: null,
    employee_category_id: null,
    publish: 1,
  }),
  redirect: { name: 'employees' },
  titles: { create: 'Mitarbeiter hinzufügen', edit: 'Mitarbeiter bearbeiten' },
  load: props.type === 'create' ? options : [],
  loaded: data => {
    teams.value = data.teams;
    categories.value = data.employeeCategories;
  },
});
</script>
