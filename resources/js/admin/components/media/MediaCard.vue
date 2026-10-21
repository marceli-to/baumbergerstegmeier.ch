<template>
  <div class="group relative overflow-hidden rounded-md border border-gray-200 bg-white dark:border-warm-700 dark:bg-warm-800">
    <div class="relative">
      <div class="flex aspect-square items-center justify-center overflow-hidden" :class="hidden ? 'opacity-40' : ''">
        <div v-if="file" class="flex flex-col items-center gap-8 text-gray-400 dark:text-warm-500">
          <component :is="extension.toLowerCase() === 'pdf' ? PhFilePdf : PhFile" :size="32" weight="light" />
          <span class="text-xs uppercase">{{ extension }}</span>
        </div>
        <img v-else :src="src" alt="" loading="lazy" class="h-full w-full object-cover">
      </div>
      <!-- Flags (Werkliste, Cover) on the image, bottom right -->
      <div v-if="$slots.badges" class="absolute right-8 bottom-8 flex flex-wrap justify-end gap-4 [&>span]:bg-white/90 [&>span]:shadow-sm dark:[&>span]:bg-warm-900/90">
        <slot name="badges" />
      </div>
    </div>

    <div class="border-t border-gray-100 px-10 py-8 dark:border-warm-700">
      <div class="truncate text-xs text-gray-900 dark:text-warm-100" :title="label">{{ label }}</div>
      <div v-if="meta" class="mt-2 truncate text-xs text-gray-400 dark:text-warm-500" :title="meta">{{ meta }}</div>
    </div>

    <!-- Hidden: an eye in the corner, out of the way of the action bar -->
    <span
      v-if="hidden"
      class="absolute top-8 right-8 flex size-24 items-center justify-center rounded-full bg-white/90 text-gray-700 shadow-sm transition-opacity duration-150 group-focus-within:opacity-0 group-hover:opacity-0 dark:bg-warm-900/90 dark:text-warm-200"
      title="Verborgen"
    >
      <PhEyeSlash :size="14" weight="regular" />
      <span class="sr-only">Verborgen</span>
    </span>

    <!-- Actions: a bar sliding down from the top on hover -->
    <div class="absolute inset-x-8 top-8 -translate-y-[calc(100%+8px)] overflow-hidden rounded-md transition-transform duration-150 group-focus-within:translate-y-0 group-hover:translate-y-0">
      <div class="flex items-center justify-around bg-black/80 px-10 py-8">
        <a :href="href" target="_blank" class="text-white/70 hover:text-white" title="In neuem Tab öffnen">
          <PhArrowSquareOut :size="15" weight="light" />
        </a>
        <button type="button" class="cursor-pointer text-white/70 hover:text-white" :title="hidden ? 'Veröffentlichen' : 'Verbergen'" @click.stop="emit('toggle')">
          <PhEyeSlash v-if="hidden" :size="15" weight="light" />
          <PhEye v-else :size="15" weight="light" />
        </button>
        <button type="button" class="cursor-pointer text-white/70 hover:text-white" title="Bearbeiten" @click.stop="emit('edit')">
          <PhPencil :size="15" weight="light" />
        </button>
        <button v-if="!file" type="button" class="cursor-pointer text-white/70 hover:text-white" title="Zuschneiden" @click.stop="emit('crop')">
          <PhCrop :size="15" weight="light" />
        </button>
        <button
          type="button"
          class="cursor-pointer text-white/70 hover:text-white disabled:cursor-not-allowed disabled:opacity-30"
          :disabled="locked"
          :title="locked ? lockedTitle : 'Löschen'"
          @click.stop="emit('delete')"
        >
          <PhTrash :size="15" weight="light" />
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { computed } from 'vue';
import { PhTrash, PhPencil, PhCrop, PhArrowSquareOut, PhFilePdf, PhFile, PhEye, PhEyeSlash } from '@phosphor-icons/vue';

// An image or a file: preview, name and a second line, the actions in a bar
// that slides in on hover
const props = defineProps({
  src: { type: String, default: null },
  // opens in a new tab
  href: { type: String, required: true },
  label: { type: String, default: '' },
  meta: { type: String, default: '' },
  hidden: { type: Boolean, default: false },
  // can't be deleted
  locked: { type: Boolean, default: false },
  lockedTitle: { type: String, default: 'Kann nicht gelöscht werden' },
  // a document: an icon instead of the image, no crop
  file: { type: Boolean, default: false },
});

const emit = defineEmits(['edit', 'crop', 'toggle', 'delete']);

const extension = computed(() => props.label.split('.').pop());
</script>
