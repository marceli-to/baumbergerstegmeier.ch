<template>
  <ResourceIndex
    title="Benutzer"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'user-create', edit: 'user-edit' }"
    create-label="Benutzer hinzufügen"
    :togglable="false"
    :row-label="row => `${row.firstname} ${row.name}`"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';

// The admin logins; one's own account can't be deleted (the API says so)
const columns = [
  { key: 'name', label: 'Name', value: row => `${row.firstname} ${row.name}`, primary: true },
  { key: 'email', label: 'E-Mail' },
];

const listing = reactive(useListing({ list: '/api/users', resource: 'user' }));
</script>
