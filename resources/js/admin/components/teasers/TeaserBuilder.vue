<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <div class="teasers" v-if="isFetched">
      <div v-for="(column, index) in columns" :key="index" class="teasers__column">
        <SortableList v-model="columns[index]" class="teasers__items" @end="items => order(index, items)">
          <div v-for="item in column" :key="item.id" class="teasers__item is-draggable">
            <h2 class="teasers__project" v-if="type === 'landing' && item.project">{{ item.project.title }}</h2>
            <TeaserArticle v-if="item.article_id" :item="item" @toggle="toggle" @destroy="destroy" />
            <TeaserImage v-else :item="item" @toggle="toggle" @destroy="destroy" />
          </div>
        </SortableList>
        <div class="teasers__actions">
          <a href="javascript:;" class="btn-select has-icon" @click.prevent="picker = { type: 'image', column: index }">
            <PhImage :size="16" weight="light" />
            <span>{{ type === 'project' ? 'Bild auswählen' : 'Projekt' }}</span>
          </a>
          <a href="javascript:;" class="btn-select has-icon" @click.prevent="picker = { type: 'article', column: index }" v-if="type === 'home'">
            <PhTextAlignLeft :size="16" weight="light" />
            <span>Artikel</span>
          </a>
        </div>
      </div>
    </div>

    <Lightbox :open="picker?.type === 'image'" :title="type === 'project' ? 'Bild wählen' : 'Projekt wählen'" @close="picker = null">
      <ImagePicker v-if="picker?.type === 'image'" :type="type" :project-id="projectId" :landing="landing" @select="store" />
    </Lightbox>
    <Lightbox :open="picker?.type === 'article'" title="Artikel wählen" size="small" @close="picker = null">
      <ArticlePicker v-if="picker?.type === 'article'" @select="store" />
    </Lightbox>
  </div>
</template>
<script setup>
import { ref, computed, onBeforeUnmount } from 'vue';
import { PhImage, PhTextAlignLeft } from '@phosphor-icons/vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import Lightbox from '@/components/ui/Lightbox.vue';
import SortableList from '@/components/ui/SortableList.vue';
import TeaserImage from '@/components/teasers/TeaserImage.vue';
import TeaserArticle from '@/components/teasers/TeaserArticle.vue';
import ImagePicker from '@/components/teasers/ImagePicker.vue';
import ArticlePicker from '@/components/teasers/ArticlePicker.vue';
import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete } from '@/lib/utils';

// Three columns of teasers (project images, on the home also articles),
// for the home, a project or a project landing. Each column is ordered on
// its own by dragging; every change is saved right away.
const props = defineProps({
  // 'home' | 'project' | 'landing'
  type: { type: String, required: true },
  projectId: { type: [String, Number], default: null },
  // { view: 'category' | 'state', id }
  landing: { type: Object, default: null },
});

// Landings have their own table and routes; home and project share the teasers
const api = computed(() => {
  if (props.type === 'landing') {
    return {
      list: `/api/project/landing/items/${props.landing.view}/${props.landing.id}`,
      item: '/api/project/landing/item',
      key: 'item',
    };
  }
  return {
    list: props.type === 'project' ? `/api/teasers/project/${props.projectId}` : '/api/teasers/home',
    item: '/api/teaser',
    key: 'teaser',
  };
});

const isLoading = ref(false);
const isFetched = ref(false);
const columns = ref([[], [], []]);

// { type: 'image' | 'article', column }
const picker = ref(null);

async function request(call, success) {
  isLoading.value = true;
  try {
    const response = await call();
    if (success) {
      notify({ type: 'success', text: success });
    }
    return response;
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}

async function fetch() {
  const response = await request(() => http.get(api.value.list));
  if (response) {
    // The API leaves out empty columns ({ 0: [...], 2: [...] } or [])
    const items = response.data.items;
    columns.value = [0, 1, 2].map(index => items[index] ?? []);
    isFetched.value = true;
  }
}

// selection: { image_id, project_id } or { article_id }
async function store(selection) {
  const { column } = picker.value;
  picker.value = null;
  const item = { column, ...selection };
  if (props.type === 'landing') {
    item[`${props.landing.view}_id`] = props.landing.id;
  }
  else {
    item.type = props.type;
  }
  const response = await request(() => http.post(api.value.item, item), 'Daten gespeichert');
  if (response) {
    columns.value[column].push(response.data[api.value.key]);
  }
}

async function toggle(item) {
  const response = await request(() => http.get(`${api.value.item}/state/${item.id}`), 'Daten aktualisiert');
  if (response) {
    item.publish = response.data;
  }
}

async function destroy(item) {
  // Only the teaser goes, the image or article stays
  if (!await confirmDelete('Teaser entfernen?', { text: 'Das Bild oder der Artikel selbst bleibt erhalten.', action: 'Entfernen' })) {
    return;
  }
  if (await request(() => http.delete(`${api.value.item}/${item.id}`), 'Daten gelöscht')) {
    columns.value.forEach(column => {
      const index = column.indexOf(item);
      if (index !== -1) {
        column.splice(index, 1);
      }
    });
  }
}

// The order API takes { items: [{ id, position }] } (not useOrder's
// { id, order }). Several drags in a column are sent once.
const timers = {};

function order(column, items) {
  items.forEach((item, index) => item.position = index);
  clearTimeout(timers[column]);
  timers[column] = setTimeout(async () => {
    try {
      await http.post(`${api.value.item}/order`, { items: items.map(({ id, position }) => ({ id, position })) });
      notify({ type: 'success', text: 'Reihenfolge angepasst' });
    }
    catch {
      // Notified by the http error handler
    }
  }, 500);
}

onBeforeUnmount(() => Object.values(timers).forEach(clearTimeout));

fetch();
</script>
