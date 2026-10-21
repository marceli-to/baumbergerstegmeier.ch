<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'contact' })">
    <Tabs v-model="tab" :tabs="tabs" :errors="Object.keys(errors).length ? ['data'] : []">
      <Tab name="data">
        <div class="flex flex-col gap-24">
          <FormField v-model="record.address" name="address" label="Adresse" type="editor" :errors="errors" required />
          <FormField v-model="record.description" name="description" label="Beschreibung" type="editor" :errors="errors" />
          <FormField v-model="record.maps_uri" name="maps_uri" label="Google Maps URL" :errors="errors" />
          <FormField v-model="record.imprint" name="imprint" label="Impressum" type="editor" :errors="errors" tall />
          <FormField v-model="record.privacy" name="privacy" label="Datenschutz" type="editor" :errors="errors" tall />
        </div>
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
  { key: 'data', label: 'Text' },
  { key: 'images', label: 'Bilder' },
];
const tab = ref('data');

const { record, errors, isEdit, isLoading, isFetched, title, submit } = useResourceForm({
  type: props.type,
  endpoint: 'contact',
  key: 'contact',
  model: () => ({
    address: null,
    description: null,
    maps_uri: null,
    imprint: null,
    privacy: null,
    publish: 1,
    images: [],
  }),
  redirect: { name: 'contact' },
  titles: { create: 'Kontakt hinzufügen', edit: 'Kontakt bearbeiten' },
});
</script>
