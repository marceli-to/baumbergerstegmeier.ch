<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'job-articles' })">
    <div class="flex flex-col gap-24">
      <FormField v-model="record.title" name="title" label="Titel" :errors="errors" required />
      <FormField v-model="record.description" name="description" label="Text" type="editor" :errors="errors" tall required />
    </div>

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
  endpoint: 'job-article',
  key: 'jobArticle',
  model: () => ({
    title: null,
    description: null,
    publish: 1,
  }),
  redirect: { name: 'job-articles' },
  titles: { create: 'Neues Inserat', edit: 'Inserat bearbeiten' },
});
</script>
