<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'projects' })">
    <Tabs v-model="tab" :tabs="tabs" :errors="tabErrors">
      <Tab name="data">
        <div class="flex flex-col gap-24">
          <FormField v-model="record.title" name="title" label="Titel" :errors="errors" required />
          <div class="grid grid-cols-2 gap-24">
            <FormField v-model="record.title_menu" name="title_menu" label="Titel Menü" hint="Optional" />
            <FormField v-model="record.title_worklist" name="title_worklist" label="Titel Werkliste" hint="Optional" />
          </div>
          <div class="grid grid-cols-[1fr_160px] gap-24">
            <FormField v-model="record.location" name="location" label="Ort" />
            <FormField v-model="record.year" name="year" label="Jahr" maxlength="4" :errors="errors" required />
          </div>
          <FormField v-model="record.text" name="text" label="Text" type="editor" />
          <FormField v-model="record.info" name="info" label="Info" type="editor" />
        </div>
      </Tab>
      <Tab name="worklist">
        <FormField v-model="record.type" name="type" label="Typ" :errors="errors" required />
      </Tab>
      <Tab name="images">
        <ImageField
          v-model:images="record.images"
          :label="null"
          :flags="[{ key: 'worklist', label: 'Werkliste' }, { key: 'cover', label: 'Cover' }, { key: 'lightbox', label: 'Lightbox' }]"
          owner="Projekt"
        />
      </Tab>
    </Tabs>

    <template v-if="isEdit" #actions>
      <!-- Only projects with a detail page have a preview -->
      <a
        v-if="Number(record.feature) === 1"
        :href="`/projekt/vorschau/${route.params.id}`"
        target="_blank"
        class="inline-flex items-center gap-8 rounded-md bg-gray-100 px-16 py-8 text-sm text-gray-700 transition-colors hover:bg-gray-200 dark:bg-warm-800 dark:text-warm-300 dark:hover:bg-warm-700"
      >
        <PhArrowSquareOut :size="16" weight="light" />
        Vorschau
      </a>
      <router-link
        :to="{ name: 'project-teasers', params: { id: route.params.id } }"
        class="inline-flex items-center gap-8 rounded-md bg-gray-100 px-16 py-8 text-sm text-gray-700 transition-colors hover:bg-gray-200 dark:bg-warm-800 dark:text-warm-300 dark:hover:bg-warm-700"
      >
        <PhSquaresFour :size="16" weight="light" />
        Teaser
      </router-link>
    </template>

    <template #sidebar>
      <div class="flex flex-col gap-12">
        <FormField v-model="record.publish" name="publish" label="Publiziert" type="checkbox" />
        <FormField v-model="record.feature" name="feature" label="Detailseite" type="checkbox" />
        <FormField v-model="record.landing" name="landing" label="Startseite" type="checkbox" />
      </div>
      <!-- A project can have several categories and states -->
      <div v-for="group in groups" :key="group.key">
        <FormLabel :error="errors[group.key] ? `${group.label}: Pflichtfeld` : null">{{ group.label }} *</FormLabel>
        <div class="flex flex-col gap-12">
          <FormCheckbox
            v-for="item in group.items"
            :id="`${group.key}_${item.id}`"
            :key="item.id"
            :model-value="record[group.key].includes(item.id) ? 1 : 0"
            @update:model-value="checked => select(group.key, item.id, checked)"
          >{{ item.description }}</FormCheckbox>
        </div>
      </div>
      <FormField v-model="record.description" name="description" label="Beschreibung (SEO)" type="textarea" />
    </template>
  </ResourceForm>
</template>
<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { PhArrowSquareOut, PhSquaresFour } from '@phosphor-icons/vue';
import ResourceForm from '@/components/resource/ResourceForm.vue';
import FormField from '@/components/ui/form/FormField.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormCheckbox from '@/components/ui/form/FormCheckbox.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import Tab from '@/components/ui/tabs/Tab.vue';
import ImageField from '@/components/media/ImageField.vue';
import { useResourceForm } from '@/composables/useResourceForm';
import http from '@/lib/http';

const props = defineProps({
  type: { type: String, required: true },
});

const route = useRoute();
const router = useRouter();

const tabs = [
  { key: 'data', label: 'Projekt' },
  { key: 'worklist', label: 'Werkliste' },
  { key: 'images', label: 'Bilder' },
];
const tab = ref('data');

// Which tab holds which validated field; categories and states are in the
// sidebar (no tab)
const fieldTabs = { title: 'data', year: 'data', type: 'worklist' };
const tabErrors = computed(() => Object.keys(errors.value).map(field => fieldTabs[field]).filter(Boolean));

const categories = ref([]);
const states = ref([]);

const groups = computed(() => [
  { key: 'category_ids', label: 'Kategorie', items: categories.value },
  { key: 'state_ids', label: 'Status', items: states.value },
]);

// The checkboxes store 1/0; the record holds the checked ids
function select(key, id, checked) {
  const ids = record.value[key].filter(value => value !== id);
  record.value[key] = checked ? [...ids, id] : ids;
  clearError(key);
}

// The edit response brings categories and states along
const load = props.type === 'create'
  ? [
    () => http.get('/api/project/categories').then(({ data }) => categories.value = data.data),
    () => http.get('/api/project/states').then(({ data }) => states.value = data.data),
  ]
  : [];

const { record, errors, isEdit, isLoading, isFetched, title, submit, clearError } = useResourceForm({
  type: props.type,
  endpoint: 'project',
  key: 'project',
  model: () => ({
    title: null,
    title_menu: null,
    title_worklist: null,
    description: null,
    text: null,
    info: null,
    year: null,
    location: null,
    type: null,
    category_ids: [],
    state_ids: [],
    publish: 1,
    feature: 0,
    landing: 0,
    images: [],
  }),
  redirect: { name: 'projects' },
  titles: { create: 'Neues Projekt', edit: 'Projekt bearbeiten' },
  load,
  loaded: data => {
    categories.value = data.categories;
    states.value = data.states;
  },
});
</script>
