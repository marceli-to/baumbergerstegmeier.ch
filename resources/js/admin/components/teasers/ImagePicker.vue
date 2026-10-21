<template>
  <div v-if="!isFetched" class="px-24 py-16 text-sm text-gray-400 dark:text-warm-500">Laden...</div>

  <!-- Project teasers: the project's own images -->
  <div v-else-if="type === 'project'" class="px-24 py-16">
    <div v-if="project?.images.length" class="grid grid-cols-4 gap-8">
      <ImageTile v-for="image in project.images" :key="image.id" :image="image" @click="emit('select', { image_id: image.id, project_id: project.id })" />
    </div>
    <div v-else class="text-sm text-gray-400 dark:text-warm-500">Es sind noch keine Bilder vorhanden.</div>
  </div>

  <!-- Home and landings: projects on the left, the chosen one's images on the right -->
  <div v-else-if="projects.length" class="flex h-full min-h-0">
    <nav class="flex w-240 shrink-0 flex-col border-r border-gray-200 dark:border-warm-800" aria-label="Projekte">
      <div class="p-16 pb-8">
        <FormInput v-model="search" name="picker_search" placeholder="Projekt suchen" />
      </div>
      <ul role="list" class="flex-1 overflow-y-auto px-8 pb-16">
        <li v-for="item in visibleProjects" :key="item.id">
          <button
            type="button"
            class="w-full cursor-pointer rounded-md px-8 py-6 text-left text-sm transition-colors"
            :class="item === current
              ? 'bg-gray-100 text-gray-900 dark:bg-warm-800 dark:text-warm-100'
              : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:text-warm-400 dark:hover:bg-warm-800/50 dark:hover:text-warm-100'"
            :aria-current="item === current ? 'true' : undefined"
            @click="current = item"
          >{{ label(item) }}</button>
        </li>
        <li v-if="!visibleProjects.length" class="px-8 py-6 text-sm text-gray-400 dark:text-warm-500">Keine Treffer.</li>
      </ul>
    </nav>
    <div class="min-w-0 flex-1 overflow-y-auto px-24 py-16">
      <template v-if="current">
        <h4 class="mb-16 text-sm font-medium text-gray-900 dark:text-warm-100">{{ label(current) }}</h4>
        <div v-if="current.published_images.length" class="grid grid-cols-3 gap-8">
          <ImageTile v-for="image in current.published_images" :key="image.id" :image="image" @click="emit('select', { image_id: image.id, project_id: current.id })" />
        </div>
        <div v-else class="text-sm text-gray-400 dark:text-warm-500">Es sind keine Bilder für dieses Projekt vorhanden.</div>
      </template>
    </div>
  </div>
  <div v-else class="px-24 py-16 text-sm text-gray-400 dark:text-warm-500">Es sind keine Projekte vorhanden.</div>
</template>
<script setup>
import { ref, computed, h } from 'vue';
import FormInput from '@/components/ui/form/FormInput.vue';
import http from '@/lib/http';
import { imageUrl } from '@/lib/images';
import { displayName } from '@/lib/utils';

// Picks the image of a teaser. A project picks from its own images (all of
// them, as before); the home from every project's published images, a
// landing from those of the projects in its category or state.
const props = defineProps({
  // 'home' | 'project' | 'landing'
  type: { type: String, required: true },
  projectId: { type: [String, Number], default: null },
  // { view: 'category' | 'state', id }
  landing: { type: Object, default: null },
});

// ({ image_id, project_id })
const emit = defineEmits(['select']);

// A square thumbnail button, the caption (or file name) as title
const ImageTile = (tileProps, { attrs }) => {
  const name = tileProps.image.caption || tileProps.image.original_name || displayName(tileProps.image.name);
  return h('button', {
    type: 'button',
    title: name,
    class: 'relative aspect-square cursor-pointer overflow-hidden rounded-sm bg-gray-100 hover:ring-2 hover:ring-gray-900 focus-visible:ring-2 focus-visible:ring-gray-900 focus-visible:outline-none dark:bg-warm-800 dark:hover:ring-warm-100 dark:focus-visible:ring-warm-100',
    onClick: attrs.onClick,
  }, [
    // unpublished images (a project's own) are dimmed
    h('img', { src: imageUrl(tileProps.image, 'thumbnail'), alt: name, loading: 'lazy', class: ['h-full w-full object-cover', Number(tileProps.image.publish) === 0 ? 'opacity-40' : ''] }),
  ]);
};
ImageTile.props = ['image'];

const isFetched = ref(false);
const project = ref(null);
const projects = ref([]);
const current = ref(null);
const search = ref('');

const label = item => [item.title, item.location].filter(Boolean).join(', ');

const visibleProjects = computed(() => {
  const terms = search.value.toLowerCase().split(/\s+/).filter(Boolean);
  return projects.value.filter(item => {
    const text = `${label(item)} ${item.year ?? ''}`.toLowerCase();
    return terms.every(term => text.includes(term));
  });
});

async function fetch() {
  try {
    if (props.type === 'project') {
      const { data } = await http.get(`/api/project/${props.projectId}`);
      project.value = data.project;
    }
    else {
      const url = props.type === 'landing' ? `/api/projects/${props.landing.view}/${props.landing.id}` : '/api/projects';
      const { data } = await http.get(url);
      projects.value = data.data;
      current.value = projects.value[0] ?? null;
    }
    isFetched.value = true;
  }
  catch {
    // Notified by the http error handler
  }
}

fetch();
</script>
