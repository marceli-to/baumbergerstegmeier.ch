<template>
  <!-- One record per page: adding is offered only while there is none -->
  <ResourceIndex
    title="Kontakt"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'contact-create', edit: 'contact-edit' }"
    :create-label="listing.isFetched && !listing.items.length ? 'Neuer Kontakt' : null"
    :row-label="() => 'Kontakt'"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { truncate } from '@/lib/utils';
import { useListing } from '@/composables/useListing';

const columns = [
  { key: 'address', label: 'Adresse', value: row => truncate(row.address, 90) || 'Kontakt', primary: true },
];

const listing = reactive(useListing({ list: '/api/contact', resource: 'contact' }));
</script>
