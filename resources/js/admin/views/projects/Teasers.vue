<template>
  <div>
    <PageHeader :title="project ? `Projekt «${[project.title, project.location].filter(Boolean).join(', ')}»` : 'Teaser'" />
    <FormActions>
      <FormButton variant="secondary" @click="router.push({ name: 'projects' })">Zurück</FormButton>
    </FormActions>
    <TeaserBuilder type="project" :project-id="route.params.id" />
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

const route = useRoute();
const router = useRouter();
const project = ref(null);

// Only for the title
http.get(`/api/project/${route.params.id}`)
  .then(({ data }) => project.value = data.project)
  .catch(() => {});
</script>
