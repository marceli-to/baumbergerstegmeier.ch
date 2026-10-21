<template>
  <!-- One record per page: adding is offered only while there is none -->
  <ResourceIndex
    title="Jobs – Inhalte"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'job-create', edit: 'job-edit' }"
    :create-label="listing.isFetched && !listing.items.length ? 'Hinzufügen' : null"
    :row-label="() => 'Job-Seite'"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { truncate } from '@/lib/utils';
import { useListing } from '@/composables/useListing';

const columns = [
  { key: 'text', label: 'Job-Seite', value: row => truncate(row.text, 90) || 'Job-Seite', primary: true },
];

const listing = reactive(useListing({ list: '/api/job', resource: 'job' }));
</script>
