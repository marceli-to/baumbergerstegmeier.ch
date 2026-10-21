<template>
  <div>
    <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="back">
      <Tabs v-model="tab" :tabs="tabs" :errors="Object.keys(errors).length ? ['data'] : []">
        <Tab name="data">
          <div class="flex flex-col gap-24">
            <div class="w-240">
              <FormField v-model="record.periode" name="periode" label="Zeitraum" :errors="errors" required />
            </div>
            <FormField v-model="record.description" name="description" label="Beschreibung" type="textarea" :errors="errors" required />
          </div>
        </Tab>
      </Tabs>

      <template #sidebar>
        <FormField v-model="record.publish" name="publish" label="Publiziert" type="checkbox" />
        <div>
          <FormField v-model="record.cv_category_id" name="cv_category_id" label="Kategorie" type="select" :options="toOptions(categories)" placeholder="Keine" />
          <button type="button" class="mt-8 inline-flex cursor-pointer items-center gap-4 text-xs text-gray-500 transition-colors hover:text-gray-900 dark:text-warm-400 dark:hover:text-warm-100" @click="openCategory">
            <PhPlus :size="12" weight="light" />
            Neue Kategorie
          </button>
        </div>
      </template>
    </ResourceForm>

    <!-- A new category right from the entry (outside the form: no nested forms) -->
    <AppDialog :open="isCategoryOpen" title="Neue Kategorie" @close="isCategoryOpen = false">
      <form id="cv-category-create" @submit.prevent="storeCategory">
        <FormField v-model="category.description" name="description" label="Bezeichnung" :errors="categoryErrors" required />
      </form>
      <template #footer>
        <div class="flex justify-end gap-12">
          <FormButton variant="secondary" @click="isCategoryOpen = false">Abbrechen</FormButton>
          <FormButton type="submit" form="cv-category-create" :disabled="isSavingCategory">Erstellen</FormButton>
        </div>
      </template>
    </AppDialog>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { PhPlus } from '@phosphor-icons/vue';
import ResourceForm from '@/components/resource/ResourceForm.vue';
import FormField from '@/components/ui/form/FormField.vue';
import FormButton from '@/components/ui/form/FormButton.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import Tab from '@/components/ui/tabs/Tab.vue';
import AppDialog from '@/components/ui/dialog/AppDialog.vue';
import http, { validationErrors } from '@/lib/http';
import { notify } from '@/lib/notify';
import { toOptions } from '@/lib/utils';

const props = defineProps({
  type: { type: String, required: true },
});

const route = useRoute();
const router = useRouter();

const tabs = [
  { key: 'data', label: 'Daten' },
];
const tab = ref('data');

const categories = ref([]);

// Not useResourceForm: the API reads an entry from GET /api/cvs/{id}
// (GET /api/cv/{id} is an employee's list) but saves to /api/cv
const isEdit = props.type === 'edit';
const title = isEdit ? 'Eintrag bearbeiten' : 'Neuer Eintrag';
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

function back() {
  router.push({ name: 'cvs', params: { id: record.value.employee_id } });
}

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
    back();
    notify({ type: 'success', text: isEdit ? 'Änderungen gespeichert!' : 'Daten erfasst!' });
  }
  catch (error) {
    errors.value = validationErrors(error) ?? errors.value;
  }
  finally {
    isLoading.value = false;
  }
}

init();

const isCategoryOpen = ref(false);
const isSavingCategory = ref(false);
const categoryErrors = ref({});
const category = reactive({ description: null, publish: 1 });

function openCategory() {
  category.description = null;
  categoryErrors.value = {};
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
    categoryErrors.value = validationErrors(error) ?? categoryErrors.value;
  }
  finally {
    isSavingCategory.value = false;
  }
}
</script>
