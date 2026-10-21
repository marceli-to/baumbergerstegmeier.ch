<template>
  <ResourceIndex
    title="Jobs – Inserate"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'job-article-create', edit: 'job-article-edit' }"
    create-label="Inserat hinzufügen"
    :order="order"
    :searchable="row => [row.title, truncate(row.description, 1000)]"
    search-placeholder="Titel oder Text"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { truncate } from '@/lib/utils';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const columns = [
  { key: 'title', label: 'Titel', primary: true },
];

const listing = reactive(useListing({ list: '/api/job-articles', resource: 'job-article' }));
const order = useOrder({ url: '/api/job-article/order', key: 'jobArticles' });
</script>
