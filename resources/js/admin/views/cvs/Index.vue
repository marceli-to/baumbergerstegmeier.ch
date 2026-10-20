<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="isFetched">
      <ContentHeader>
        <h1>Lebenslauf</h1>
        <AddButton :to="{ name: 'cv-create', params: { employeeId: route.params.id } }" />
      </ContentHeader>
      <section class="team-group" v-if="entries.length">
        <SortableList v-model="entries" class="listing" @end="saveOrder">
          <div v-for="item in entries" :key="item.id" :class="['listing__item is-draggable', { 'is-disabled': item.publish == 0 }]">
            <div class="listing__item-body">{{ item.periode }} – {{ item.description }}</div>
            <ListActions :record="item" edit-route="cv-edit" @toggle="toggle" @destroy="destroy" />
          </div>
        </SortableList>
      </section>
      <section class="team-group" v-for="group in groups" :key="group.category.id">
        <h2>{{ group.category.description }}</h2>
        <SortableList v-model="group.items" class="listing" @end="saveOrder">
          <div v-for="item in group.items" :key="item.id" :class="['listing__item is-draggable', { 'is-disabled': item.publish == 0 }]">
            <div class="listing__item-body">{{ item.periode }} – {{ item.description }}</div>
            <ListActions :record="item" edit-route="cv-edit" @toggle="toggle" @destroy="destroy" />
          </div>
        </SortableList>
      </section>
      <p class="no-records" v-if="!entries.length && !groups.length">Es sind noch keine Daten vorhanden...</p>
      <ContentFooter :back="{ name: 'employees' }" />
    </template>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import AddButton from '@/components/ui/AddButton.vue';
import ListActions from '@/components/ui/ListActions.vue';
import SortableList from '@/components/ui/SortableList.vue';
import http from '@/lib/http';
import { notify } from '@/lib/notify';
import { confirmDelete } from '@/lib/utils';
import { useOrder } from '@/composables/useOrder';

const route = useRoute();

// Entries without a category, then one list per category
// (the API: { cv: [...], cvCategorized: { categoryId: [...] } })
const entries = ref([]);
const groups = ref([]);
const isLoading = ref(false);
const isFetched = ref(false);

// All lists go in one request: an entry's order only counts within its
// category, so numbering them through keeps each list's order
const order = useOrder({ url: '/api/cv/order', key: 'cvs' });
function saveOrder() {
  order(all());
}

function all() {
  return [...entries.value, ...groups.value.flatMap(group => group.items)];
}

async function fetch() {
  isLoading.value = true;
  try {
    const { data } = await http.get(`/api/cv/${route.params.id}`);
    entries.value = data.cv;
    groups.value = Object.values(data.cvCategorized).map(items => ({ category: items[0].category, items }));
    isFetched.value = true;
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}

async function toggle(id) {
  isLoading.value = true;
  try {
    const { data } = await http.get(`/api/cv/state/${id}`);
    all().find(item => item.id === id).publish = data;
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
    await http.delete(`/api/cv/${id}`);
    notify({ type: 'success', text: 'Eintrag gelöscht' });
  }
  catch {
    // Notified by the http error handler
  }
  await fetch();
}

fetch();
</script>
