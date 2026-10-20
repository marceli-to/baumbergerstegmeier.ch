<template>
  <div>
    <ContentHeader>
      <h1>Startseite</h1>
    </ContentHeader>
    <TeaserBuilder type="home" />
  </div>
</template>
<script setup>
import ContentHeader from '@/components/ui/ContentHeader.vue';
import TeaserBuilder from '@/components/teasers/TeaserBuilder.vue';
</script>
