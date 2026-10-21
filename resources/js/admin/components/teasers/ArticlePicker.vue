<template>
  <div class="px-24 py-16">
    <div v-if="!isFetched" class="text-sm text-gray-400 dark:text-warm-500">Laden...</div>
    <ul v-else-if="articles.length" role="list" class="flex flex-col">
      <li v-for="article in articles" :key="article.id" class="border-b border-gray-900/6 last:border-0 dark:border-warm-700/40">
        <button
          type="button"
          class="flex w-full cursor-pointer items-baseline gap-12 px-4 py-12 text-left text-sm transition-colors hover:bg-gray-50 dark:hover:bg-warm-800"
          :class="Number(article.publish) === 0 ? 'opacity-40' : ''"
          @click="emit('select', { article_id: article.id })"
        >
          <span class="min-w-0 flex-1 text-gray-900 dark:text-warm-100">{{ truncate(article.title) }}</span>
          <span v-if="article.category" class="shrink-0 text-xs text-gray-400 dark:text-warm-500">{{ article.category }}</span>
        </button>
      </li>
    </ul>
    <div v-else class="text-sm text-gray-400 dark:text-warm-500">Es sind noch keine Artikel vorhanden.</div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import http from '@/lib/http';
import { truncate } from '@/lib/utils';

// Picks the article for a homepage teaser
const emit = defineEmits(['select']);

const isFetched = ref(false);
const articles = ref([]);

http.get('/api/articles')
  .then(({ data }) => {
    articles.value = data.data;
    isFetched.value = true;
  })
  .catch(() => {});
</script>
