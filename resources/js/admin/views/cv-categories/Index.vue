<template>
  <ResourceIndex
    title="CV-Kategorien"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'cv-category-create', edit: 'cv-category-edit' }"
    create-label="Hinzufügen"
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

const listing = reactive(useListing({ list: '/api/cv/categories', resource: 'cv/category' }));
const order = useOrder({ url: '/api/cv/category/order', key: 'cvCategories' });
</script>
