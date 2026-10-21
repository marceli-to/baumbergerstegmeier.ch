<template>
  <TeaserCard :item="item" @toggle="emit('toggle', item)" @destroy="emit('destroy', item)">
    <img v-if="item.image" :src="imageUrl(item.image)" alt="" loading="lazy" class="block h-auto w-full bg-gray-100 dark:bg-warm-900">
    <div v-else class="flex aspect-[3/2] flex-col items-center justify-center gap-8 bg-gray-50 text-gray-400 dark:bg-warm-900 dark:text-warm-500">
      <PhImageBroken :size="24" weight="light" />
      <span class="text-xs">Das Bild wurde gelöscht.</span>
    </div>
    <div v-if="project || item.image?.caption" class="border-t border-gray-100 px-10 py-8 dark:border-warm-700">
      <div v-if="project" class="truncate text-xs text-gray-900 dark:text-warm-100" :title="project">{{ project }}</div>
      <div v-if="item.image?.caption" class="truncate text-xs" :class="project ? 'mt-2 text-gray-400 dark:text-warm-500' : 'text-gray-900 dark:text-warm-100'" :title="item.image.caption">{{ item.image.caption }}</div>
    </div>
  </TeaserCard>
</template>
<script setup>
import { PhImageBroken } from '@phosphor-icons/vue';
import TeaserCard from '@/components/teasers/TeaserCard.vue';
import { imageUrl } from '@/lib/images';

// A teaser showing a project image (as cropped). Deleting an image leaves
// its teasers; they can be removed here.
defineProps({
  item: { type: Object, required: true },
  // the project's title (landings mix several projects)
  project: { type: String, default: null },
});

const emit = defineEmits(['toggle', 'destroy']);
</script>
