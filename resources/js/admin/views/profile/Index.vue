<template>
  <!-- One record per page: adding is offered only while there is none -->
  <ResourceIndex
    title="Profil"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'profile-create', edit: 'profile-edit' }"
    :create-label="listing.isFetched && !listing.items.length ? 'Neues Profil' : null"
    :row-label="() => 'Profil'"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';

const columns = [
  { key: 'title_bsa', label: 'Titel', value: row => [row.title_bsa, row.title_bsemi].filter(Boolean).join(' · ') || 'Profil', primary: true },
];

const listing = reactive(useListing({ list: '/api/profile', resource: 'profile' }));
</script>
