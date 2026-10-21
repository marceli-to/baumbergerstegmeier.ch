<template>
  <div>
    <FormLabel v-if="label" :error="error ? 'Mindestens ein Bild hochladen' : null">{{ label }}</FormLabel>
    <div class="mt-8 flex flex-col gap-16">
      <MediaUploader v-bind="imageUpload" :compact="images.length > 0" @uploaded="store" />
      <draggable
        v-if="images.length"
        v-model="images"
        :item-key="image => image.id ?? image.name"
        :class="['grid gap-20', wide ? 'grid-cols-3 lg:grid-cols-4' : 'grid-cols-2 sm:grid-cols-3']"
        ghost-class="opacity-30"
        :animation="150"
        @end="order(images)"
      >
        <template #item="{ element: image }">
          <div class="cursor-grab active:cursor-grabbing">
            <MediaCard
              :src="imageUrl(image, 'thumbnail')"
              :href="imageUrl(image)"
              :label="image.original_name || displayName(image.name)"
              :meta="image.caption ?? ''"
              :hidden="Number(image.publish) === 0"
              @toggle="toggle(image)"
              @edit="openEdit(image)"
              @crop="cropping = image"
              @delete="destroy(image)"
            >
              <template v-if="exclusive.some(flag => Number(image[flag.key]) === 1)" #badges>
                <Badge v-for="flag in exclusive.filter(flag => Number(image[flag.key]) === 1)" :key="flag.key">{{ flag.label }}</Badge>
              </template>
            </MediaCard>
          </div>
        </template>
      </draggable>
    </div>

    <MediaEdit :item="editing" @close="editing = null" @save="save">
      <template #default="{ item }">
        <FormGroup>
          <FormLabel for="image_caption">Legende</FormLabel>
          <FormInput id="image_caption" v-model="item.caption" />
        </FormGroup>
        <FormGroup>
          <FormLabel for="image_description">Alt</FormLabel>
          <FormInput id="image_description" v-model="item.description" />
        </FormGroup>
        <FormGroup>
          <FormLabel for="image_credits">Credits</FormLabel>
          <FormInput id="image_credits" v-model="item.credits" />
        </FormGroup>
        <div v-if="flags.length" class="flex flex-col gap-12">
          <FormCheckbox v-for="flag in flags" :id="`image_${flag.key}`" :key="flag.key" v-model="item[flag.key]">{{ flag.label }}</FormCheckbox>
          <span v-if="exclusive.length" class="text-xs text-gray-400 dark:text-warm-500">{{ exclusive.map(flag => flag.label).join(' und ') }}: je nur ein Bild pro {{ owner }}.</span>
        </div>
      </template>
    </MediaEdit>
    <MediaCrop :image="cropping" :formats="formats" @close="cropping = null" @save="saveCrop" />
  </div>
</template>
<script setup>
import { ref } from 'vue';
import draggable from 'vuedraggable';
import Badge from '@/components/ui/badge/Badge.vue';
import FormGroup from '@/components/ui/form/FormGroup.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormInput from '@/components/ui/form/FormInput.vue';
import FormCheckbox from '@/components/ui/form/FormCheckbox.vue';
import MediaUploader from '@/components/media/MediaUploader.vue';
import MediaCard from '@/components/media/MediaCard.vue';
import MediaEdit from '@/components/media/MediaEdit.vue';
import MediaCrop from '@/components/media/MediaCrop.vue';
import { useImages, imageUpload } from '@/composables/useImages';
import { useOrder } from '@/composables/useOrder';
import { imageUrl } from '@/lib/images';
import { displayName } from '@/lib/utils';

/**
 * The images of a form: upload, publish, texts and flags (edit drawer),
 * crop, order (drag), delete. Every change is saved right away; new images
 * are attached to the record when the form is saved.
 */
const props = defineProps({
  label: { type: String, default: 'Bilder' },
  // crop formats { label, w, h }; the first is the default
  ratios: { type: Array, default: () => [{ label: 'Quer', w: 3, h: 2 }, { label: 'Hoch', w: 2, h: 3 }] },
  // offer a crop without a fixed ratio
  freeCrop: { type: Boolean, default: false },
  // checkboxes in the edit drawer, { key, label }: 'cover' and 'worklist'
  // are held by one image only, others ('lightbox') are saved with the texts
  flags: { type: Array, default: () => [] },
  // what the exclusive flags are unique within ("ein Bild pro Projekt")
  owner: { type: String, default: 'Projekt' },
  // no form sidebar next to it: more columns
  wide: { type: Boolean, default: true },
  error: { type: Boolean, default: false },
});

const images = defineModel('images', { type: Array, required: true });

const isLoading = ref(false);
const { store, destroy, toggle, update, toggleFlag, saveCoords } = useImages({ images, isLoading });
const order = useOrder({ url: '/api/images/order', key: 'images', delay: 1000 });

const exclusiveKeys = ['cover', 'worklist'];
const exclusive = props.flags.filter(flag => exclusiveKeys.includes(flag.key));

const formats = [
  ...props.ratios.map(({ label, w, h }) => ({ label: `${label} ${w} x ${h}`, ratio: w / h })),
  ...(props.freeCrop ? [{ label: 'Frei', ratio: null }] : []),
];

// The drawer edits a copy; Speichern writes it back and saves it
const editing = ref(null);
let original = null;

function openEdit(image) {
  original = image;
  editing.value = { ...image };
}

async function save(copy) {
  const image = original;
  editing.value = null;
  // Exclusive flags have their own endpoint, which clears them on the others
  for (const flag of exclusive) {
    if (Number(copy[flag.key] ?? 0) !== Number(image[flag.key] ?? 0)) {
      await toggleFlag(image, flag.key);
    }
  }
  Object.assign(image, { caption: copy.caption, description: copy.description, credits: copy.credits });
  props.flags.filter(flag => !exclusiveKeys.includes(flag.key)).forEach(flag => image[flag.key] = copy[flag.key]);
  await update(image);
}

const cropping = ref(null);

function saveCrop(image) {
  cropping.value = null;
  saveCoords(image);
}
</script>
