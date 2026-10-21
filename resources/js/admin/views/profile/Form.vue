<template>
  <ResourceForm :title="title" :is-edit="isEdit" :loading="!isFetched" :busy="isLoading" @submit="submit" @cancel="router.push({ name: 'profile' })">
    <Tabs v-model="tab" :tabs="tabs" :errors="Object.keys(errors).length ? ['data'] : []">
      <Tab name="data">
        <div class="flex flex-col gap-24">
          <FormField v-model="record.title_bsa" name="title_bsa" label="Titel BSA" :errors="errors" required />
          <FormField v-model="record.text_bsa" name="text_bsa" label="Beschreibung BSA" type="editor" :errors="errors" tall />
          <FormField v-model="record.title_bsemi" name="title_bsemi" label="Titel BS+EMI" :errors="errors" required />
          <FormField v-model="record.text_bsemi" name="text_bsemi" label="Beschreibung BS+EMI" type="editor" :errors="errors" tall />
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
  endpoint: 'profile',
  key: 'profile',
  model: () => ({
    title_bsa: null,
    text_bsa: null,
    title_bsemi: null,
    text_bsemi: null,
    publish: 1,
    images: [],
  }),
  redirect: { name: 'profile' },
  titles: { create: 'Profil hinzufügen', edit: 'Profil bearbeiten' },
});
</script>
