<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'employees' })">
    <Tabs v-model="tab" :tabs="tabs" :errors="Object.keys(errors).length ? ['data'] : []">
      <Tab name="data">
        <div class="grid grid-cols-2 gap-24">
          <FormField v-model="record.firstname" name="firstname" label="Vorname" :errors="errors" required />
          <FormField v-model="record.name" name="name" label="Name" :errors="errors" required />
          <div class="col-span-2">
            <FormField v-model="record.title" name="title" label="Titel" :errors="errors" />
          </div>
          <FormField v-model="record.email" name="email" label="E-Mail" :errors="errors" />
          <FormField v-model="record.phone" name="phone" label="Telefon" :errors="errors" />
        </div>
      </Tab>
    </Tabs>

    <template #sidebar>
      <FormField v-model="record.publish" name="publish" label="Publizieren?" type="checkbox" />
      <FormField v-model="record.team_id" name="team_id" label="Team" type="select" :options="toOptions(teams, 'name')" placeholder="Bitte wählen" :errors="errors" required />
      <FormField v-model="record.employee_category_id" name="employee_category_id" label="Position" type="select" :options="toOptions(categories, 'name')" placeholder="Bitte wählen" :errors="errors" required />
    </template>
  </ResourceForm>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import ResourceForm from '@/components/resource/ResourceForm.vue';
import FormField from '@/components/ui/form/FormField.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import Tab from '@/components/ui/tabs/Tab.vue';
import http from '@/lib/http';
import { toOptions } from '@/lib/utils';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const router = useRouter();

const tabs = [
  { key: 'data', label: 'Daten' },
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

const { record, errors, isEdit, isLoading, isFetched, title, submit } = useResourceForm({
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
