<template>
  <ResourceIndex
    title="Auszeichnungen"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'award-create', edit: 'award-edit' }"
    create-label="Hinzufügen"
    :order="order"
    :row-label="row => truncate(row.text, 60)"
    :searchable="row => [row.year, truncate(row.text, 1000)]"
    search-placeholder="Jahr oder Text"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { truncate } from '@/lib/utils';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

// Sorted by year (newest first), then by the dragged order
const columns = [
  { key: 'year', label: 'Jahr', class: 'w-80' },
  { key: 'text', label: 'Auszeichnung', value: row => truncate(row.text, 90), primary: true },
];

const listing = reactive(useListing({ list: '/api/awards', resource: 'award' }));
const order = useOrder({ url: '/api/award/order', key: 'awards' });
</script>
