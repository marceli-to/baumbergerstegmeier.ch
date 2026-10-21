<template>
  <!-- A teaser's frame: card, hover actions, dimmed and marked when hidden -->
  <div class="group relative overflow-hidden rounded-md border border-gray-200 bg-white dark:border-warm-700 dark:bg-warm-800">
    <div :class="hidden ? 'opacity-40' : ''">
      <slot />
    </div>

    <span
      v-if="hidden"
      class="absolute top-8 right-8 flex size-24 items-center justify-center rounded-full bg-white/90 text-gray-700 shadow-sm transition-opacity duration-150 group-focus-within:opacity-0 group-hover:opacity-0 dark:bg-warm-900/90 dark:text-warm-200"
      title="Verborgen"
    >
      <PhEyeSlash :size="14" weight="regular" />
      <span class="sr-only">Verborgen</span>
    </span>

    <TeaserActions :item="item" @toggle="emit('toggle', item)" @destroy="emit('destroy', item)" />
  </div>
</template>
<script setup>
import { computed } from 'vue';
import { PhEyeSlash } from '@phosphor-icons/vue';
import TeaserActions from '@/components/teasers/TeaserActions.vue';

const props = defineProps({
  item: { type: Object, required: true },
});

const emit = defineEmits(['toggle', 'destroy']);

const hidden = computed(() => Number(props.item.publish) === 0);
</script>
