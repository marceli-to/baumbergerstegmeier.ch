<template>
  <div>
    <LoadingIndicator v-if="isLoading || isSavingCategory" />
    <form @submit.prevent="submit" v-if="isFetched">
      <ContentHeader>
        <h1>{{ title }}</h1>
      </ContentHeader>
      <Tabs :tabs="tabs" v-model="tab" :errors="Object.keys(errors).length ? ['data'] : []" />
      <div v-show="tab === 'data'">
        <div :class="['form-row', { 'has-error': errors.periode }]">
          <label>Zeitraum</label>
          <input type="text" v-model="record.periode" @focus="clearError('periode')">
          <LabelRequired />
        </div>
        <div :class="['form-row', { 'has-error': errors.description }]">
          <label>Beschreibung</label>
          <textarea v-model="record.description" @focus="clearError('description')"></textarea>
          <LabelRequired />
        </div>
        <div class="form-row">
          <div class="cv-category-label">
            <label>Kategorie (optional)</label>
            <a href="javascript:;" class="feather-icon" title="Kategorie erstellen" @click.prevent="openCategory()">
              <PhPlus :size="18" weight="light" />
            </a>
          </div>
          <div class="select-wrapper">
            <select v-model="record.cv_category_id">
              <option :value="null">Bitte wählen...</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.description }}</option>
            </select>
          </div>
        </div>
      </div>
      <div v-show="tab === 'settings'">
        <div class="form-row">
          <Toggle label="Publizieren?" name="publish" v-model="record.publish" />
        </div>
      </div>
      <ContentFooter :back="{ name: 'cvs', params: { id: record.employee_id } }" submit />
    </form>

    <!-- A new category right from the entry (outside the form: no nested forms) -->
    <Lightbox :open="isCategoryOpen" title="Kategorie erstellen" size="small" @close="isCategoryOpen = false">
      <form id="cv-category-create" @submit.prevent="storeCategory()">
        <div :class="['form-row', { 'has-error': categoryError }]">
          <label>Name</label>
          <input type="text" v-model="category.description" @focus="categoryError = false">
          <LabelRequired />
        </div>
      </form>
      <template #footer>
        <button type="submit" class="btn-primary" form="cv-category-create">Speichern</button>
        <a href="javascript:;" @click.prevent="isCategoryOpen = false">Abbrechen</a>
      </template>
    </Lightbox>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { PhPlus } from '@phosphor-icons/vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import Tabs from '@/components/ui/Tabs.vue';
import LabelRequired from '@/components/ui/LabelRequired.vue';
import Toggle from '@/components/ui/Toggle.vue';
import Lightbox from '@/components/ui/Lightbox.vue';
import http, { validationErrors } from '@/lib/http';
import { notify } from '@/lib/notify';

const props = defineProps({
  type: { type: String, required: true },
});

const route = useRoute();
const router = useRouter();

const tabs = [
  { key: 'data', label: 'Daten' },
  { key: 'settings', label: 'Einstellungen' },
];
const tab = ref('data');

const categories = ref([]);

// Not useResourceForm: the API reads an entry from GET /api/cvs/{id}
// (GET /api/cv/{id} is an employee's list) but saves to /api/cv
const isEdit = props.type === 'edit';
const title = isEdit ? 'CV bearbeiten' : 'CV hinzufügen';
const record = ref({
  periode: null,
  description: null,
  cv_category_id: null,
  employee_id: Number(route.params.employeeId) || null,
  publish: 1,
});
const errors = ref({});
const isLoading = ref(false);
const isFetched = ref(false);

async function init() {
  isLoading.value = true;
  try {
    if (isEdit) {
      const { data } = await http.get(`/api/cvs/${route.params.id}`);
      record.value = data.cv;
      categories.value = data.categories;
    }
    else {
      categories.value = (await http.get('/api/cv/categories')).data.data;
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

async function submit() {
  isLoading.value = true;
  try {
    if (isEdit) {
      await http.put(`/api/cv/${route.params.id}`, record.value);
    }
    else {
      await http.post('/api/cv', record.value);
    }
    errors.value = {};
    router.push({ name: 'cvs', params: { id: record.value.employee_id } });
    notify({ type: 'success', text: isEdit ? 'Änderungen gespeichert!' : 'Daten erfasst!' });
  }
  catch (error) {
    errors.value = validationErrors(error) ?? errors.value;
  }
  finally {
    isLoading.value = false;
  }
}

// A field's error goes away once it gets focus
function clearError(field) {
  delete errors.value[field];
}

init();

const isCategoryOpen = ref(false);
const isSavingCategory = ref(false);
const categoryError = ref(false);
const category = reactive({ description: null, publish: 1 });

function openCategory() {
  category.description = null;
  categoryError.value = false;
  isCategoryOpen.value = true;
}

// Saved right away; the new category is selected for this entry
async function storeCategory() {
  isSavingCategory.value = true;
  try {
    const { data } = await http.post('/api/cv/category', category);
    categories.value.push(data.cvCategory);
    record.value.cv_category_id = data.cvCategory.id;
    isCategoryOpen.value = false;
    notify({ type: 'success', text: 'Daten erfasst!' });
  }
  catch (error) {
    categoryError.value = !!validationErrors(error)?.description;
  }
  finally {
    isSavingCategory.value = false;
  }
}
</script>
