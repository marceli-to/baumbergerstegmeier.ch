<template>
  <ResourceIndex
    title="Mitarbeitende"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'employee-create', edit: 'employee-edit' }"
    create-label="Neue Person"
    :order="order"
    :group-by="row => ({ key: row.group, label: labels[row.group] })"
    :draggable-group="group => group.key === 'leadership'"
    :row-label="row => `${row.firstname} ${row.name}`"
    :searchable="row => [row.firstname, row.name, row.title]"
    search-placeholder="Name oder Titel"
  >
    <template #row-actions="{ row }">
      <button
        v-if="hasCv(row)"
        type="button"
        class="cursor-pointer rounded text-gray-400 transition-colors hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100"
        title="Lebenslauf"
        @click="router.push({ name: 'cvs', params: { id: row.id } })"
      >
        <PhListBullets :size="16" weight="light" />
      </button>
    </template>
  </ResourceIndex>
</template>
<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { PhListBullets } from '@phosphor-icons/vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const router = useRouter();

// The API groups by employee category. Only the management is ordered by
// hand; the other groups come sorted by name.
const labels = {
  leadership: 'Geschäftsleitung',
  employees: 'Mitarbeitende',
  former_employees: 'Ehemalige Mitarbeitende',
};

// * marks the second team
const columns = [
  { key: 'name', label: 'Name', value: row => `${row.firstname} ${row.name}${Number(row.team_id) === 2 ? '*' : ''}`, primary: true },
  { key: 'title', label: 'Titel' },
];

// The site shows a CV for the management of the first team (BSA) only
function hasCv(row) {
  return row.group === 'leadership' && Number(row.team_id) === 1;
}

const listing = reactive(useListing({
  list: '/api/employees',
  resource: 'employee',
  transform: data => Object.keys(labels).flatMap(group => (data[group] ?? []).map(row => ({ ...row, group }))),
}));
const order = useOrder({ url: '/api/employee/order', key: 'employees' });
</script>
