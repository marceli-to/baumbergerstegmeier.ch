<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'jobs' })">
    <Tabs v-model="tab" :tabs="tabs" :errors="Object.keys(errors).length ? ['data'] : []">
      <Tab name="data">
        <FormField
          v-model="record.text"
          name="text"
          label="Text (falls keine Inserate vorhanden sind)"
          type="editor"
          :errors="errors"
          tall
          required
        />
      </Tab>
      <Tab name="images">
        <ImageField v-model:images="record.images" :label="null" />
      </Tab>
    </Tabs>

    <template #sidebar>
      <FormField v-model="record.publish" name="publish" label="Publizieren?" type="checkbox" />
    </template>
  </ResourceForm>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import ResourceForm from '@/components/resource/ResourceForm.vue';
import FormField from '@/components/ui/form/FormField.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import Tab from '@/components/ui/tabs/Tab.vue';
import ImageField from '@/components/media/ImageField.vue';
import { useResourceForm } from '@/composables/useResourceForm';

const props = defineProps({
  type: { type: String, required: true },
});

const router = useRouter();

const tabs = [
  { key: 'data', label: 'Inhalt' },
  { key: 'images', label: 'Bilder' },
];
const tab = ref('data');

const { record, errors, isEdit, isLoading, isFetched, title, submit } = useResourceForm({
  type: props.type,
  endpoint: 'job',
  key: 'job',
  model: () => ({
    text: null,
    publish: 1,
    images: [],
  }),
  redirect: { name: 'jobs' },
  titles: { create: 'Job-Seite hinzufügen', edit: 'Job-Seite bearbeiten' },
});
</script>
