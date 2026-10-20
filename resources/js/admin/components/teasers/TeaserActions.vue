<template>
  <div class="teaser__actions">
    <a href="javascript:;" class="feather-icon" :title="item.publish == 1 ? 'Verbergen' : 'Publizieren'" @click.prevent="emit('toggle', item)">
      <PhEye v-if="item.publish == 1" :size="18" weight="light" />
      <PhEyeSlash v-else :size="18" weight="light" />
    </a>
    <a href="javascript:;" class="feather-icon" title="Entfernen" @click.prevent="emit('destroy', item)">
      <PhTrash :size="18" weight="light" />
    </a>
  </div>
</template>
<script setup>
import { PhEye, PhEyeSlash, PhTrash } from '@phosphor-icons/vue';

// Publish and remove, on top of a teaser
defineProps({
  item: { type: Object, required: true },
});

const emit = defineEmits(['toggle', 'destroy']);
</script>
