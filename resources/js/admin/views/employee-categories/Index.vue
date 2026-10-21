<template>
  <!-- No create button and no ordering (as before): the employee list
       groups by exactly these three categories -->
  <ResourceIndex
    title="Kategorien"
    :listing="listing"
    :columns="columns"
    :routes="{ edit: 'employee-category-edit' }"
    :row-label="row => row.name"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';

const columns = [
  { key: 'name', label: 'Name', primary: true },
];

const listing = reactive(useListing({ list: '/api/employee/categories', resource: 'employee/category' }));
</script>
