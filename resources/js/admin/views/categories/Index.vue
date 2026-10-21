<template>
  <ResourceIndex
    title="Projekt-Kategorien"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'category-create', edit: 'category-edit' }"
    create-label="Projekt-Kategorie hinzufügen"
    :order="order"
    :row-label="row => row.description"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const columns = [
  { key: 'description', label: 'Bezeichnung', primary: true },
];

// Toggle: GET /api/project/category/state/{id}
const listing = reactive(useListing({ list: '/api/project/categories', resource: 'project/category' }));
const order = useOrder({ url: '/api/project/category/order', key: 'categories' });
</script>
