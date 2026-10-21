<template>
  <!-- No create button (as before): the site knows exactly these teams -->
  <ResourceIndex
    title="Teams"
    :listing="listing"
    :columns="columns"
    :routes="{ edit: 'team-edit' }"
    :order="order"
    :row-label="row => row.name"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const columns = [
  { key: 'name', label: 'Name', primary: true },
];

const listing = reactive(useListing({ list: '/api/teams', resource: 'team' }));
const order = useOrder({ url: '/api/team/order', key: 'teams' });
</script>
