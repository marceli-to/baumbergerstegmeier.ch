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
    <nav class="w-300 shrink-0 overflow-y-auto border-r border-gray-200 p-8 dark:border-warm-800" aria-label="Projekte">
      <ul role="list" class="flex flex-col gap-4">
        <li v-for="item in visibleProjects" :key="item.id">
          <button
            type="button"
            class="block w-full cursor-pointer rounded-md px-10 py-8 text-left transition-colors"
            :class="item === current
              ? 'bg-gray-100 dark:bg-warm-800'
              : 'hover:bg-gray-50 dark:hover:bg-warm-800/50'"
            :aria-current="item === current ? 'true' : undefined"
            @click="current = item"
          >
            <span class="block truncate text-sm" :class="item === current ? 'text-gray-900 dark:text-warm-100' : 'text-gray-700 dark:text-warm-300'">{{ item.title }}</span>
            <span class="mt-2 block truncate text-xs text-gray-400 dark:text-warm-500">{{ meta(item) }}</span>
          </button>
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
  // ids of the images already shown as a teaser
  used: { type: Set, default: () => new Set() },
  // filters the projects (the field is in the drawer's header)
  search: { type: String, default: '' },
});

// ({ image_id, project_id })
const emit = defineEmits(['select']);

// A square tile showing the whole image (as cropped for the teasers),
// with the caption (or file name) on hover. Images already used are marked.
const ImageTile = (tileProps, { attrs }) => {
  const image = tileProps.image;
  const name = image.caption || image.original_name || displayName(image.name);
  const isUsed = props.used.has(image.id);
  return h('button', {
    type: 'button',
    title: isUsed ? `${name} (bereits verwendet)` : name,
    class: 'group relative flex aspect-square cursor-pointer items-center justify-center overflow-hidden rounded-sm bg-gray-100 p-8 transition-colors hover:bg-gray-200 focus-visible:ring-2 focus-visible:ring-gray-300 focus-visible:outline-none dark:bg-warm-800 dark:hover:bg-warm-700 dark:focus-visible:ring-warm-600',
    onClick: attrs.onClick,
  }, [
    // unpublished images (a project's own) and used ones are dimmed
    h('img', { src: imageUrl(image, 'crop', 400), alt: name, loading: 'lazy', class: ['max-h-full max-w-full object-contain', Number(image.publish) === 0 || isUsed ? 'opacity-40' : ''] }),
    isUsed && h('span', { class: 'absolute top-6 left-6 rounded-sm bg-white/90 px-6 py-2 text-[11px] text-gray-500 ring-1 ring-gray-900/5 dark:bg-warm-900/90 dark:text-warm-400 dark:ring-warm-100/10' }, 'Verwendet'),
    h('span', { class: 'absolute inset-x-0 bottom-0 truncate bg-white/90 px-8 py-4 text-left text-[11px] text-gray-500 opacity-0 transition-opacity group-hover:opacity-100 group-focus-visible:opacity-100 dark:bg-warm-900/90 dark:text-warm-400' }, name),
  ]);
};
ImageTile.props = ['image'];

const isFetched = ref(false);
const project = ref(null);
const projects = ref([]);
const current = ref(null);

const label = item => [item.title, item.location].filter(Boolean).join(', ');

// Location and year; some locations already end in a period (2026–2031)
const meta = item => (/\d{4}/.test(item.location ?? '') ? [item.location] : [item.location, item.year]).filter(Boolean).join(', ');

const visibleProjects = computed(() => {
  const terms = props.search.toLowerCase().split(/\s+/).filter(Boolean);
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
