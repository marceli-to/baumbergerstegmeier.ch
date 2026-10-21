<template>
  <ResourceIndex
    title="Lebenslauf"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'cv-create', edit: 'cv-edit' }"
    :order="(rows, all) => order(all)"
    :group-by="row => row.cv_category_id ? { key: row.cv_category_id, label: row.category?.description } : { key: 'none', label: null }"
    :row-label="row => `${row.periode} – ${truncate(row.description, 60)}`"
  >
    <template #actions>
      <FormButton variant="secondary" @click="router.push({ name: 'employees' })">Zurück</FormButton>
      <FormButton @click="router.push({ name: 'cv-create', params: { employeeId: route.params.id } })">Neuer Eintrag</FormButton>
    </template>
  </ResourceIndex>
</template>
<script setup>
import { reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import FormButton from '@/components/ui/form/FormButton.vue';
import { truncate } from '@/lib/utils';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const route = useRoute();
const router = useRouter();

const columns = [
  { key: 'periode', label: 'Zeitraum', class: 'w-160' },
  { key: 'description', label: 'Beschreibung', value: row => truncate(row.description, 90), primary: true },
];

// Entries without a category, then one group per category
// (the API: { cv: [...], cvCategorized: { categoryId: [...] } })
const listing = reactive(useListing({
  list: `/api/cv/${route.params.id}`,
  resource: 'cv',
  transform: data => [...data.cv, ...Object.values(data.cvCategorized).flat()],
}));

// All lists go in one request: an entry's order only counts within its
// category, so numbering them through keeps each list's order
const order = useOrder({ url: '/api/cv/order', key: 'cvs' });
</script>
