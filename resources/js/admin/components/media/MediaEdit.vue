<template>
  <Drawer :open="!!item" :title="file ? 'Datei bearbeiten' : 'Bild bearbeiten'" :dismissible="false" @close="emit('close')">
    <template v-if="item">
      <div v-if="!file" class="border-b border-neutral-200 bg-neutral-50 dark:border-warm-700 dark:bg-warm-800">
        <img :src="imageUrl(item)" alt="" class="max-h-[320px] w-full object-contain">
      </div>

      <div class="space-y-2 border-b border-neutral-100 px-24 py-16 text-xs text-gray-400 dark:border-warm-800 dark:text-warm-500">
        <div class="font-medium text-gray-900 dark:text-warm-100">{{ item.original_name || displayName(item.name) }}</div>
        <div v-if="!file && item.orientation">{{ item.orientation === 'p' ? 'Hochformat' : 'Querformat' }}</div>
      </div>

      <div class="flex flex-col gap-24 px-24 py-20">
        <slot :item="item" />
      </div>
    </template>

    <template #footer>
      <div class="flex gap-12">
        <FormButton @click="emit('save', item)">Speichern</FormButton>
        <FormButton variant="secondary" @click="emit('close')">Abbrechen</FormButton>
      </div>
    </template>
  </Drawer>
</template>
<script setup>
import Drawer from '@/components/ui/drawer/Drawer.vue';
import FormButton from '@/components/ui/form/FormButton.vue';
import { imageUrl } from '@/lib/images';
import { displayName } from '@/lib/utils';

// The texts of an image or a file (the fields come in the slot); saved
// right away
defineProps({
  item: { type: Object, default: null },
  file: { type: Boolean, default: false },
});

const emit = defineEmits(['close', 'save']);
</script>
