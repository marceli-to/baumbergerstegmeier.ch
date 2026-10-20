<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="isFetched">
      <ContentHeader>
        <h1>Mitarbeiter</h1>
        <AddButton :to="{ name: 'employee-create' }" />
      </ContentHeader>
      <template v-for="group in groups" :key="group.key">
        <section class="team-group" v-if="employees[group.key]?.length">
          <h2>{{ group.label }}</h2>
          <SortableList v-model="employees[group.key]" class="listing" @end="order">
            <div
              v-for="item in employees[group.key]"
              :key="item.id"
              :class="['listing__item', { 'is-draggable': group.sortable, 'is-disabled': item.publish == 0 }]"
            >
              <div class="listing__item-body">
                {{ item.firstname }} {{ item.name }}<template v-if="item.team_id == 2">*</template>
                <span class="team-separator" v-if="item.title">&bull;</span>
                {{ item.title }}
              </div>
              <ListActions
                :record="item"
                edit-route="employee-edit"
                :list-route="hasCv(group, item) ? 'cvs' : null"
                @toggle="toggle(group.key, $event)"
                @destroy="destroy"
              />
            </div>
          </SortableList>
        </section>
      </template>
      <p class="no-records" v-if="!groups.some(group => employees[group.key]?.length)">Es sind noch keine Daten vorhanden...</p>
    </template>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import AddButton from '@/components/ui/AddButton.vue';
import ListActions from '@/components/ui/ListActions.vue';
import SortableList from '@/components/ui/SortableList.vue';
import http from '@/lib/http';
import { notify } from '@/lib/notify';
import { confirmDelete } from '@/lib/utils';
import { useOrder } from '@/composables/useOrder';

// The API groups by employee category. Only the management is ordered by
// hand; the other groups come sorted by name.
const groups = [
  { key: 'leadership', label: 'Geschäftsleitung', sortable: true },
  { key: 'employees', label: 'Mitarbeitende', sortable: false },
  { key: 'former_employees', label: 'Ehemalige Mitarbeitende', sortable: false },
];

const employees = ref({});
const isLoading = ref(false);
const isFetched = ref(false);
const order = useOrder({ url: '/api/employee/order', key: 'employees' });

// The site shows a CV for the management of the first team (BSA) only
function hasCv(group, item) {
  return group.key === 'leadership' && item.team_id == 1;
}

async function fetch() {
  isLoading.value = true;
  try {
    const { data } = await http.get('/api/employees');
    employees.value = data;
    isFetched.value = true;
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}

async function toggle(key, id) {
  isLoading.value = true;
  try {
    const { data } = await http.get(`/api/employee/state/${id}`);
    employees.value[key].find(item => item.id === id).publish = data;
    notify({ type: 'success', text: 'Status geändert' });
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}

async function destroy(id) {
  if (!await confirmDelete()) {
    return;
  }
  isLoading.value = true;
  try {
    await http.delete(`/api/employee/${id}`);
    notify({ type: 'success', text: 'Eintrag gelöscht' });
  }
  catch {
    // Notified by the http error handler
  }
  await fetch();
}

fetch();
</script>
