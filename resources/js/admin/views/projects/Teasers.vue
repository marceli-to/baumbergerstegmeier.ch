<template>
  <div>
    <ContentHeader>
      <h1 v-if="project">Projekt «{{ [project.title, project.location].filter(Boolean).join(', ') }}»</h1>
    </ContentHeader>
    <TeaserBuilder type="project" :project-id="route.params.id" />
    <ContentFooter :back="{ name: 'projects' }" />
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import TeaserBuilder from '@/components/teasers/TeaserBuilder.vue';
import http from '@/lib/http';

const route = useRoute();
const project = ref(null);

// Only for the title
http.get(`/api/project/${route.params.id}`)
  .then(({ data }) => project.value = data.project)
  .catch(() => {});
</script>
