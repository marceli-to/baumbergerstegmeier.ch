<template>
  <div>
    <ContentHeader>
      <h1 v-if="title">Projekt Landing «{{ title }}»</h1>
    </ContentHeader>
    <TeaserBuilder type="landing" :landing="landing" />
    <ContentFooter :back="{ name: 'landings' }" />
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ContentFooter from '@/components/ui/ContentFooter.vue';
import TeaserBuilder from '@/components/teasers/TeaserBuilder.vue';
import http from '@/lib/http';

// The landing of a project category or state (view)
const route = useRoute();
const landing = { view: route.params.view, id: route.params.id };
const title = ref(null);

// The title used to be part of the URL; now it comes from the record
http.get(`/api/project/${landing.view}/${landing.id}`)
  .then(({ data }) => title.value = data[landing.view].description)
  .catch(() => {});
</script>
