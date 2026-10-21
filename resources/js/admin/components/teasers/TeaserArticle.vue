<template>
  <TeaserCard :item="item" @toggle="emit('toggle', item)" @destroy="emit('destroy', item)">
    <article v-if="item.article" class="flex flex-col gap-8 p-12">
      <div class="flex items-center gap-6 text-xs text-gray-400 dark:text-warm-500">
        <PhTextAlignLeft :size="14" weight="light" />
        <span class="truncate">{{ item.article.category || 'Artikel' }}</span>
      </div>
      <h3 class="text-sm font-medium text-gray-900 dark:text-warm-100">{{ item.article.title }}</h3>
      <img v-if="item.article.published_image" :src="imageUrl(item.article.published_image)" alt="" loading="lazy" class="block h-auto w-full rounded-sm">
      <p v-if="item.article.text" class="text-xs/relaxed text-gray-500 dark:text-warm-400">{{ truncate(item.article.text, 220) }}</p>
    </article>
    <div v-else class="flex aspect-[3/2] flex-col items-center justify-center gap-8 bg-gray-50 text-gray-400 dark:bg-warm-900 dark:text-warm-500">
      <PhTextAlignLeft :size="24" weight="light" />
      <span class="text-xs">Der Artikel wurde gelöscht.</span>
    </div>
  </TeaserCard>
</template>
<script setup>
import { PhTextAlignLeft } from '@phosphor-icons/vue';
import TeaserCard from '@/components/teasers/TeaserCard.vue';
import { imageUrl } from '@/lib/images';
import { truncate } from '@/lib/utils';

// A teaser showing a homepage article. Deleting an article leaves its
// teasers; they can be removed here.
defineProps({
  item: { type: Object, required: true },
});

const emit = defineEmits(['toggle', 'destroy']);
</script>
