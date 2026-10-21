<template>
  <div>
    <PageHeader title="Übersicht" />
    <div v-if="!isFetched" class="text-sm text-gray-400 dark:text-warm-500">Laden...</div>
    <div v-else-if="!rows.length" class="text-sm text-gray-400 dark:text-warm-500">Keine Einträge vorhanden.</div>
    <DataTable v-else :columns="columns" :rows="rows" :group-by="row => ({ key: row.view, label: labels[row.view] })">
      <template #cell-description="{ row, value }">
        <router-link :to="teasers(row)" class="hover:underline">{{ value }}</router-link>
      </template>
      <template #cell-actions="{ row }">
        <div class="flex items-center justify-end gap-12">
          <router-link :to="teasers(row)" class="rounded text-gray-400 transition-colors hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100" title="Teaser">
            <PhSquaresFour :size="16" weight="light" />
          </router-link>
        </div>
      </template>
    </DataTable>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { PhSquaresFour } from '@phosphor-icons/vue';
import PageHeader from '@/components/layout/PageHeader.vue';
import DataTable from '@/components/ui/table/DataTable.vue';
import http from '@/lib/http';

// The categories (of projects without a landing state) and the states with
// a landing; each has its own landing page
const labels = { category: 'Kategorien', state: 'Status' };
const columns = [
  { key: 'description', label: 'Bezeichnung', primary: true },
  { key: 'actions', label: '', class: 'w-100', align: 'right' },
];

const isFetched = ref(false);
const rows = ref([]);

const teasers = row => ({ name: 'landing-edit', params: { id: row.id, view: row.view } });

http.get('/api/project/landing')
  .then(({ data }) => {
    rows.value = [
      ...data.categories.map(item => ({ ...item, view: 'category' })),
      ...data.states.map(item => ({ ...item, view: 'state' })),
    ];
    isFetched.value = true;
  })
  .catch(() => {});
</script>
