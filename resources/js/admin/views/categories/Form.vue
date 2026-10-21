<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'categories' })">
    <FormField v-model="record.description" name="description" label="Bezeichnung" :errors="errors" required />

    <template #sidebar>
      <FormField v-model="record.publish" name="publish" label="Publiziert" type="checkbox" />
    </template>
  </ResourceForm>
</template>
<script setup>
import { useRouter } from 'vue-router';
import ResourceForm from '@/components/resource/ResourceForm.vue';
import FormField from '@/components/ui/form/FormField.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const router = useRouter();

const { record, errors, isEdit, isLoading, isFetched, title, submit } = useResourceForm({
  type: props.type,
  endpoint: 'project/category',
  key: 'category',
  model: () => ({
    description: null,
    publish: 1,
  }),
  redirect: { name: 'categories' },
  titles: { create: 'Neue Projekt-Kategorie', edit: 'Projekt-Kategorie bearbeiten' },
});
</script>
