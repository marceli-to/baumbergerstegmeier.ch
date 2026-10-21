<template>
  <div>
    <PageHeader :title="title ? `Projekt Landing «${title}»` : 'Projekt Landing'" />
    <FormActions>
      <FormButton variant="secondary" @click="router.push({ name: 'landings' })">Zurück</FormButton>
    </FormActions>
    <TeaserBuilder type="landing" :landing="landing" />
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import PageHeader from '@/components/layout/PageHeader.vue';
import FormActions from '@/components/ui/form/FormActions.vue';
import FormButton from '@/components/ui/form/FormButton.vue';
import TeaserBuilder from '@/components/teasers/TeaserBuilder.vue';
import http from '@/lib/http';

// The landing of a project category or state (view)
const route = useRoute();
const router = useRouter();
const landing = { view: route.params.view, id: route.params.id };
const title = ref(null);

// The title used to be part of the URL; now it comes from the record
http.get(`/api/project/${landing.view}/${landing.id}`)
  .then(({ data }) => title.value = data[landing.view].description)
  .catch(() => {});
</script>
