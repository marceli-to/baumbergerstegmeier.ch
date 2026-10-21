<template>
  <!-- Publish and remove: a small bar top right, sliding down on hover -->
  <div class="absolute top-8 right-8 z-10 -translate-y-[calc(100%+8px)] overflow-hidden rounded-md transition-transform duration-150 group-focus-within:translate-y-0 group-hover:translate-y-0">
    <div class="flex items-center gap-14 bg-black/80 px-12 py-8">
      <button type="button" class="cursor-pointer text-white/70 hover:text-white" :title="hidden ? 'Veröffentlichen' : 'Verbergen'" @click.stop="emit('toggle', item)">
        <PhEyeSlash v-if="hidden" :size="15" weight="light" />
        <PhEye v-else :size="15" weight="light" />
      </button>
      <button type="button" class="cursor-pointer text-white/70 hover:text-white" title="Entfernen" @click.stop="emit('destroy', item)">
        <PhTrash :size="15" weight="light" />
      </button>
    </div>
  </div>
</template>
<script setup>
import { computed } from 'vue';
import { PhEye, PhEyeSlash, PhTrash } from '@phosphor-icons/vue';

// Publish and remove, on top of a teaser card (the card is the hover group)
const props = defineProps({
  item: { type: Object, required: true },
});

const emit = defineEmits(['toggle', 'destroy']);

const hidden = computed(() => Number(props.item.publish) === 0);
</script>
