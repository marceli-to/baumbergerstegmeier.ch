<template>
  <ResourceIndex
    title="Artikel"
    :listing="listing"
    :columns="columns"
    :routes="{ create: 'article-create', edit: 'article-edit' }"
    create-label="Hinzufügen"
    :searchable="row => [row.category, row.title]"
    search-placeholder="Kategorie oder Titel"
  />
</template>
<script setup>
import { reactive } from 'vue';
import ResourceIndex from '@/components/resource/ResourceIndex.vue';
import { useListing } from '@/composables/useListing';

// The homepage articles (teasers of the home)
const columns = [
  { key: 'title', label: 'Titel', primary: true },
  { key: 'category', label: 'Kategorie', class: 'w-240' },
];

const listing = reactive(useListing({ list: '/api/articles', resource: 'article' }));
</script>
