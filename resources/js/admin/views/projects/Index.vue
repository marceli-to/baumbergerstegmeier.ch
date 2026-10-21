<template>
  <ResourceIndex
    title="Projekte"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'project-create', edit: 'project-edit' }"
    create-label="Hinzufügen"
    :order="order"
    :row-label="label"
    :searchable="row => [row.title, row.location, row.year]"
    search-placeholder="Titel, Ort oder Jahr"
  >
    <template #cell-feature="{ row }">
      <Badge v-if="Number(row.feature) === 1">Detailseite</Badge>
      <!-- an empty slot would show the fallback (the raw 0) -->
      <span v-else />
    </template>
    <template #row-actions="{ row }">
      <!-- Only projects with a detail page have a preview -->
      <a
        v-if="Number(row.feature) === 1"
        :href="`/projekt/vorschau/${row.id}`"
        target="_blank"
        class="rounded text-gray-400 transition-colors hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100"
        title="Vorschau"
      >
        <PhArrowSquareOut :size="16" weight="light" />
      </a>
      <router-link
        :to="{ name: 'project-teasers', params: { id: row.id } }"
        class="rounded text-gray-400 transition-colors hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100"
        title="Teaser"
      >
        <PhSquaresFour :size="16" weight="light" />
      </router-link>
    </template>
  </ResourceIndex>
</template>
<script setup>
import { reactive } from 'vue';
import { PhArrowSquareOut, PhSquaresFour } from '@phosphor-icons/vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const label = row => [row.title, row.location].filter(Boolean).join(', ');

// Sorted by year (newest first), then by the dragged order
const columns = [
  { key: 'title', label: 'Titel', value: label, primary: true },
  { key: 'year', label: 'Jahr', class: 'w-80' },
  { key: 'feature', label: 'Detailseite', class: 'w-120' },
];

// Projects toggle at /api/project/toggle/{id}, not the usual …/state/{id}
const listing = reactive(useListing({ list: '/api/projects', resource: 'project', toggle: 'toggle' }));
const order = useOrder({ url: '/api/project/order', key: 'projects' });
</script>
