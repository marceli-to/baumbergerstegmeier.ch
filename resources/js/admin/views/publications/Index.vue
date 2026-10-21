<template>
  <ResourceIndex
    title="Publikationen"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'publication-create', edit: 'publication-edit' }"
    create-label="Neue Publikation"
    :order="order"
    :row-label="row => row.title"
    :searchable="row => [row.year, row.title, row.subtitle]"
    search-placeholder="Jahr, Titel oder Subtitel"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

// Sorted by year (newest first), then by the dragged order
const columns = [
  { key: 'year', label: 'Jahr', class: 'w-80' },
  { key: 'title', label: 'Titel', limit: 90, primary: true },
];

const listing = reactive(useListing({ list: '/api/publications', resource: 'publication' }));
const order = useOrder({ url: '/api/publication/order', key: 'publications' });
</script>
