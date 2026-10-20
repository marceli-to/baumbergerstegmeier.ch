<template>
  <div :class="['teaser', { 'is-hidden': item.publish == 0 }]">
    <TeaserActions :item="item" @toggle="emit('toggle', item)" @destroy="emit('destroy', item)" />
    <article class="teaser__article" v-if="item.article">
      <h3>{{ item.article.category }}</h3>
      <h2>{{ item.article.title }}</h2>
      <figure v-if="item.article.published_image">
        <img :src="imageUrl(item.article.published_image)" height="300" width="300" loading="lazy" alt="">
      </figure>
      <div v-html="item.article.text"></div>
    </article>
    <p class="teaser__missing" v-else>Der Artikel wurde gelöscht.</p>
  </div>
</template>
<script setup>
import TeaserActions from '@/components/teasers/TeaserActions.vue';
import { imageUrl } from '@/lib/images';

// A teaser showing a homepage article. Deleting an article leaves its
// teasers; they can be removed here.
defineProps({
  item: { type: Object, required: true },
});

const emit = defineEmits(['toggle', 'destroy']);
</script>
