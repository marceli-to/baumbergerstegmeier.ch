<template>
  <ResourceIndex
    title="Projekt-Status"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'state-create', edit: 'state-edit' }"
    create-label="Hinzufügen"
    :order="order"
    :row-label="row => row.description"
  >
    <template #cell-flags="{ row }">
      <div class="flex gap-6">
        <Badge v-if="Number(row.show_in_menu) === 1">Im Menü</Badge>
        <Badge v-if="Number(row.has_landing) === 1">Übersichtsseite</Badge>
      </div>
    </template>
  </ResourceIndex>
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const columns = [
  { key: 'description', label: 'Bezeichnung', primary: true },
  { key: 'flags', label: 'Optionen', class: 'w-240' },
];

// Toggle: GET /api/project/state/state/{id} (resource + '/state/')
const listing = reactive(useListing({ list: '/api/project/states', resource: 'project/state' }));
const order = useOrder({ url: '/api/project/state/order', key: 'states' });
</script>
