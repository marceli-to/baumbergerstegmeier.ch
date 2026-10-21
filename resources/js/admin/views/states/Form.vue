<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'states' })">
    <FormField v-model="record.description" name="description" label="Bezeichnung" :errors="errors" required />

    <template #sidebar>
      <FormField v-model="record.publish" name="publish" label="Publiziert" type="checkbox" />
      <FormField v-model="record.show_in_menu" name="show_in_menu" label="Im Menü" type="checkbox" />
      <FormField v-model="record.has_landing" name="has_landing" label="Übersichtsseite" type="checkbox" />
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
  endpoint: 'project/state',
  key: 'state',
  model: () => ({
    description: null,
    publish: 1,
    show_in_menu: 1,
    has_landing: 1,
  }),
  redirect: { name: 'states' },
  titles: { create: 'Neuer Projekt-Status', edit: 'Projekt-Status bearbeiten' },
});
</script>
