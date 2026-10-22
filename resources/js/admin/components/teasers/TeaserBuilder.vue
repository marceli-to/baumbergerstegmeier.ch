<template>
  <div>
    <div v-if="!isFetched" class="text-sm text-gray-400 dark:text-warm-500">Laden...</div>
    <div v-else class="grid grid-cols-3 items-start gap-24">
      <div v-for="(column, index) in columns" :key="index" class="flex flex-col gap-16">
        <div class="border-b border-gray-900/10 pb-8 text-xs text-gray-400 dark:border-warm-700/50 dark:text-warm-500">
          Spalte {{ index + 1 }}
        </div>
        <draggable
          v-model="columns[index]"
          item-key="id"
          group="teasers"
          class="flex min-h-80 flex-col gap-16"
          ghost-class="opacity-30"
          :data-column="index"
          :animation="150"
          @end="moved"
        >
          <template #item="{ element: item }">
            <div class="cursor-grab active:cursor-grabbing">
              <TeaserArticle v-if="item.article_id" :item="item" @toggle="toggle" @destroy="destroy" />
              <TeaserImage v-else :item="item" :project="type === 'landing' ? item.project?.title : null" @toggle="toggle" @destroy="destroy" />
            </div>
          </template>
        </draggable>
        <div class="flex gap-8">
          <button type="button" :class="addClass" @click="picker = { type: 'image', column: index }">
            <PhImage :size="16" weight="light" />
            <span>{{ type === 'project' ? 'Bild' : 'Projekt' }}</span>
          </button>
          <button v-if="type === 'home'" type="button" :class="addClass" @click="picker = { type: 'article', column: index }">
            <PhTextAlignLeft :size="16" weight="light" />
            <span>Artikel</span>
          </button>
        </div>
      </div>
    </div>

    <Drawer :open="picker?.type === 'image'" :title="type === 'project' ? 'Bild wählen' : 'Projektbild wählen'" size="xl" @close="picker = null">
      <template v-if="type !== 'project'" #header>
        <h3 class="text-sm font-medium text-gray-900 dark:text-warm-100">Projektbild wählen</h3>
        <div class="absolute top-1/2 left-1/2 w-300 -translate-1/2">
          <FormInput v-model="search" name="picker_search" placeholder="Projekt suchen" />
        </div>
      </template>
      <ImagePicker v-if="picker?.type === 'image'" :type="type" :project-id="projectId" :landing="landing" :used="usedImages" :search="search" @select="store" />
    </Drawer>
    <Drawer :open="picker?.type === 'article'" title="Artikel wählen" size="md" @close="picker = null">
      <ArticlePicker v-if="picker?.type === 'article'" @select="store" />
    </Drawer>
  </div>
</template>
<script setup>
import { ref, computed, watch, onBeforeUnmount } from 'vue';
import draggable from 'vuedraggable';
import { PhImage, PhTextAlignLeft } from '@phosphor-icons/vue';
import Drawer from '@/components/ui/drawer/Drawer.vue';
import TeaserImage from '@/components/teasers/TeaserImage.vue';
import TeaserArticle from '@/components/teasers/TeaserArticle.vue';
import ImagePicker from '@/components/teasers/ImagePicker.vue';
import ArticlePicker from '@/components/teasers/ArticlePicker.vue';
import FormInput from '@/components/ui/form/FormInput.vue';
import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { confirmDelete } from '@/lib/utils';

// Three columns of teasers (project images, on the home also articles),
// for the home, a project or a project landing. Teasers are ordered by
// dragging, also into another column; every change is saved right away.
const props = defineProps({
  // 'home' | 'project' | 'landing'
  type: { type: String, required: true },
  projectId: { type: [String, Number], default: null },
  // { view: 'category' | 'state', id }
  landing: { type: Object, default: null },
});

const addClass = 'flex flex-1 cursor-pointer items-center justify-center gap-8 rounded-md border border-dashed border-gray-300 px-12 py-10 text-xs text-gray-500 transition-colors hover:border-gray-400 hover:bg-gray-50 hover:text-gray-900 dark:border-warm-600 dark:text-warm-400 dark:hover:border-warm-500 dark:hover:bg-warm-800 dark:hover:text-warm-100';

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

const isFetched = ref(false);
const columns = ref([[], [], []]);

// { type: 'image' | 'article', column }
const picker = ref(null);

// Images already shown as a teaser, marked in the picker
const usedImages = computed(() => new Set(columns.value.flat().map(item => item.image_id).filter(Boolean)));

// The image picker's project search, in the drawer's header; empty on opening
const search = ref('');
watch(picker, () => search.value = '');

async function request(call, success) {
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
    columns.value = columns.value.map(column => column.filter(other => other.id !== item.id));
  }
}

// A teaser can be dragged within its column or into another one; both
// columns are then saved
function moved({ from, to, oldIndex, newIndex }) {
  if (from === to && oldIndex === newIndex) {
    return;
  }
  order(Number(from.dataset.column));
  order(Number(to.dataset.column));
}

// The order API takes { items: [{ id, position, column }] } (not useOrder's
// { id, order }). Several drags are sent at once.
const changed = new Set();
let timer = null;

function order(column) {
  changed.add(column);
  clearTimeout(timer);
  timer = setTimeout(async () => {
    const items = [...changed].flatMap(index => columns.value[index].map((item, position) => {
      item.position = position;
      item.column = index;
      return { id: item.id, position, column: index };
    }));
    changed.clear();
    try {
      await http.post(`${api.value.item}/order`, { items });
      notify({ type: 'success', text: 'Reihenfolge angepasst' });
    }
    catch {
      // Notified by the http error handler
    }
  }, 500);
}

onBeforeUnmount(() => clearTimeout(timer));

fetch();
</script>
