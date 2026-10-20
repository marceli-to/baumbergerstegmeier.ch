<template>
  <div class="teaser-picker-root">
    <LoadingIndicator v-if="isLoading" />
    <!-- Project teasers: the project's own images -->
    <template v-if="type === 'project'">
      <ul role="list" class="teaser-picker__images" v-if="project?.images.length">
        <li v-for="image in project.images" :key="image.id">
          <button type="button" class="teaser-picker__image" @click="emit('select', { image_id: image.id, project_id: project.id })">
            <img :src="imageUrl(image, 'thumbnail')" height="300" width="300" loading="lazy" alt="">
            <span>{{ image.caption || image.original_name }}</span>
          </button>
        </li>
      </ul>
      <p class="teaser-picker__empty" v-else-if="isFetched">Es sind noch keine Bilder vorhanden.</p>
    </template>
    <!-- Home and landings: projects on the left, the chosen one's images on the right -->
    <template v-else-if="isFetched">
      <div class="teaser-picker" v-if="projects.length">
        <nav class="teaser-picker__sources" aria-label="Projekte">
          <ul role="list">
            <li v-for="item in projects" :key="item.id">
              <button
                type="button"
                :class="{ 'is-active': item === current }"
                :aria-current="item === current ? 'true' : undefined"
                @click="current = item"
              >{{ label(item) }}</button>
            </li>
          </ul>
        </nav>
        <div v-if="current">
          <h3 class="teaser-picker__title">{{ label(current) }}</h3>
          <ul role="list" class="teaser-picker__images" v-if="current.published_images.length">
            <li v-for="image in current.published_images" :key="image.id">
              <button type="button" class="teaser-picker__image" @click="emit('select', { image_id: image.id, project_id: current.id })">
                <img :src="imageUrl(image, 'thumbnail')" height="300" width="300" loading="lazy" alt="">
                <span>{{ image.caption || image.original_name }}</span>
              </button>
            </li>
          </ul>
          <p class="teaser-picker__empty" v-else>Es sind keine Bilder für dieses Projekt vorhanden.</p>
        </div>
      </div>
      <p class="teaser-picker__empty" v-else>Es sind keine Projekte vorhanden.</p>
    </template>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import http from '@/lib/http';
import { imageUrl } from '@/lib/images';

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

const isLoading = ref(false);
const isFetched = ref(false);
const project = ref(null);
const projects = ref([]);
const current = ref(null);

const label = item => [item.title, item.location].filter(Boolean).join(', ');

async function fetch() {
  isLoading.value = true;
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
  finally {
    isLoading.value = false;
  }
}

fetch();
</script>
