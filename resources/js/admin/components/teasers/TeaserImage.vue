<template>
  <div :class="['teaser', { 'is-hidden': item.publish == 0 }]">
    <TeaserActions :item="item" @toggle="emit('toggle', item)" @destroy="emit('destroy', item)" />
    <figure v-if="item.image">
      <img :src="imageUrl(item.image)" height="300" width="300" loading="lazy" alt="">
      <figcaption v-if="item.image.caption">{{ item.image.caption }}</figcaption>
    </figure>
    <p class="teaser__missing" v-else>Das Bild wurde gelöscht.</p>
  </div>
</template>
<script setup>
import TeaserActions from '@/components/teasers/TeaserActions.vue';
import { imageUrl } from '@/lib/images';

// A teaser showing a project image (as cropped). Deleting an image leaves
// its teasers; they can be removed here.
defineProps({
  item: { type: Object, required: true },
});

const emit = defineEmits(['toggle', 'destroy']);
</script>
