<template>
  <Teleport to="body">
    <div v-if="image" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
      <div class="mx-16 flex max-h-[90vh] w-full max-w-3xl flex-col rounded-2xl bg-white shadow-xl dark:bg-warm-900" role="dialog" aria-modal="true" aria-label="Bild zuschneiden">
        <div class="flex items-center justify-between px-24 pt-16">
          <h2 class="text-sm font-medium text-gray-900 dark:text-warm-100">Bild zuschneiden</h2>
          <button type="button" class="cursor-pointer text-gray-400 transition-colors hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100" title="Schliessen" @click="close">
            <PhX :size="16" weight="light" />
          </button>
        </div>

        <div v-if="formats.length > 1" class="flex gap-8 px-24 pt-16">
          <button
            v-for="format in formats"
            :key="format.label"
            type="button"
            class="cursor-pointer rounded-md px-12 py-6 text-xs transition-colors"
            :class="ratio === format.ratio
              ? 'bg-gray-900 text-white dark:bg-warm-100 dark:text-warm-900'
              : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-warm-800 dark:text-warm-300 dark:hover:bg-warm-700'"
            @click="ratio = format.ratio"
          >{{ format.label }}</button>
        </div>

        <div class="min-h-0 flex-1 overflow-hidden bg-white p-16 dark:bg-warm-900 [&_.vue-advanced-cropper]:!bg-gray-100 dark:[&_.vue-advanced-cropper]:!bg-warm-800">
          <div v-if="isLoading" class="flex h-[60vh] items-center justify-center text-sm text-gray-400 dark:text-warm-500">Laden...</div>
          <Cropper
            v-else
            :key="ratio"
            class="h-[60vh]"
            :src="src"
            :stencil-props="{ aspectRatio: ratio }"
            :default-position="hasCoords ? defaultPosition : undefined"
            :default-size="hasCoords ? defaultSize : undefined"
            image-restriction="fit-area"
            @change="change"
          />
        </div>

        <div class="flex items-center justify-between px-24 pb-16">
          <span class="text-xs text-gray-400 tabular-nums dark:text-warm-500">
            <template v-if="size.w">Neue Grösse: {{ size.w }} × {{ size.h }} px</template>
          </span>
          <div class="flex gap-12">
            <FormButton variant="secondary" @click="close">Abbrechen</FormButton>
            <FormButton @click="save">Speichern</FormButton>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>
<script setup>
import { ref, reactive, computed, watch, onMounted, onUnmounted } from 'vue';
import { PhX } from '@phosphor-icons/vue';
import { Cropper } from 'vue-advanced-cropper';
import FormButton from '@/components/ui/form/FormButton.vue';
import { imageUrl, preloadImage } from '@/lib/images';

// Crop an image to one of its formats; sets coords_w/h/x/y on the image and
// emits save
const props = defineProps({
  image: { type: Object, default: null },
  // [{ label, ratio }] (w / h, or null for a free crop); the first is the default
  formats: { type: Array, required: true },
});

const emit = defineEmits(['close', 'save']);

const src = ref(null);
const isLoading = ref(false);
const ratio = ref(null);
const coords = reactive({ w: 0, h: 0, x: 0, y: 0 });
const size = reactive({ w: null, h: null });

// An image without coords starts with the cropper's default (the largest
// box of the ratio, centred)
const hasCoords = computed(() => Number(props.image?.coords_w) > 0 && Number(props.image?.coords_h) > 0);

watch(() => props.image, async image => {
  size.w = size.h = null;
  if (!image) {
    return;
  }
  ratio.value = props.formats[0]?.ratio ?? null;
  isLoading.value = true;
  try {
    src.value = await preloadImage(imageUrl(image, 'original'));
  }
  finally {
    isLoading.value = false;
  }
});

function change({ coordinates }) {
  Object.assign(coords, { w: coordinates.width, h: coordinates.height, x: coordinates.left, y: coordinates.top });
  size.w = Math.floor(coordinates.width);
  size.h = Math.floor(coordinates.height);
}

function defaultPosition() {
  return { left: Number(props.image.coords_x), top: Number(props.image.coords_y) };
}

function defaultSize() {
  return { width: Number(props.image.coords_w), height: Number(props.image.coords_h) };
}

function save() {
  const image = props.image;
  Object.assign(image, { coords_w: coords.w, coords_h: coords.h, coords_x: coords.x, coords_y: coords.y });
  emit('save', image);
}

function close() {
  emit('close');
}

function onKeydown(event) {
  if (event.key === 'Escape' && props.image) {
    document.activeElement?.blur();
    close();
  }
}

onMounted(() => document.addEventListener('keydown', onKeydown));
onUnmounted(() => document.removeEventListener('keydown', onKeydown));
</script>
